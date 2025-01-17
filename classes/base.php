<?php

namespace local_organization;

//TO DO: Change this into a Singleton and get rid of static functions
class base
{

    // Set constant for buttons
    const CONTEXT_TONE = 'TONE';
    const CONTEXT_LENGTH = 'LENGTH';
    // Set context for advisors
    const CONTEXT_UNIT = 'UNIT';
    const CONTEXT_DEPARTMENT = 'DEPARTMENT';

    /**
     * Creates the Moodle page header
     * @param string $url Current page url
     * @param string $pagetitle Page title
     * @param string $pageheading Page heading (Note hard coded to site fullname)
     * @param array $context The page context (SYSTEM, COURSE, MODULE etc)
     * @param string $pagelayout The page context (SYSTEM, COURSE, MODULE etc)
     * @return HTML Contains page information and loads all Javascript and CSS
     * @global \stdClass $CFG
     * @global \moodle_database $DB
     * @global \moodle_page $PAGE
     * @global \stdClass $SITE
     */
    public static function page($url, $pagetitle, $pageheading, $context = null, $pagelayout = 'base')
    {
        global $CFG, $PAGE, $SITE;


        $context = \context_system::instance();

        $PAGE->set_url($url);
        $PAGE->set_title($pagetitle);
        $PAGE->set_heading($pageheading);
        $PAGE->set_pagelayout($pagelayout);
        $PAGE->set_context($context);
        // We need datatables to work. So we load it from cdn
        // We also load one JS file that initialises all datatables.
        // This same file is used throughout, including in the blocks
        self::loadJQueryJS();
    }

    public static function loadJQueryJS()
    {
        global $CFG, $PAGE;
        $stringman = get_string_manager();
        $strings = $stringman->load_component_strings('local_yulearn', current_language());

        $PAGE->requires->jquery();
        $PAGE->requires->jquery_plugin('ui');
        $PAGE->requires->jquery_plugin('ui-css');
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js'), true);
        $PAGE->requires->js(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.js'), true);
        $PAGE->requires->css(new \moodle_url('https://cdn.datatables.net/v/bs4/jszip-2.5.0/dt-1.12.1/af-2.4.0/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/cr-1.5.6/date-1.1.2/fc-4.1.0/fh-3.2.4/kt-2.7.0/r-2.3.0/rg-1.2.0/rr-1.2.8/sc-2.0.7/sb-1.3.4/sp-2.0.2/sl-1.4.0/sr-1.1.1/datatables.min.css'));
        $PAGE->requires->strings_for_js(array_keys($strings), 'local_cria');
    }

    /**
     * Sets filemanager options
     * @param \stdClass $context
     * @param int $maxfiles
     * @return array
     * @global \stdClass $CFG
     */
    public static function getFileManagerOptions($context, $maxfiles = 1)
    {
        global $CFG;
        return array('subdirs' => 0, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => $maxfiles);
    }


    public static function getEditorOptions($context)
    {
        global $CFG;
        return array('subdirs' => 1, 'maxbytes' => $CFG->maxbytes, 'maxfiles' => -1,
            'changeformat' => 1, 'context' => $context, 'noclean' => 1, 'trusttext' => 0);
    }


    /**
     * Locale-formatted strftime using \IntlDateFormatter (PHP 8.1 compatible)
     * This provides a cross-platform alternative to strftime() for when it will be removed from PHP.
     * Note that output can be slightly different between libc sprintf and this function as it is using ICU.
     *
     * Usage:
     * use function \PHP81_BC\strftime;
     * echo strftime('%A %e %B %Y %X', new \DateTime('2021-09-28 00:00:00'), 'fr_FR');
     *
     * Original use:
     * \setlocale('fr_FR.UTF-8', LC_TIME);
     * echo \strftime('%A %e %B %Y %X', strtotime('2021-09-28 00:00:00'));
     *
     * @param string $format Date format
     * @param integer|string|DateTime $timestamp Timestamp
     * @return string
     * @author BohwaZ <https://bohwaz.net/>
     */
    public static function strftime(string $format, $timestamp = null, ?string $locale = null): string
    {
        if (null === $timestamp) {
            $timestamp = new \DateTime;
        } elseif (is_numeric($timestamp)) {
            $timestamp = date_create('@' . $timestamp);

            if ($timestamp) {
                $timestamp->setTimezone(new \DateTimezone(date_default_timezone_get()));
            }
        } elseif (is_string($timestamp)) {
            $timestamp = date_create($timestamp);
        }

        if (!($timestamp instanceof \DateTimeInterface)) {
            throw new \InvalidArgumentException('$timestamp argument is neither a valid UNIX timestamp, a valid date-time string or a DateTime object.');
        }

        $locale = substr((string)$locale, 0, 5);

        $intl_formats = [
            '%a' => 'EEE',    // An abbreviated textual representation of the day	Sun through Sat
            '%A' => 'EEEE',    // A full textual representation of the day	Sunday through Saturday
            '%b' => 'MMM',    // Abbreviated month name, based on the locale	Jan through Dec
            '%B' => 'MMMM',    // Full month name, based on the locale	January through December
            '%h' => 'MMM',    // Abbreviated month name, based on the locale (an alias of %b)	Jan through Dec
        ];

        $intl_formatter = function (\DateTimeInterface $timestamp, string $format) use ($intl_formats, $locale) {
            $tz = $timestamp->getTimezone();
            $date_type = \IntlDateFormatter::FULL;
            $time_type = \IntlDateFormatter::FULL;
            $pattern = '';

            // %c = Preferred date and time stamp based on locale
            // Example: Tue Feb 5 00:45:10 2009 for February 5, 2009 at 12:45:10 AM
            if ($format == '%c') {
                $date_type = \IntlDateFormatter::LONG;
                $time_type = \IntlDateFormatter::SHORT;
            }
            // %x = Preferred date representation based on locale, without the time
            // Example: 02/05/09 for February 5, 2009
            elseif ($format == '%x') {
                $date_type = \IntlDateFormatter::SHORT;
                $time_type = \IntlDateFormatter::NONE;
            } // Localized time format
            elseif ($format == '%X') {
                $date_type = \IntlDateFormatter::NONE;
                $time_type = \IntlDateFormatter::MEDIUM;
            } else {
                $pattern = $intl_formats[$format];
            }

            return (new \IntlDateFormatter($locale, $date_type, $time_type, $tz, null, $pattern))->format($timestamp);
        };

        // Same order as https://www.php.net/manual/en/function.strftime.php
        $translation_table = [
            // Day
            '%a' => $intl_formatter,
            '%A' => $intl_formatter,
            '%d' => 'd',
            '%e' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('j'));
            },
            '%j' => function ($timestamp) {
                // Day number in year, 001 to 366
                return sprintf('%03d', $timestamp->format('z') + 1);
            },
            '%u' => 'N',
            '%w' => 'w',

            // Week
            '%U' => function ($timestamp) {
                // Number of weeks between date and first Sunday of year
                $day = new \DateTime(sprintf('%d-01 Sunday', $timestamp->format('Y')));
                return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
            },
            '%V' => 'W',
            '%W' => function ($timestamp) {
                // Number of weeks between date and first Monday of year
                $day = new \DateTime(sprintf('%d-01 Monday', $timestamp->format('Y')));
                return sprintf('%02u', 1 + ($timestamp->format('z') - $day->format('z')) / 7);
            },

            // Month
            '%b' => $intl_formatter,
            '%B' => $intl_formatter,
            '%h' => $intl_formatter,
            '%m' => 'm',

            // Year
            '%C' => function ($timestamp) {
                // Century (-1): 19 for 20th century
                return floor($timestamp->format('Y') / 100);
            },
            '%g' => function ($timestamp) {
                return substr($timestamp->format('o'), -2);
            },
            '%G' => 'o',
            '%y' => 'y',
            '%Y' => 'Y',

            // Time
            '%H' => 'H',
            '%k' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('G'));
            },
            '%I' => 'h',
            '%l' => function ($timestamp) {
                return sprintf('% 2u', $timestamp->format('g'));
            },
            '%M' => 'i',
            '%p' => 'A', // AM PM (this is reversed on purpose!)
            '%P' => 'a', // am pm
            '%r' => 'h:i:s A', // %I:%M:%S %p
            '%R' => 'H:i', // %H:%M
            '%S' => 's',
            '%T' => 'H:i:s', // %H:%M:%S
            '%X' => $intl_formatter, // Preferred time representation based on locale, without the date

            // Timezone
            '%z' => 'O',
            '%Z' => 'T',

            // Time and Date Stamps
            '%c' => $intl_formatter,
            '%D' => 'm/d/Y',
            '%F' => 'Y-m-d',
            '%s' => 'U',
            '%x' => $intl_formatter,
        ];

        $out = preg_replace_callback('/(?<!%)(%[a-zA-Z])/', function ($match) use ($translation_table, $timestamp) {
            if ($match[1] == '%n') {
                return "\n";
            } elseif ($match[1] == '%t') {
                return "\t";
            }

            if (!isset($translation_table[$match[1]])) {
                throw new \InvalidArgumentException(sprintf('Format "%s" is unknown in time format', $match[1]));
            }

            $replace = $translation_table[$match[1]];

            if (is_string($replace)) {
                return $timestamp->format($replace);
            } else {
                return $replace($timestamp, $match[1]);
            }
        }, $format);

        $out = str_replace('%%', '%', $out);
        return $out;
    }

    /**
     * Check if the user has the capability
     * @param string $capability
     * @param \context $context
     * @param int $userid
     * @param bool $doanything
     * @param int $instance_id
     * @param string $user_context UNIT or DEPARTMENT or BOTH
     * @return bool
     */
    public static function has_capability($capability, $context, $userid = null, $doanything = true, $instance_id = 0, $user_context = 'BOTH')
    {
        global $USER, $DB;
        if (is_null($userid)) {
            $userid = $USER->id;
        }
        // Check if the user has the capability. If not, return false
        if (!has_capability($capability, $context, $userid, $doanything)) {
            return false;
        } else {
            // If the user has the capability, and the user context equals both
            if ($user_context == 'BOTH') {
                $sql = "SELECT * FROM {local_organization_advisor} WHERE user_id = ? AND instance_id = ?";
                $sql .= " AND (user_context = 'UNIT' OR user_context = 'DEPARTMENT')";
                if ($has_access = $DB->get_records_sql($sql, [$userid, $instance_id])) {
                    return true;
                }
                return false;
            } else {
                if ($has_access = $DB->get_record_sql("select * from {local_organization_advisor} loa left join {role_capabilities} mrc on loa.role_id=mrc.roleid where mrc.capability=? and loa.user_id = ? and loa.instance_id = ? and loa.user_context = ? and mrc.permission = ?", [$capability, $userid, $instance_id, $user_context, 1])) {
                    return true;
                }
                return false;
            }
        }
    }

    /**
     * Check if the user has the role
     * @param \context $context
     * @param int $role_id
     * @param int $userid
     * @param int $instance_id
     * @param string $user_context UNIT or DEPARTMENT or BOTH
     * @return bool
     */
    public static function has_role($context, $role_id, $userid = null, $instance_id = 0, $user_context = 'BOTH')
    {
        global $USER, $DB;

        if (is_null($userid)) {
            $userid = $USER->id;
        }
        $user_roles = get_user_roles($context, $userid);
        // loop through the user roles and check if the user has the role
        $has_role = false;
        foreach ($user_roles as $role) {
            if ($role->roleid == $role_id) {
               $has_role = true;
               break;
            }
        }
        // If does have the role, check if the user has the capability for this instance based on the user context
        if ($has_role) {
            // If the user has the capability, and the user context equals both
            if ($user_context == 'BOTH') {
                $sql = "SELECT * FROM {local_organization_advisor} WHERE user_id = ? AND instance_id = ?";
                $sql .= " AND (user_context = 'UNIT' OR user_context = 'DEPARTMENT')";
                if ($has_access = $DB->get_records_sql($sql, [$userid, $instance_id])) {
                    return true;
                }
                return false;
            } else {
                if ($has_access = $DB->get_record('local_organization_advisor', ['user_id' => $userid, 'instance_id' => $instance_id, 'user_context' => $user_context])) {
                    return true;
                }
                return false;
            }
        }
        return false;
    }
}
