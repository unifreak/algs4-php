<?php
namespace Algs;

/**
 * p.119
 *
 * 改进 @see TwoSum.php 性能
 */
class TwoSumFast
{
    /**
     * 计算和为 0 的整数对
     */
    public static function count(array $a): int
    {
        $N = count($a);
        sort($a);
        $cnt = 0;
        for ($i = 0; $i < $N; $i++) {
            // 注意是 > $i, 而非 > 0. 因为如果在小于等于 $i 的地方找到, 代表重复计数
            if (BinarySearch::rank(-$a[$i], $a) > $i) {
                $cnt++;
            }
        }
        return $cnt;
    }

    /**
     * % php TwoSumFast.php ../resource/4Kints.txt
     * 3
     */
    public static function main(array $args): void
    {
        $a = In::readInts($args[0]);
        StdOut::println(self::count($a));
    }
}