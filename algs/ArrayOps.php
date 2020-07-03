<?php
namespace Algs;

/**
 * p.11, t.1.1.4
 *
 * 典型数组处理代码
 */

final class ArrayOps
{
    /**
     * 最大值
     */
    public static function max(array $a)
    {
        $max = $a[0];
        foreach($a as $v) {
            if ($v > $max) $max = $v;
        }
        return $max;
    }


    /**
     * 平均数
     */
    public static function avg(array $a)
    {
        $sum = 0;
        foreach ($a as $v) {
            $sum += $v;
        }
        return $sum / count($a);
    }

    /**
     * 复制
     */
    public static function copy(array $a)
    {
        $b = [];
        foreach ($a as $v) {
            $b[] = $v;
        }
        return $b; // PHP 数组参数不是按引用传递, 故需 return
    }

    /**
     * 逆序
     */
    public static function reverse(array &$a)
    {
        $len = count($a);
        for ($i = 0; $i < $len / 2; $i++) {
            $temp = $a[$i];
            $a[$i] = $a[$len-1-$i];
            $a[$len-1-$i] = $temp;
        }
    }

    /**
     * 方阵乘积
     */
    public static function matrix_product(array $a, array $b)
    {
        dump($a, $b);

        $len = count($a);
        $c = [];
        for ($i = 0; $i < $len; $i++) {
            for ($j = 0; $j < $len; $j++) {
                for ($k = 0; $k < $len; $k++) {
                    if (! isset($c[$i][$j])) {
                        $c[$i][$j] = $a[$i][$k] * $b[$k][$j];
                    }
                    $c[$i][$j] += $a[$i][$k] * $b[$k][$j];
                }
            }
        }
        return $c;
    }

    public static function main(array $args)
    {
        $a = [1, 7, 6, 5];
        dump("array a:", $a);

        dump(
            "max:" . self::max($a),
            "copy:", self::copy($a),
            "reverse:", self::reverse($a)
        );

        $b = [[2, 3], [5, 1]];
        $c = [[3, 3], [8, 2]];
        dump("array b:", $b,
            "array c:", $c,
            "matrix product:", self::matrix_product($b, $c)
        );
    }
}
