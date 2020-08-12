<?php
namespace Algs;

/**
 * p.170
 *
 * **归并**即将两个有序的数组归并成一个更大的有序数组
 *
 * 归并排序最吸引人的性质是它能够保证任意长度为 N 的数组排序所需时间和 NlogN 成正比,
 * 它的主要缺点则是它所需的额外空间和 N 成正比
 *
 * 命题
 *   - 归并排序是一种渐进最优的基于比较排序的算法
 */
abstract class MergeSort extends Sort
{
    private static $aux = [];

    /**
     * 原地归并: 将 $a[$lo...min] 和 $a[$mid+1...$hi] 归并
     */
    public static function merge(array &$a, int $lo, int $mid, int $hi)
    {
        $i = $lo;
        $j = $mid + 1;

        // 将 $a[$lo...$hi] 复制到辅助数组
        for ($k = 0; $k <= $hi; $k++) {
            self::$aux[$k] = $a[$k];
        }

        for ($k = $lo; $k <= $hi; $k++) {
            if ($i > $mid) {
                $a[$k] = self::$aux[$j++]; // 左半用尽, 直接复制右半下一个元素
            } elseif ($j > $hi) {
                $a[$k] = self::$aux[$i++]; // 右半用尽, 直接复制左半下一个元素
            } elseif (self::less(self::$aux[$j], self::$aux[$i])) {
                $a[$k] = self::$aux[$j++]; // 右半小, 取右半
            } else {
                $a[$k] = self::$aux[$i++]; // 左半小, 取左半
            }
        }
    }
}
