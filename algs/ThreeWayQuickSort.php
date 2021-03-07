<?php
namespace Algs;

/**
 * p.189
 *
 * 三向切分的快速排序: 适用于有大量重复元素的数组
 *
 * 不同于二分法将数组分为小于和大于切分元素两部分, 三向切分将数组切分为三部分, 分别对应于小于, 等于
 * 和大于切分元素的数组元素. 它比标准的二分法多使用了很多次交换, J.Bently 和 D.McIlroy 改进了这个问题
 *
 * 它从左到右遍历数组一次, 维护三个指针 lt, i, gt, 使得:
 * - a[lo..lt-1] 的元素都小于 v
 * - a[gt+1..hi] 的元素都大于 v
 * - a[lt..i-1] 的元素都等于 v
 * - a[i..gt] 的元素则还未确定
 *
 * 命题
 * - 不存在任何基于比较的排序算法能够保证在 NH-N 次比较之内将 N 个元素排序. 其中 H 为由主键值出现频率定义的香农信息量
 * - 对于大小为 N 的数组, 三项切分的快速排序需要 ~(2ln2)NH 次比较. 其中 H 为由主键值出现频率定义的香农信息量
 *   证明: 略
 */
class ThreeWayQuickSort extends QuickSort
{
    private static function doSort(array &$a, int $lo, int $hi): void
    {
        if ($hi <= $lo) return;
        $lt = $lo; $i = $lo + 1; $gt = $hi;
        $v = $a[$lo];
        while ($i <= $gt) {
            // 这里因为要区分等于和大于, 所以没有使用 less()
            // @todo Sort.php should have greater() & equals()
            if      ($a[$i] < $v) self::exch($a, $lt++, $i++);
            else if ($a[$i] > $v) self::exch($a, $i, $gt--);
            else                  $i++;
        } // 现在 a[lo..lt-1] < v = a[lt..gt] < a[gt+1..hi] 成立
        self::doSort($a, $lo, $lt-1);
        self::doSort($a, $gt + 1, $hi);
    }
}