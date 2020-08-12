<?php
namespace Algs;

/**
 * p.160, p.161
 */
class SortCompare
{
    public static function time(string $alg, array $a)
    {
        $timer = new StopWatch();
        if ($alg == "Insertion")  InsertionSort::sort($a); // 插入
        if ($alg == "Selection")  SelectionSort::sort($a); // 选择
        if ($alg == "Shell")  ShellSort::sort($a);         // 希尔
        if ($alg == "Merge")  MergeSort::sort($a);         // 归并
        if ($alg == "Quick")  QuickSort::sort($a);         // 快排
        if ($alg == "Heap")  HeapSort::sort($a);           // 堆
        return $timer->elapsedTime();
    }

    /**
     * 使用算法 $alg 将 T 个长度为 N 的数组排序, 并计算耗时
     */
    public static function timeRandomInput($alg, int $N, int $T)
    {
        $total = 0.0;
        $a = [];
        for ($t = 0; $t < $T; $t++) {
            for ($i = 0; $i < $N; $i++) {
                $a[$i] = StdRandom::uniform();
            }
            $total += self::time($alg, $a);
        }
        return $total;
    }

    /**
     * % php SortCompare.php Insertion Selection 1000 100
     * For 1000 random Doubles
     *      Insertion is 1.1 times faster than Selection
     */
    public static function main(array $args): void
    {
        $alg1 = $args[0]; // alg1
        $alg2 = $args[1]; // alg2
        $N = (int) $args[2]; // array size
        $T = (int) $args[3]; // how many times
        $t1 = self::timeRandomInput($alg1, $N, $T);
        $t2 = self::timeRandomInput($alg2, $N, $T);
        StdOut::printf("For %d random Doubles\n  %s is", $N, $alg1);
        StdOut::printf(" %.1f times faster than %s\n", $t2/$t1, $alg2);
    }
}