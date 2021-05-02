<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.196
 */
class TopM
{
    /**
     * 打印输入流中最大的 M 行
     *
     * % php TopM.php 5 < ../data/tinyBatch.txt
     * Thompson    2/27/2000  4747.08
     * vonNeumann  2/12/1994  4732.35
     * vonNeumann  1/11/1999  4409.74
     * Hoare       8/18/1992  4381.21
     * vonNeumann  3/26/2002  4121.85
    */
    public static function main(array $args): void
    {
        $M = (int) $args[0];
        $pq = new MinPQ(Comparable::class, $M+1);
        while (StdIn::hasNextLine()) {
            // 为下一行输入创建一个元素并放入优先队列中
            if (is_null($line = StdIn::readLine())) {
                break;
            }

            // 如果优先队列中存在 M+1 个元素则删除其中最小的元素
            $pq->insert(new Transaction($line));
            if ($pq->size() > $M) {
                $pq->delMin();
            }
        } // 最大的 M 个元素都在优先队列中

        // 使用栈以颠倒它们的顺序
        $stack = new Stack();
        while (! $pq->isEmpty()) $stack->push($pq->delMin());
        foreach ($stack as $t) StdOut::println($t);
    }
}