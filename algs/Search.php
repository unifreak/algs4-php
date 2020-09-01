<?php
namespace Algs;

/**
 * p.337, p.338
 *
 * 找到和起点 s 连通的所有顶点
 *
 * 我们已经见过 Search API 的另一种实现, 使用 UF (@todo e.4.1.8)
 * - 实现 marked(): @see UF
 *   构造函数创建一个 UF 对象, 对图中每一条边进行一次 union() 操作并调用 connected() 实现
 * - 实现 count(): @see WeightedQuickUnionUF
 *   需要一个加权的 UF 并扩展它的 API, 以便使用 count() 返回 wt[find(v)]
 *
 * 现在要学的实现即深度优先搜索算法 @see DepthFirstSearch
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