<?php
namespace Algs;

/**
 * p.25, t.1.1.17
 * p.51, t.1.2.9
 */

final class Out
{
    public function __construct($filename)
    {
        try {
            if (false === ($fp = fopen($name, 'w+b'))) {
                throw new \InvalidArgumentException("can not open file $name to write");
            }
            $this->fp = $fp;
        } catch (\Exception $e) {
            throw new InvalidArgumentException("Could not open file $name to write");
        }
    }

    public function __destruct()
    {
        fclose($this->fp);
    }

    public function println($x)
    {
        fwrite($this->fp, $x);
    }

    public function close()
    {
        fclose($this->fp);
    }
}