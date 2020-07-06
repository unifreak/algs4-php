<?php
namespace Algs;

/**
 * p.121
 */

/**
 * @see DoublingTest 的改进版本
 *
 * 通过进行倍率试验, 可预测程序运行时间
 */
class DoublingRatio
{
    /**
     * 这里的实现, 可换成想要测试的其他算法
     * 2 倍率也可换成其他相应的倍率
     */
    public static function timeTrail(int $N): float
    {
        $MAX = 1000000;
        $a = [];
        for ($i = 0; $i < $N; $i++) {
            $a[$i] = StdRandom::uniform(-$MAX, $MAX);
        }
        $timer = new Stopwatch();
        // $cnt = ThreeSum::count($a); // 用于测试 ThreeSum
        $cnt = ThreeSumFast::count($a); // 用于测试 ThreeSumFast
        return $timer->elapsedTime();
    }

    /**
     * 测试 ThreeSum 结果
     * -------------------
     * % php DoublingRatio.php
     *   250     0.1   8.2
     *   500     0.9   7.9
     *  1000     7.2   8.0
     *  2000    59.2   8.2
     *  ...
     *
     * 测试 ThreeSumFast 结果
     * --------------------
     * % php DoublingRatio.php
     *   250     0.0   4.2
     *   500     0.1   4.4
     *  1000     0.5   4.4
     *  2000     2.1   4.4
     *  4000     9.2   4.3
     *  ...
     */
    public static function main(array $args): void
    {
        $prev = self::timeTrail(125);
        for ($N = 250; true; $N += $N) {
            $time = self::timeTrail($N);
            StdOut::printf("%6d %7.1f %5.1f\n", $N, $time, $time/$prev);
            $prev = $time;
        }
    }
}