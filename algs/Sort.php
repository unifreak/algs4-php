<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.153
 *
 * 排序算法可分为两类
 * 1. 原地排序算法: 除了函数调用所需的栈和固定数目的实例变量之外无需额外内存
 * 2. 其他排序算法: 需要额外内存空间来存储另一份数组副本
 *
 * 对于随机排序的无重复主键的数组, 插入排序和选择排序的运行时间是平方级别的, 两者之比应该是一个较小的常数.
 * 插入排序大约比选择排序快一倍, 可以使用 @see SortCompare 验证这一点
 *
 * 成本模型
 *   比较和交换的数量. 对于不交换元素的算法, 则计算访问数组的次数
 */
abstract class Sort
{
    /**
     * 将 $a 按升序排列
     */
    abstract public static function sort(array &$a): void;

    protected static function less($v, $w): bool
    {
        // @todo if we are in PHP8, we could use union type and avoid type test
        if ($v instanceof Comparable && $w instanceof Comparable) {
            return $v.compareTo($w) < 0;
        }
        if ((is_string($v) && is_string($w)) || (is_numeric($v) && is_numeric($w))) {
            return $v < $w;
        }
        throw new \InvalidArgumentException("arguments must be Comparable or scalar");
    }

    protected static function exch(array &$a, int $i, int $j): void
    {
        $t = $a[$i];
        $a[$i] = $a[$j];
        $a[$j] = $t;
    }

    protected static function show(array $a): void
    {
        for ($i = 0; $i < count($a); $i++) {
            StdOut::print("$a[$i] ");
        }
        StdOut::println();
    }

    public static function isSorted(array $a): bool
    {
        for ($i = 1; $i < count($a); $i++) {
            if (static::less($a[$i], $a[$i-1])) {
                return false;
            }
        }
        return true;
    }

    /**
     * 从标准输入读取字符串, 将他们排序并输出
     *
     * % php SelectionSort.php ../resource/words3.txt
     *
     * % php InsertionSort.php ../resource/words3.txt
     *
     * % php ShellSort.php ../resource/words3.txt
     *
     * % php TopDownMergeSort.php ../resource/words3.txt
     *
     * % php BottomUpMergeSort.php ../resource/words3.txt
     *
     * % php QuickSort.php ../resource/words3.txt
     *
     * % php ThreeWayQuickSort.php ../resource/words3.txt
     *
     * % php HeapSort.php ../resource/words3.txt
     *
     * all bad bed bug dad dim dug egg ... sky sob tag tap tar tip wad was wee yes yet zoo
     */
    public static function main(array $args): void
    {
        $a = In::readStrings($args[0]);
        dump($a);
        static::sort($a);
        assert(static::isSorted($a));
        static::show($a);
    }
}