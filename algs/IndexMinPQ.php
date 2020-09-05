<?php
namespace Algs;

/**
 * p.203, p.211, p.212
 *
 * 基于堆的索引优先队列: 较小数优先
 *
 * 索引优先队列允许用例通过索引引用进入优先队列中的元素. 可将它看成能够快速访问数组的一个特定子
 * 集中的最小元素的数据结构. @see Multiway 用它解决了_多向归并_问题
 *
 * 命题 p.204
 * - Q: 插入元素, 改变优先级, 删除和删除最小元素操作所需的比较次数和 logN 成正比
 *   证明: 已知堆中所有路径最长即为 ~lgN, 从代码中很容易得出结论
 */
class IndexMinPQ extends PQ
{
    protected $N;       // PQ 中的元素数量
    protected $pq;      // 索引二叉堆, 由 1 开始
    protected $qp;      // 逆序: qp[pq[i]] = pq[qp[i]] = i
    protected $keys;    // 有优先级之分的元素
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
     * 是否存在索引为 k 的元素
     */
    public function contains(int $k): bool
    {
        return $this->qp[$k] != -1;
    }

    /**
     * 插入一个元素, 将它和索引 k 相关联
     */
    public function insert(int $k, $key): void
    {
        $this->N++;
        $this->qp[$k] = $this->N;
        $this->pq[$this->N] = $k;
        $this->keys[$k] = $key;
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
     * 将索引为 k 的元素设为 item
     */
    public function change(int $k, $key): void
    {
        $this->keys[$k] = $key;
        $this->swim($this->qp[$k]);
        $this->sink($this->qp[$k]);
    }

    /**
     * 删去索引 k 及其相关联的元素
     */
    public function delete(int $k): void
    {
        $index = $this->qp[$k];
        $this->exch($index, $this->N--);
        $this->swim($index);
        $this->sink($index);
        unset($this->keys[$k]);
        $this->qp[$k] = -1;
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
