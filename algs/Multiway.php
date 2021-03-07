<?php
namespace Algs;

/**
 * p.205
 *
 * 使用优先队列的多向归并
 *
 * 多向归并问题: 将多个_有序_的输入流归并成一个有序的输出流
 * 如果有足够多的空间, 你可以简单的把它们全部读入数组并排序
 * 但如果像这个示例一样, 使用了优先队列, 无论输入有多长你都可以可以把它们全部读入并排序
 * 
 * 这段代码调用了 IndexMinPQ 来将作为命令行参数输入的多行有序字符串归并为一行有序的输出
 */
class Multiway
{
    public static function merge(Arr $streams): void
    {
        $N = count($streams);
        $pq = new IndexMinPQ('string', $N);

        // 假设三个输入流:
        // 0: A B C F G I I Z
        // 1: B D H P Q Q
        // 2: A B E F J N

        // 先把每个输入流中的第一个元素放入优先队列
        // 初始堆中有 A A B 三个元素 (分别来自三个输入流第一个元素)
        for ($i = 0; $i < $N; $i++) {
            if (! $streams[$i]->isEmpty()) {
                $pq->insert($i, $streams[$i]->readString());
            }
        }

        while (! $pq->isEmpty()) {
            StdOut::println($pq->min());
            // 删除最小的元素
            $i = $pq->delMin();
            // 从被删除元素对应的索引 i 输入流中继续取下一个元素放入优先队列
            if (! $streams[$i]->isEmpty()) {
                $pq->insert($i, $streams[$i]->readString());
            }
        }
    }

    /**
     * % more ../resource/m1.txt
     * A B C F G I I Z
     * % more ../resource/m2.txt
     * B D H P Q Q
     * % more ../resource/m3.txt
     * A B E F J N
     *
     * % php Multiway.php ../resource/m1.txt ../resource/m2.txt ../resource/m3.txt
     * A A B B B C D E F F G H I I J N P Q Q Z
     */
    public static function main(array $args): void
    {
        $N = count($args);
        $streams = new Arr(In::class, $N);
        for ($i = 0; $i < $N; $i++) {
            $streams[$i] = new In($args[$i]);
        }
        self::merge($streams);
    }
}