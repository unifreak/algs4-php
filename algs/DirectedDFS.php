<?php
namespace Algs;

/**
 * p.367, t.4.2.3
 *
 * 有向图的可达性: 使用 DFS 判断从给定一个或一组顶点能到达哪些其他顶点
 *
 * 命题: p.367
 * - D: 在有向图中, DFS 标记由一个集合的顶点可达的所有顶点所需要的时间与被标记的所有顶点的出度
 *      之和成正比
 */
class DirectedDFS
{
    private $marked;

    public function __construct(Digraph $G, $s)
    {
        $this->marked = new Arr('bool', $G->V());
        if (is_int($s)) {
            return $this->forSingle($G, $s);
        } else if ($s instanceof \Iterator) {
            return $this->forBatch($G, $s);
        }
        throw new \InvalidArgumentException("the second argument must be integer or \Iterator");
    }

    private function forSingle(Digraph $G, int $s)
    {
        $this->dfs($G, $s);
    }

    private function forBatch(Digraph $G, \Iterator $sources)
    {
        foreach ($sources as $s) {
            if (! $this->marked[$s]) {
                $this->dfs($G, $s);
            }
        }
    }

    private function dfs(Digraph $G, int $v): void
    {
        $this->marked[$v] = true;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w);
            }
        }
    }

    public function marked(int $v): bool
    {
        return $this->marked[$v];
    }

    /**
     * % php DirectedDFS.php ../data/tinyDG.txt 1
     * 1
     *
     * % php DirectedDFS.php ../data/tinyDG.txt 2
     * 0 1 2 3 4 5
     *
     * % php DirectedDFS.php ../data/tinyDG.txt 1 2 6
     * 0 1 2 3 4 5 6 8 9 10 11 12
     */
    public static function main(array $args): void
    {
        $G = new Digraph(new In($args[0]));
        $sources = new Bag();
        for ($i = 1; $i < count($args); $i++) {
            $sources->add((int) $args[$i]);
        }

        $reachable = new self($G, $sources);
        for ($v = 0; $v < $G->V(); $v++) {
            if ($reachable->marked($v)) {
                StdOut::print("$v ");
            }
        }
        StdOut::println();
    }
}