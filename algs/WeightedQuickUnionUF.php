<?php
namespace Algs;

/**
 * p.138
 *
 * 动态连通性问题的 union-find 实现:
 * - $id 用法同 quick-union
 * - 使用 $sz 跟踪树大小, 始终把小树挂到大树, 树相对平衡, 保证了**对数级别**的性能
 *
 *
 * 成本模型
 *   数组的访问次数
 * 命题
 *   - quick-union 构造的森林中的任意节点的深度最多为 lgN
 *     证明: 可以用归纳法证明一个更强的命题: 森林中大小为 k 的树的高度最多为 lgk
 *     1. 原始情况中, k=1 时高度为 0. 假设大小为 i 的树的高度最多为 lgi, 其中 i<k
 *     2. 设 i <= j 且 i+j=k, 将大小为 i 和大小为 j 的树归并时, 小树中所有节点的深度增加了 1
 *        但它们现在所在树的大小为 i+j=k, 而 1+lgi=lg2+lgi=lg(i+i) <= lg(i+j) = lgk, 性质成立
 *   - 最坏情况的 find(), connected(), 和 union() 成本的增长数量级为 logN
 *     证明: 在森林中, 对于从一个节点到它的根节点的路径上的每个节点, 每种操作最多都只会访问数组常数次
 */
class WeightedQuickUnionUF extends QuickUnionUF
{
    /**
     * (由触点索引的) 各个根节点所对应的分量的大小
     */
    private $sz = [];

    public function __construct(int $N)
    {
        parent::__construct($N);
        $this->sz = new Arr('int', $N);
        for ($i = 1; $i < $N; $i++) {
            $this->sz[$i] = 1;
        }
    }

    public function union($p, $q)
    {
        $i = $this->find($p);
        $j = $this->find($q);
        if ($i == $j) {
            return;
        }

        // 将小树的根节点连接到大树的根节点
        if ($this->sz[$i] < $this->sz[$j]) {
            $this->id[$i] = $j;
            $this->sz[$j] += $this->sz[$i];
        } else {
            $this->id[$j] = $i;
            $this->sz[$i] += $this->sz[$j];
        }
        $this->count--;
    }
}