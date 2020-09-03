<?php
namespace Algs;

/**
 * p.372, t.4.2.5
 */
class DirectedCycle
{
    private $marked;
    private $hasCycle = false;
    private $cycle;   // 有向环中的所有顶点 (如果存在)
    private $onStack; // 递归调用的栈上的所有顶点. 当找到一条边 v->w 且 w 在栈中时就找到了一个有向环

    public function __construct(Digraph $G)
    {
        $this->onStack = new Arr('bool', $G->V());
        $this->marked = new Arr('bool', $G->V());
        for ($s = 0; $s < $G->V(); $s++) {
            $this->dfs($G, $s, $s);
        }
    }

    /**
     * 通过 DFS 判定是否含有有向环
     */
    private function dfs(Digraph $G, int $v): void
    {
        $this->onStack[$v] = true;
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if ($this->hasCycle()) {
                return;                  // 提前结束
            } else if (! $this->marked[$w]) {
                $this->edgeTo[$w] = $v;
                $this->dfs($G, $w);
            } else if ($this->onStack[$w]) {
                $this->cycle = new Stack();
                for ($x = $v; $x != $w; $x = $this->edgeTo[$x]) {
                    $this->cycle->push($x);
                }
                $this->cycle->push($w);
                $this->cycle->push($v);
            }
        }
        $this->onStack[$v] = false;   // 返回本次调用后, 设置为 false
    }

    public function hasCycle(): bool
    {
        return $this->cycle !== null;
    }

    /**
     * 有向环中的所有顶点 (如果存在的话)
     */
    public function cycle(): \Iterator
    {
        return $this->cycle;
    }

    /**
     * php DirectedCycle.php ../resource/tinyDG.txt
     * has cycle
     */
    public static function main(array $args): void
    {
        $G = new Digraph(new In($args[0]));
        $cycle = new self($G);
        if (! $cycle->hasCycle()) {
            StdOut::print("NOT ");
        }
        StdOut::println("has cycle");
    }

}