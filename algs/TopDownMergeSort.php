<?php
namespace Algs;

/**
 * p.171
 *
 * 自顶向下的归并排序
 * 基于原地归并的抽象实现了另一种递归归并, 这也是应用高效算法设计中**分治思想**的最典型例子之一
 *
 * 命题
 *   - 需要 1/2NlogN 至 NlgN 次比较
 *     可结合下面的树状图理解:
 *                                    a[0..15]
 *
 *                      a[0..7]                         a[8..15]
 *
 *             a[0..3]       a[4..7]            a[8..11]          a[12..15]
 *
 *      a[0..1]  a[2..3]  a[4..5]  a[6..7]  a[8..9]  a[10..1]  a[12..13] a[14..15]
 *
 *      每个树中的结点都表示一个 sort() 方法通过 merge() 方法归并而成的子数组. 这个数正好有 n=lgN 层.
 *      对于 0 到 n-1 之间的任意 k, 自顶向下的第 k 层有 2^k 个子数组, 每个数组的长度为 2^(n-k), 归并
 *      最多需要 2^(n-k) 次比较. 因此每层的比较次数为 2^k * 2^(n-k) = 2^n, n 层总共为 n2^n=NlgN
 *
 *   - 最多需要 6NlgN 次数组访问
 *     证明: 每次归并需要访问数组 6N 次 (2N 用来复制, 2N 用来将排序号的元素移动回去, 另外最多需要比较 2N 次 @?)
 *     需要 lgN 次归并, 故得
 *
 * 改进思路 @todo
 * - 使用插入排序处理小规模的子数组
 * - 如果 a[mid] 小于等于 a[mid+1], 就认为数组已有序并跳过 merge()
 * - 在递归调用的每个层次交换输入数组和辅助数组的角色
 */
class TopDownMergeSort extends MergeSort
{
    public static function sort(array &$a): void
    {
        self::doSort($a, 0, count($a)-1);
    }

    private static function doSort(array &$a, int $lo, int $hi)
    {
        if ($hi <= $lo) {
            return;
        }
        $mid = (int) ($lo + ($hi - $lo) / 2);
        self::doSort($a, $lo, $mid); // 将左半边排序
        self::doSort($a, $mid+1, $hi); // 将右半边排序
        self::merge($a, $lo, $mid, $hi); // 归并结果
    }
}