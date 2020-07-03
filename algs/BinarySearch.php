<?php
namespace Algs;

/**
 * p.28
 */

class BinarySearch
{
    public static function rank($key, array $a)
    {
        // 数组必须是有序的
        $lo = 0;
        $hi = count($a) - 1;
        while ($lo <= $hi) {
            $mid = (int) ($lo + ($hi - $lo) / 2); // 注意保持索引为整形
            if ($key < $a[$mid]) $hi = (int) ($mid - 1);
            elseif ($key > $a[$mid]) $lo = (int) $mid + 1;
            else return $mid;
        }
        return -1;
    }

    // % php BinarySearch ../resource/tinyW.txt < ../resource/tinyT.txt
    public static function main($args)
    {
        $whiteList = In::readInts($args[0]);
        sort($whiteList);
        while (! StdIn::isEmpty()) {
            $key = StdIn::readInt();
            if (self::rank($key, $whiteList) < 0) {
                StdOut::println($key);
            }
        }
    }
}
