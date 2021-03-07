<?php
namespace Algs;

/**
 * p.141
 *
 * 动态连通性问题的 quick-union 实现:
 * - 将每个 $id 中的触点对应的值当做另一个触点的名称 -- 这种联系称为_链接_
 *     这种用法实质上形成了树结构, 我们定义:
 *     + 树的_大小_: 是它节点的数量
 *     + 节点的_深度_: 是它到根节点的路径上的链接数
 *     + 树的_高度_: 是它所有节点中的最大深度
 *     但是 quick-union 因为始终把 p 所在的树挂到 q 所在树, 导致树很不平衡, 导致性能问题
 *     -> @see WeightedQuickUnionUF
 * - 指向自己的触点即为_根触点_
 * - 解决了 quick-find 中的最主要问题: union() 从平方级别变成线性级别的
 * - 但是因为 find() 对输入依赖强烈, 不能保证 quick-union 任何情况都比 quick-find 快
 *   + 最好情况, find() 只需访问数组一次 --> 线性级别
 *   + 最快情况(比如输入 0,1  0,2  0,3  ...), find() 需访问 2N-1 次 --> 平方级别
 *
 * 成本模型
 *   数组的访问次数
 * 命题
 *   - find() 访问数组次数在 1 和给定触点所对应节点的深度的两倍之间
 *   - union() 和 connected() 访问数组次数是 find() 的两倍
 *   - 如果给定的两个触点在不同树中, 则 union() 还需加 1
 */
class QuickUnionUF extends UF
{
    public function find($p)
    {
        while ($p != $this->id[$p]) {
            $p = $this->id[$p];
        }
        return $p;
    }

    public function union($p, $q)
    {
        $pRoot = $this->find($p);
        $qRoot = $this->find($q);
        if ($pRoot == $qRoot) {
            return;
        }
        $this->id[$pRoot] = $qRoot;
        $this->count--;
    }
}