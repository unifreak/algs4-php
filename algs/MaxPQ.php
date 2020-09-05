<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.200, p.201, p.202
 *
 * 基于二叉堆的优先队列: 较大数优先
 */
class MaxPQ extends PQ
{
    /**
     * 插入元素: 新元素加入到末尾, 上浮到合适位置
     */
    public function insert($v): void
    {
        $this->pq[++$this->N] = $v;
        $this->swim($this->N);
    }

    /**
     * 删除最大元素: 顶端删除后, 将最后一个元素放到顶端, 然后下沉到合适位置
     */
    public function delMax()
    {
        $max = $this->pq[1];            // 从根结点得到最大元素
        $this->exch(1, $this->N--);     // 将其和最后一个结点交换
        unset($this->pq[$this->N+1]);   // 防止对象游离
        $this->sink(1);                 // 恢复堆的有序性
        return $max;
    }

    protected function less(int $i, int $j): bool
    {
        if ($this->pq[$i] instanceof Comparable) {
            return $this->pq[$i]->compareTo($this->pq[$j]) < 0;
        } else {
            return $this->pq[$i] < $this->pq[$j];
        }
    }

    public static function main(array $args): void
    {
        $pq = new MaxPQ('string', (int) $args[0]);
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if ($item != "-") $pq->insert($item);
            else if (!$pq->isEmpty()) StdOut::print("{$pq->delMax()} ");
        }
        StdOut::println("( {$pq->size()} left on pq )");
    }
}