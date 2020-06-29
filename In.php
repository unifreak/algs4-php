<?php
namespace Algs;

/**
 * p.25, t.1.1.17
 * p.52, t.1.2.8
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
            throw new InvalidArgumentException("Could not open file $name");
        }
    }

    public function __destruct()
    {
        fclose($this->fp);
    }

    public static function readInts($filename)
    {
        return (new In($filename))->readAllInts();
    }

    public function readAllInts()
    {
        $fields = $this->readAllStrings();
        foreach ($fields as $i => $val) {
            $fields[$i] = (int) $val;
        }
        return $fields;
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

    public static function main($args)
    {
        dump("ReadInts() from ${args[0]}");
        dump(In::readInts($args[0]));
    }
}
