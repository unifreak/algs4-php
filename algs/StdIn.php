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
    private function __construct() { }

    /**
     * 因为 feof() 必须读下一个字符才能判定是否输入结束, 这个方法基本上没用. 会看到书中
     * isEmpty() 的检查一般用对 readChar() 或 readString() 的结果是否为空代替
     * @see <https://www.php.net/manual/en/function.feof.php> first comment
     *
     * 不知道有什么解决方法, 尝试过
     * - 先读取一个字符, 再用 fseek 指向原先地址
     */
    public static function isEmpty()
    {
        return feof(STDIN);
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
        return fgetc(STDIN);
    }

    public static function readAll()
    {
        if (false !== $r = stream_get_contents(STDIN)) {
            self::$empty = true;
            return $r;
        }
        return null;
    }

    public static function readString()
    {
        $c = fgetc(STDIN);
        if ($c === false) {
            return null;
        }

        // skip pre whitespace
        while ($c == " " || $c == "\t") {
            $c = fgetc(STDIN);
        }
        // dump("prev got $c;");

        $s = $c;
        $c = fgetc(STDIN);
        while ($c !== false && !ctype_space($c)) {
            // var_dump("tail got:");
            // var_dump($c.";");
            // var_dump("strcmp space:" . strcmp(' ', $c));
            // var_dump("strcmp tab:" . strcmp("\t", $c));
            // var_dump("equal empty string: " . ($c == ' '));
            // var_dump("equal line feed: " . ($c == PHP_EOL));
            $s .= $c;
            $c = fgetc(STDIN);
        }

        // dump("read string: $s}");
        return $s;
    }

    public static function readInt()
    {
        list($r) = fscanf(STDIN, "%d");
        if (! is_numeric($r)) {
            throw new \UnexpectedValueException(
                "attemps to read an 'int' value from standard input,
                but the next token is $r"
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
        while (! StdIn::isEmpty()) {
            dump("{".StdIn::readString()."}");
        }
        // StdOut::print("Type a string: ");
        // $s = StdIn::readString();
        // StdOut::println("Your string was: " . $s);
        // StdOut::println();

        // StdOut::print("Type an int: ");
        // $a = StdIn::readInt();
        // StdOut::println("Your int was: " . $a);
        // StdOut::println();

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
