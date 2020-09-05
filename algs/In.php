<?php
namespace Algs;

/**
 * p.25, t.1.1.17
 * p.51, t.1.2.8
 */
final class In
{
    public function __construct($name)
    {
        if (! $name) throw new \InvalidArgumentException("invalid file name");
        try {
            if (false === ($fp = fopen($name, 'rb'))) {
                throw new \InvalidArgumentException("can not open file $name");
            }
            $this->fp = $fp;
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Could not open file $name");
        }
    }

    public function readInt()
    {
        if (($c = $this->readString()) === null) {
            return null;
        }
        return (int) $c;
    }

    public static function readInts($filename)
    {
        return (new In($filename))->readAllInts();
    }

    public function readDouble()
    {
        if (($c = $this->readString()) === null) {
            return null;
        }
        return (double) $c;
    }

    public function readAllInts()
    {
        $fields = $this->readAllStrings();
        $ints = [];
        $i = 0;
        foreach ($fields as $val) {
            if ($val == '') {
                continue;
            }
            $ints[$i++] = (int) $val;
        }
        return $ints;
    }

    private function nextNonSpaceChar()
    {
        $c = fgetc($this->fp);
        if ($c === false) {
            return null;
        }

        // skip pre whitespace
        while ($c == " " || $c == "\t") {
            $c = fgetc($this->fp);
        }
        // dump("prev got $c;");
        return $c;
    }

    public function readString()
    {
        if (($c = $this->nextNonSpaceChar()) === null) {
            return null;
        }

        $s = $c;
        $c = fgetc($this->fp);
        while ($c !== false && !ctype_space($c)) {
            // var_dump("tail got:");
            // var_dump($c.";");
            // var_dump("strcmp space:" . strcmp(' ', $c));
            // var_dump("strcmp tab:" . strcmp("\t", $c));
            // var_dump("equal empty string: " . ($c == ' '));
            // var_dump("equal line feed: " . ($c == PHP_EOL));
            $s .= $c;
            $c = fgetc($this->fp);
        }

        // dump("read string: $s}");
        return $s;
    }

    public static function readStrings($filename)
    {
        return (new In($filename))->readAllStrings();
    }

    public function readAllStrings()
    {
        return mb_split("\s+", $this->readAll());
    }

    public function readAll()
    {
        if (false !== $r = stream_get_contents($this->fp)) {
            return $r;
        }
        return null;
    }

    public function isEmpty()
    {
        return feof($this->fp);
    }

    public function close()
    {
        return fclose($this->fp);
    }

    public function hasNextLine()
    {
        $before = ftell($this->fp);
        $has = fgets($this->fp) !== false;
        fseek($this->fp, $before);
        return $has;
    }

    public function readLine()
    {
        return rtrim(fgets($this->fp));
    }

    /**
     * % php In.php ../resource/InTest.txt
     */
    public static function main($args)
    {
        StdOut::println("readStrings() from ${args[0]}");
        foreach (In::readStrings($args[0]) as $i) {
            StdOut::println($i);
        }

        StdOut::println("ReadString() from ${args[0]}");
        $in = new self($args[0]);
        while (! $in->isEmpty()) {
            StdOut::println($in->readString());
        }

        StdOut::println("ReadInts() from ${args[0]}");
        foreach (In::readInts($args[0]) as $i) {
            StdOut::println($i);
        }

        StdOut::println("ReadLine() from ${args[0]}");
        $in = new self($args[0]);
        while ($in->hasNextLine()) {
            StdOut::print($in->readLine());
        }
    }
}
