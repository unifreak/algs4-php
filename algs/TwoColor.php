<?php
namespace Algs;

/**
 * p.352
 *
 * 使用 DFS 判定是否是二分图 (双色问题)
 */
class TwoColor
{
    private $marked;
    private $color;
    private $isTwoColorable = true;

    public function __construct(Graph $G)
    {
        $this->marked = new Arr('bool', $G->V());
        $this->color = new Arr('bool', $G->V());
        for ($s = 0; $s < $G->V(); $s++) {
            if (! $this->marked[$s]) {
                $this->dfs($G, $s);
            }
        }
    }

    private function dfs(Graph $G, int $v): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->color[$w] = !$this->color[$v];
                $this->dfs($G, $w);
            } else if ($this->color[$w] == $this->color[$v]) {
                $this->isTwoColorable = false;
            }
        }
    }

    public function isBipartitie(): bool
    {
        return $this->isTwoColorable;
    }

    /**
     * % php TwoColor.php ../resource/tinyG.txt
     * NOT is bipartitie
     */
    public static function main(array $args): void
    {
        $G = new Graph(new In($args[0]));
        $search = new self($G);
        if (! $search->isBipartitie()) {
            StdOut::print("NOT ");
        }
        StdOut::print("is bipartitie");
    }
}