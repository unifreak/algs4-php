<?php
namespace Algs;

/**
 * p.337, p.338
 *
 * 无向图的连通性问题: 找到和起点 s 连通的所有顶点
 *
 * 我们已经见过 Search API 的另一种实现: @see 使用 UF
 * - 在 UF 中实现 marked():
 *   构造函数创建一个 UF 对象, 对图中每一条边进行一次 union() 操作并调用 connected() 实现
 * - 在 UF 中实现 count():
 *   需要一个加权的 UF (@see WeightedQuickUnionFind) 并扩展它的 API, 以便使用 count() 返回 wt[find(v)]
 *
 * 现在要学的实现即深度优先搜索算法 @see DepthFirstSearch, DirectedDFS
 */
interface Search
{
    /**
     * 找到和起点 s 联通的所有顶点
     */
    public function __construct(Graph $G, int $s);

    /**
     * v 和 s 是连通的吗
     */
    public function marked(int $v);

    /**
     * 与 s 连通的顶点个数
     */
    public function count();
}