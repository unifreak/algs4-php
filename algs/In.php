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

    public static function readInts($filename)
    {
        return (new In($filename))->readAllInts();
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

    public function close()
    {
        return fclose($this->fp);
    }

    /**
     * % php In.php ../resource/InTest.txt
     */
    public static function main($args)
    {
        StdOut::println("ReadInts() from ${args[0]}");
        foreach (In::readStrings($args[0]) as $i) {
            StdOut::println($i);
        }
    }
}
