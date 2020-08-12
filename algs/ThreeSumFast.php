<?php
namespace Algs;

/**
 * p.120
 *
 * 使用二分查找优化 ThreeSum 性能
 */
class ThreeSumFast
{
    /**
     * 计算和为 0 的三元组的数目
     */
    public static function count(array $a): int
    {
        sort($a);
        $N = count($a);
        $cnt = 0;
        for ($i = 0; $i < $N; $i++) {
            for ($j = $i + 1; $j < $N; $j++) {
                if (BinarySearch::rank(-$a[$i]-$a[$j], $a) > $j) {
                    $cnt++;
                }
            }
        }
        return $cnt;
    }

    /**
     * % php ThreeSum.php ../resource/1Kints.txt
     * 70
     * % php ThreeSum.php ../resource/2Kints.txt
     * 528
     */
    public static function main(array $args): void
    {
        $a = In::readInts($args[0]);
        StdOut::println(self::count($a));
    }
}