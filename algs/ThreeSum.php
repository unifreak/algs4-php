<?php
namespace Algs;

/**
 * p.109
 *
 * 性质
 *   运行时间的增长数量级为 N^3
 * 成本模型
 *   数组的访问次数
 * 命题
 *   使用了 ~N^3 / 2 次数组访问
 */
class ThreeSum
{
    /**
     * 统计和为 0 的三元组的数量
     */
    public static function count(array $a): int
    {
        $N = count($a);
        $cnt = 0;
        for ($i = 0; $i < $N; $i++) {
            for ($j = $i + 1; $j < $N; $j++) {
                for ($k = $j + 1; $k < $N; $k++) {
                    if ($a[$i] + $a[$j] + $a[$k] == 0) {
                        $cnt++;
                    }
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
