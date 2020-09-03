<?php
namespace Algs;

/**
 * p.349
 *
 * 使用 DFS 找出图中的所有连通分量
 *
 * 该算法会循环查找每个没有被标记的顶点, 并递归调用 dfs() 来标记和他相邻的所有顶点. 它还使用了
 * 一个以顶点作为索引的数组 id, 将同一个连通分量中的顶点和连通分量的标识符关联起来
 *
 * 命题: p.350
 * - C: DFS 的预处理使用的时间和空间与 V+E 成正比且可以在常数时间内处理关于图的连通性查询
 *   证明: 由代码可知每个邻接表中的元素都只会被检查一次, 共有 2E 个元素 (每条边两个). 实例方法
 *   会检查或者返回一个或两个变量
 *
 * 理论上, DFS 比 union-find (@see WeightedQuickUnionUF) 快, 但实际应用中, union-find
 * 其实更快. 因为它不需要完整构造并表示一幅图. 更重要的是, union-find 是一种动态算法 (任何时候
 * 都能用接近常数的时间检查两个顶点是否连通, 甚至在添加一条边的时候), 但 DFS 则必须对图进行预处理.
 * 因此, 在完成需要判断连通性或是需要完成有大量连通性查询和插入操作混合等类似的任务时, 更倾向于使用
 * union-find 算法, 而 DFS 则更适合实现图的抽象数据类型, 因为它能更有效的利用已有的数据结构.
 */
class CC
{
    private $marked;
    private $id;        // 基于顶点索引的数组. 如果 v 属于第 i 个连通分量, 则 id[v] 为 i
    private $count = 0;

    public function __construct(Graph $G)
    {
        $this->marked = new Arr('bool', $G->V());
        $this->id = new Arr('int', $G->V());
        for ($s = 0; $s < $G->V(); $s++) {
            if (! $this->marked[$s]) {
                $this->dfs($G, $s);
                $this->count++;
            }
        }
    }

    private function dfs(Graph $G, int $v): void
    {
        $this->marked[$v] = true;
        $this->id[$v] = $this->count;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w);
            }
        }
    }

    /**
     * v 和 w 是连通的吗
     */
    public function connected(int $v, int $w): bool
    {
        return $this->id[$v] === $this->id[$w];
    }

    /**
     * v 所在的连通分量的标志符 (0 ~ $this->count-1)
     */
    public function id(int $v): int
    {
        return $this->id[$v];
    }

    /**
     * 连通分量数
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * php CC.php ../resource/tinyG.txt
     * 3 components
     * 6 5 4 3 2 1 0
     * 8 7
     * 12 11 10 9
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        $cc = new self($G);
        $M = $cc->count();
        StdOut::println("$M components");

        $components = new Arr(Bag::class, $M);
        for ($i = 0; $i < $M; $i++) {
            $components[$i] = new Bag();
        }
        for ($v = 0; $v < $G->V(); $v++) {
            $components[$cc->id($v)]->add($v);
        }
        for ($i = 0; $i < $M; $i++) {
            foreach ($components[$i] as $v) {
                StdOut::print("$v ");
            }
            StdOut::println();
        }
    }
}