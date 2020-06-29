<?php
namespace Algs;

/**
 * p.24, t.1.1.16
 */

final class StdIn
{
    private function __construct() { }

    /**
     * @see <https://www.php.net/manual/en/function.feof.php> first comment
     */
    public static function isEmpty()
    {
        $before = ftell(STDIN);
        $empty = fgets(STDIN) === false;
        fseek(STDIN, $before);
        return $empty;
    }

    public static function hasNextLine()
    {
        $before = ftell(STDIN);
        $has = fgets(STDIN) !== false;
        fseek(STDIN, $before);
        return $has;
    }

    public static function readLine()
    {
        return fgets(STDIN);
    }

    public static function readChar()
    {
        if (false !== $c = fgetc(STDIN)) {
            return $c;
        }
        return null;
    }

    public static function readAll()
    {
        if (false !== $r = stream_get_contents(STDIN)) {
            return $r;
        }
        return null;
    }

    public static function readString()
    {
        list($r) = fscanf(STDIN, "%s");
        if (! is_string($r)) {
            throw new \UnexpectedValueException(
                "attemps to read a 'string' value from standard input,
                but next token is " . $r
            );
        }
        return $r;
    }

    public static function readInt()
    {
        list($r) = fscanf(STDIN, "%d");
        if (! is_int($r)) {
            throw new \UnexpectedValueException(
                "attemps to read an 'int' value from standard input,
                but the next token is " . self::readString()
            );
        }
        return $r;
    }

    public static function readDouble()
    {
        list($r) = fscanf(STDIN, "%f");
        if (! is_double($r)) {
            throw new \UnexpectedValueException(
                "attemps to read an 'float' value from standard input,
                but the next token is " . self::readString()
            );
        }
        return $r;
    }

    public static function readBoolean()
    {
        $token = self::readString();
        if (strcasecmp($token, 'true') == 0)  return true;
        if (strcasecmp($token, 'false') == 0) return false;
        if ($token == "1")                    return true;
        if ($token == "0")                    return false;
        throw new \UnexpectedValueException(
            "attempts to read a 'boolean' value from standard input,
            but the next token is " . $token
        );
    }

    public static function main()
    {
        StdOut::print("Type a string: ");
        $s = StdIn::readString();
        StdOut::println("Your string was: " . $s);
        StdOut::println();

        StdOut::print("Type an int: ");
        $a = StdIn::readInt();
        StdOut::println("Your int was: " . $a);
        StdOut::println();

        StdOut::print("Type a boolean: ");
        $b = StdIn::readBoolean();
        StdOut::println("Your boolean was: " . $b);
        StdOut::println();

        StdOut::print("Type a double: ");
        $c = StdIn::readDouble();
        StdOut::println("Your double was: " . $c);
        StdOut::println();
    }
}
