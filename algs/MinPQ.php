<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * 基于二叉堆的优先队列: 较小数优先
 */
class MinPQ extends PQ
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
     * 删除最小元素: 顶端删除后, 将最后一个元素放到顶端, 然后下沉到合适位置
     */
    public function delMin()
    {
        $min = $this->pq[1];
        $this->exch(1, $this->N--);
        unset($this->pq[$this->N+1]);
        $this->sink(1);
        return $min;
    }

    /**
     * 与 MaxPQ 中的 less 刚好相反 (较小数优先)
     */
    protected function less(int $i, int $j): bool
    {
        if ($this->pq[$i] instanceof Comparable) {
            return $this->pq[$i]->compareTo($this->pq[$j]) > 0;
        } else {
            return $this->pq[$i] > $this->pq[$j];
        }
    }

    public static function main(array $args): void
    {
        $pq = new MinPQ('string', (int) $args[0]);
        while (! StdIn::isEmpty()) {
            $item = StdIn::readString();
            if ($item != "-") {
                $pq->insert($item);
            } elseif (!$pq->isEmpty()) {
                StdOut::print("{$pq->delMin()} ");
            }
        }
        StdOut::println("( {$pq->size()} left on pq )");
    }
}