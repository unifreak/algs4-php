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
 *   - 需要 1/2NlgN 至 NlgN 次比较, 最多 6NlgN 次数组访问
 */
class BottomUpMergeSort extends MergeSort
{
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