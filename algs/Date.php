<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.56, t1.2.12
 */
class Date
{
    private static $DAYS = [ 0, 31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ];
    private $month;
    private $day;
    private $year;

    public function __construct(string $date)
    {

        $fields = explode("/", $date);
        if (count($fields) != 3) {
            throw new \InvalidArgumentException("Invalid date");
        }
        $this->month = (int) $fields[0];
        $this->day = (int) $fields[1];
        $this->year  = (int) $fields[2];
        if (! self::isValid($this->month, $this->day, $this->year)) {
            throw new \InvalidArgumentException("Invalid date");
        }
    }

    public function day(): int
    {
        return $this->day;
    }

    public function month(): int
    {
        return $this->month;
    }

    public function year(): int
    {
        return $this->year;
    }

    public function __toString()
    {
        return "$this->month/$this->day/$this->year";
    }

    private static function isValid(int $m, int $d, int $y): bool
    {
        if ($m < 1 || $m > 12) return false;
        if ($d < 1 || $d > self::$DAYS[$m]) return false;
        if ($m == 2 && $d == 29 && ! self::isLeapYear($y)) return false;
        return true;
    }

    private static function isLeapYear(int $y): bool
    {
        if ($y % 400 == 0) return true;
        if ($y % 100 == 0) return false;
        return $y % 4 == 0;
    }

    public static function main(array $args): void
    {
        $today = new Date("2/25/2004");
        StdOut::println($today);
        StdOut::println($today->month());
        StdOut::println($today->day());
        StdOut::println($today->year());
    }
}