<?php
namespace Algs;

/**
 * p.111
 */
class DoublingTest
{
    public static function timeTrail($N): float
    {
        $MAX = 1000000;
        $a = [];
        for ($i = 0; $i < $N; $i++) {
            $a[$i] = StdRandom::uniform(-$MAX, $MAX);
        }
        $timer = new Stopwatch();
        $cnt = ThreeSum::count($a);
        return $timer->elapsedTime();
    }

    /**
     * % php DoublingTest.php
     *    250   0.1
     *    500   1.0
     *   1000   7.3
     *   2000  60.0
     *   4000 469.4
     *   ...
     *
     * 预测的运行时间的公式为 T(N) = aN^3, 用此用例检验预测是否准确.
     * 如果准确, 则每次加倍问题规模, 用时应该大约等于上次的 8 倍
     */
    public static function main(array $args): void
    {
        for ($N = 250; true; $N += $N) { // Doubling
            $time = self::timeTrail($N);
            StdOut::printf("%7d %5.1f\n", $N, $time);
        }
    }
}