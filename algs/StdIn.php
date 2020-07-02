<?php
namespace Algs;

/**
 * p.24, t.1.1.16
 *
 * @todo  There should be a generic stream scanner like Java
 * NOTE: Does NOT support multibyte
 */

final class StdIn
{
    private static $lines = []; // [line, ...]
    private static $tokens = []; // [line=>[token, token], ...]
    private static $chars = []; // [line=>[token=>[char, ...]...]...]
    private static $totalChar = 0;

    private static $linePos = 0;
    private static $tokenPos = 0;
    private static $charPos = 0;

    private function __construct() { }

    public static function __constructStatic()
    {
        if (false ===
            self::$lines = file('php://stdin', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
        ) {
            throw new \RuntimeException('Can not open stdin for read');
        }

        self::parseChar();
        // dump("pased lines:");
        // dump(self::$lines);
        // dump("pased chars:");
        // dump(self::$chars);
    }

    private static function parseChar()
    {
        foreach (self::$lines as $line) {
            $chars = preg_split('/(?<!^)(?!$)/uS', $line);
            self::$chars[] = $chars;
        }
    }

    /**
     * @see <https://www.php.net/manual/en/function.feof.php> first comment
     */
    public static function isEmpty()
    {
        return self::$linePos >= count(self::$lines);
    }

    public static function hasNextLine()
    {
        return isset(self::$lines[self::$linePos+1]);
    }

    public static function readLine()
    {
        $line = self::nextChar();
        while (! self::isEmpty() && self::$charPos != 0) {
            $line .= self::nextChar();
        }
        return $line;
    }

    public static function readChar()
    {
        $char = self::nextChar();
        while (($char == ' ' || $char == "\t") && ! self::isEmpty()) {
            $char = self::nextChar();
        }
        return $char;
    }

    private static function nextChar()
    {
        // dump("reading char at " . self::$linePos .':' . self::$charPos);
        if (self::isEmpty()) {
            return null;
        }
        $char = self::$chars[self::$linePos][self::$charPos];
        self::forward();
        return $char;
    }

    private static function forward()
    {
        self::$charPos++;
        // dump("char pos:" . self::$charPos);
        // dump("reset?" . count(self::$chars[self::$linePos]));
        if (self::$charPos >= count(self::$chars[self::$linePos])) {
            self::$linePos++;
            self::$charPos = 0;
        }
    }

    public static function readAll()
    {
        $remain = '';
        while (! self::isEmpty()) {
            $remain .= self::readLine();
        }
        return $remain;
    }

    public static function readString()
    {
        // dump("reading string");
        $s = self::readChar();
        $c = self::nextChar();
        while (self::$charPos != 0 && $c != ' ' && $c != "\t") {
            $c = self::nextChar();
            $s .= $c;
        }
        return $s;
    }

    private static function rollback()
    {
        if (self::$resetPos) {
            fseek(STDIN, self::$resetPos);
            self::$resetPos = null;
        }
    }

    public static function readInt()
    {
        return (int) self::readString();
    }

    public static function readDouble()
    {
        return (float) self::readString();
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

        // StdOut::print("Type a boolean: ");
        // $b = StdIn::readBoolean();
        // StdOut::println("Your boolean was: " . $b);
        // StdOut::println();

        // StdOut::print("Type a double: ");
        // $c = StdIn::readDouble();
        // StdOut::println("Your double was: " . $c);
        // StdOut::println();
    }
}

StdIn::__constructStatic();