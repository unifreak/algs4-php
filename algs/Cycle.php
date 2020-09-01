<?php
namespace Algs;

/**
 * p.352
 *
 * 使用 DFS 判断是否是无环图
 */
class Cycle
{
    private $marked;
    private $hasCycle = false;

    public function __construct(Graph $G)
    {
        $this->marked = new Arr('bool', $G->V());
        for ($s = 0; $s < $G->V(); $s++) {
            $this->dfs($G, $s, $s);
        }
    }

    /**
     * 通过 DFS 判定是否是无环图
     *
     * v 是当前 DFS 的顶点, u 是刚被 DFS 的顶点
     */
    private function dfs(Graph $G, int $v, int $u): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w, $v);
            /**
             * 这部分较难理解, 也是算法的核心部分
             *
             *       1 - 2 - 3
             *           |    |
             *           5 -- 4
             *
             * 走到 else, 说明 w 已经被标记过. 那么有两种可能的情况
             * 1. 如果 w == u, 说明 w 是上层 DFS 走过来的 u 点. 如图 2-3, dfs(3) 时, 和
             *    3 相连的点 2 刚走过来, 这种情况不作处理, 但是
             * 2. 如果 w != u, 说明 w 不是 u, 但已经在路径上走过. 如从 1-2-3-4-5 过来, 5
             *    的相邻顶点 2 (即 w), 已从 1 走过, 此时发现 5 (即 u) 也能到 2, 说明有环
             */
            } else if ($w != $u) {
                $this->hasCycle = true;
            }
        }
    }

    public function hasCycle(): bool
    {
        return $this->hasCycle;
    }

    /**
     * php Cycle.php ../resource/tinyG.txt
     * has cycle: 1
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        $cycle = new self($G);
        if (! $cycle->hasCycle()) {
            StdOut::print("NOT ");
        }
        StdOut::println("has cycle");
    }
}