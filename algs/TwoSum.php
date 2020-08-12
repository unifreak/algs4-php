<?php
namespace Algs;

/**
 * p.117, t.1.4.7
 *
 * 为了优化 @link ThreeSum, 先来看看其简化版本 TwoSum
 */
class TwoSum
{
    /**
     * 计算和为 0 的整数对
     */
    public static function count(array $a): int
    {
        $cnt = 0;
        $N = count($a);
        for ($i = 0; $i < $N; $i++) {
            for ($j = $i + 1; $j < $N; $j++) {
                if ($a[$i] + $a[$j] == 0) {
                    $cnt++;
                }
            }
        }
        return $cnt;
    }

    /**
     * % php TwoSum.php ../resource/4Kints.txt
     * 3
     */
    public static function main(array $args): void
    {
        $a = In::readInts($args[0]);
        StdOut::println(self::count($a));
    }
}