<?php
namespace Algs;


/**
 * t.2.4.1, p.200, p.201, p.202
 *
 * 基于二叉堆的优先队列
 *
 * 堆有序: 当一颗二叉树的每个结点都大于等于它的两个子结点时, 它被称为堆有序
 *
 * 二叉堆
 *   是一组能够用堆有序的完全二叉树排序的元素, 并在数组中按照层及存储 (不使用数组的第一个位置). 简称为堆
 *   位置 k 的结点的父结点的位置是 ⌊k/2⌋, 而它的两个子结点的位置则分别为 2k 和 2k+1
 *
 * 堆的操作会首先进行一些简单的改动, 打破堆的状态, 然后再遍历堆并按照要求将堆的状态恢复, 这个过程叫做堆的有序化
 *   - 上浮: 当某个结点变得比它的父结点更大而打破堆有序状态, 需要一遍遍的通过交换它和它的父结点来修复堆 @see swim
 *   - 下沉: 当某个结点变得比它的两个子结点或是其中之一更小而打破堆有序状态,
 *          需要一遍遍的通过将它和它的两个子结点中的较大者交换来修复堆 @see sink
 *
 * 命题 p.199, p.202
 *   - P: 一颗大小为 N 的完全二叉树的高度为 ⌊lgN⌋
 *   - Q: 插入元素操作只需不超过 lgN+1 次比较, 删除最大元素操作需要不超过 2lgN 次比较
 *     证明: 两种操作都需要在根节点和堆底元素之间移动元素, 而路径的长度不超过 lgN. 对于路径上
 *     的每个结点, 删除最大元素操作需要两次比较 (除了堆底元素), 一次用来找出比较打的子结点, 一
 *     次用来确定该子结点是否需要上浮
 *
 * 改进思路
 *   - 多叉堆
 *     比如三叉堆, 位置 k 的结点大于等于位于 3k-1, 3k, 3k+1 的结点, 小于等于位于 ⌊(k+1)/3⌋ 的结点
 *     甚至对于给定的 d, 将其修改为任意的 d 叉树也并不困难. 但是需要在树高和在每个结点的 d 个子结点
 *     找到最大者的代价之间找到折中
 *   - 自动调整数组大小
 *   - 如果用例代码改变了优先队列中的对象, 则可能打破有序性. 可强制用例不能改变, 但会增加复杂性
 *   - 为了允许用例通过索引引用进入优先队列中的元素, 实现**索引优先队列** @see IndexMinPQ
 */
abstract class PQ
{
    protected $type;
    // 基于堆的完全二叉树
    protected $pq = [];
    // 存储于 pq[1..N] 中, pq[0] 没有使用, 这样计算子结点和父结点的关系更方便
    protected $N = 0;

    public function __construct(string $type, int $maxN)
    {
        $this->type = $type;
        $this->pq = new Arr($type, $maxN+1);
    }

    public function isEmpty(): bool
    {
        return $this->N == 0;
    }

    public function size(): int
    {
        return $this->N;
    }

    abstract protected function less(int $i, int $j): bool;

    protected function exch(int $i, int $j): void
    {
        $t = $this->pq[$i];
        $this->pq[$i] = $this->pq[$j];
        $this->pq[$j] = $t;
    }

    protected function swim(int $k): void
    {
        while ($k > 1 && $this->less((int) ($k / 2), $k)) {
            $j = (int) ($k / 2);
            $this->exch((int) ($k / 2), $k);
            $k = (int) ($k / 2);
        }
    }

    protected function sink(int $k): void
    {
        while (2 * $k <= $this->N) {
            $j = 2 * $k;
            // 子结点中选出优先级较高者. 注意 MinPQ 中, 指值较小的元素
            if ($j < $this->N && $this->less($j, $j+1)) $j++;
            if (! $this->less($k, $j)) break;
            $this->exch($k, $j);
            $k = $j;
        }
    }
}