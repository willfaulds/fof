<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers;

abstract class TravisLogger
{
    private static $fp;

    public static function reset()
    {
        self::close();

        if(file_exists(JPATH_TESTS.'/debug.txt'))
        {
            unlink(JPATH_TESTS.'/debug.txt');
        }
    }

    public static function log($level, $message = '')
    {
        if(!self::isEnabled())
        {
            return;
        }

        if(!self::$fp)
        {
            self::open();
        }

        // Replace new lines
        $message = str_replace("\r\n", "\n", $message);
        $message = str_replace("\r", "\n", $message);
        $message = str_replace("\n", ' \n ', $message);

        switch ($level)
        {
            case 1:
                $string = "ERROR   |";
                break;

            case 2:
                $string = "WARNING |";
                break;

            case 3:
                $string = "INFO    |";
                break;

            default:
                $string = "DEBUG   |";
                break;
        }

        $string .= @strftime("%y%m%d %H:%M:%S") . "|$message\r\n";

        @fwrite(self::$fp, $string);
    }

    protected static function open()
    {
        @touch(JPATH_TESTS.'/debug.txt');

        self::$fp = fopen(JPATH_TESTS.'/debug.txt', 'ab');
    }

    protected static function close()
    {
        if(is_resource(self::$fp))
        {
            fclose(self::$fp);
        }

        self::$fp = null;
    }

    protected static function isEnabled()
    {
        return getenv('TRAVIS');
    }
}

