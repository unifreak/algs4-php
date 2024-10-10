<?php
namespace Algs;

/**
 * p.203, p.211, p.212
 *
 * 基于堆的索引优先队列: 较小数优先
 *
 * 索引优先队列允许用例通过_索引_引用进入优先队列中的元素. 
 * 理解这种数据结构的的一种方式, 是将它看成能够快速访问最小元素的数组. 
 * 实际上, 更进一步地, 可将它看成能够快速访问数组的一个特定子集中的最小元素的数据结构. 
 *
 * 客户代码可以使用索引, 指定要删除或更改的元素.
 * 
 * Multiway 用它解决了_多向归并_问题 (See Multiway.php)
 *
 * 命题 Q (p.204)
 * 
 *   插入元素, 改变优先级, 删除和删除最小元素操作所需的比较次数和 logN 成正比.
 *
 *   s证明: 已知堆中所有路径最长即为 ~lgN, 从代码中很容易得出结论.
 */
class IndexMinPQ extends PQ
{
    protected $N;       // PQ 中的元素数量
    protected $pq;      // 索引二叉堆, 从 1 开始. pq[N] = i (指定的索引)
    protected $qp;      // pq 的逆转: qp[i] = N (在二叉堆中的索引)
    protected $keys;    // keys[i] 即 i 元素的优先级
    protected $type;    // 类型

    public function __construct(string $type, int $maxN)
    {
        $this->type = $type;
        $this->keys = new Arr($this->type, $maxN+1);
        $this->pq = new Arr('int', $maxN+1);
        $this->qp = new Arr('int', $maxN+1);
        for ($i = 0; $i <= $maxN; $i++) {
            $this->qp[$i] = -1;
        }
    }

    /**
     * 同 MinPQ
     */
    protected function less(int $i, int $j): bool
    {
        if ($this->keys[$this->pq[$i]] instanceof Comparable) {
            return $this->keys[$this->pq[$i]]->compareTo($this->keys[$this->pq[$j]]) > 0;
        } else {
            return $this->keys[$this->pq[$i]] > $this->keys[$this->pq[$j]];
        }
    }

    /**
     * 是否存在索引为 i 的元素
     */
    public function contains(int $i): bool
    {
        return $this->qp[$i] != -1;
    }

    /**
     * 插入一个元素, 将它和索引 i 相关联
     */
    public function insert(int $i, $key): void
    {
        $this->N++;  // NOTE: 插入 (0, key) 时, 这句代码保证了从 1 开始存储
        $this->qp[$i] = $this->N; // 但是索引从 0 开始
        $this->pq[$this->N] = $i;
        $this->keys[$i] = $key;
        $this->swim($this->N);
    }

    /**
     * 返回最小元素
     */
    public function min()
    {
        return $this->keys[$this->pq[1]];
    }

    /**
     * 删除最小元素并返回它的索引
     */
    public function delMin(): int
    {
        $indexOfMin = $this->pq[1];
        $this->exch(1, $this->N--);
        $this->sink(1);
        unset($this->keys[$this->pq[$this->N+1]]);
        $this->qp[$this->pq[$this->N+1]] = -1;
        return $indexOfMin;
    }

    /**
     * 返回最小元素的索引
     */
    public function minIndex(): int
    {
        return $this->pq[1];
    }

    /**
     * 将索引为 i 的元素设为 item
     */
    public function change(int $i, $key): void
    {
        $this->keys[$i] = $key;
        $this->swim($this->qp[$i]);
        $this->sink($this->qp[$i]);
    }

    /**
     * 删去索引 i 及其相关联的元素
     */
    public function delete(int $i): void
    {
        $index = $this->qp[$i];
        $this->exch($index, $this->N--);
        $this->swim($index);
        $this->sink($index);
        unset($this->keys[$i]);
        $this->qp[$i] = -1;
    }

    protected function exch(int $i, int $j): void
    {
        $t = $this->pq[$i];
        $this->pq[$i] = $this->pq[$j];
        $this->pq[$j] = $t;
        $this->qp[$this->pq[$i]] = $i;
        $this->qp[$this->pq[$j]] = $j;
    }

    /**
     * % php IndexMinPQ.php
     * 3 best
     * 0 it
     * 6 it
     * 4 of
     * 8 the
     * 2 the
     * 5 times
     * 7 was
     * 1 was
     * 9 worst
     */
    public static function main(array $args): void
    {
        $strings = [ "it", "was", "the", "best", "of", "times", "it", "was", "the", "worst" ];

        $pq = new IndexMinPQ("string", count($strings));
        for ($i = 0; $i < count($strings); $i++) {
            $pq->insert($i, $strings[$i]);
        }

        while (! $pq->isEmpty()) {
            $i = $pq->delMin();
            StdOut::println("$i {$strings[$i]}");
        }
        StdOut::println();
    }
}
