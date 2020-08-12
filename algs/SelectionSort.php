<?php
namespace Algs;

/**
 * p.156
 *
 * 选择排序: 找元素 -- 不断选择剩余元素中的最小者
 * 交换总次数是 N, 所以算法效率取决于比较的次数
 *
 * 两个鲜明特点:
 * 1. 运行时间和输入无关. 将会看到, 其他算法更善于利用输入的初始状态
 * 2. 数据移动是最少的, 交换次数和数组大小是线性关系, 其他算法都不具备这个特征
 *
 * 命题
 *   - 选择排序需要大约 N^2/2 次比较和 N 次交换
 *     证明: 从代码可看出, 0 到 N-1 的任意 i 都会进行一次交换和 N-1-i 次比较, 因此总共有 N 次交换
 *     和 (N-1)+(N-2)+...+2+1=N(N-1)/2 ~ N^2/2 次比较
 */
class SelectionSort extends Sort
{
    public static function sort(array &$a): void
    {
        $N = count($a);
        for ($i = 0; $i < $N; $i++) {
            $min = $i;
            for ($j = $i + 1; $j < $N; $j++) {
                if (self::less($a[$j], $a[$min])) {
                    $min = $j;
                }
            }
            self::exch($a, $i, $min);
        }
    }
}