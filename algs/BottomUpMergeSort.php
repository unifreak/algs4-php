<?php
namespace Algs;

/**
 * p.175
 *
 * 自底向上的归并排序: 循序渐进
 * 先两两归并, 然后四四, 八八...
 * 比较适合用链表组织的数据, 这种方法只需要重新组织链表链接就能将链表原地排序
 *
 * 命题
 *   - H: 需要 1/2NlgN 至 NlgN 次比较, 最多 6NlgN 次数组访问
 */
class BottomUpMergeSort extends MergeSort
{
    /**
     * 假设排序 a[0..15], N = 16
     *
     *      sz  lo  N-sz    
     *      1   0   15      merge(a, 0, 0, 1)
     *          2           merge(a, 2, 2, 3)
     *          4           merge(a, 4, 4, 5)
     *          6           merge(a, 6, 6, 7)
     *          8           merge(a, 8, 8, 9)
     *          10          merge(a, 10, 10, 11)
     *          12          merge(a, 12, 12, 13)
     *          14          merge(a, 14, 14, 15)
     *      2   0    14     merge(a, 0, 1, 3)
     *          4           merge(a, 4, 5, 7)
     *          8           merge(a, 8, 9, 11)
     *          12          merge(a, 12, 13, 15)
     *      4   0    12     merge(a, 0, 3, 7)
     *          8           merge(a, 8, 11, 15)     
     *      8   0    8      merge(a, 0, 7, 15)
     */
    public static function sort(array &$a): void
    {
        $N = count($a);
        for ($sz = 1; $sz < $N; $sz = $sz + $sz) { // sz 是子数组大小
            for ($lo = 0; $lo < $N - $sz; $lo += $sz + $sz) { // lo 是子数组索引
                // N 不一定是 sz 的偶数倍, 故需两者取小
                self::merge($a, $lo, $lo+$sz-1, min($lo+$sz+$sz-1, $N-1));
            }
        }
    }
}