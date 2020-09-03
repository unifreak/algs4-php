<?php
namespace Algs;

/**
 * p.380, t.4.2.7
 *
 * 计算强连通分量的 Kosaraju 算法.
 *
 * 这个算法是个典型示例: 它容易实现但难以理解.
 *
 * 这个实现只为 @see CC 添加了几行代码就做到了. 它将完成以下任务
 * 1. 在给定的一幅有向图 G 中, 使用 DepthFirstOrder 来计算它的方向图 G' 的逆后续排序
 * 2. 在 G 中进行标准的深度优先搜索, 但是要按照刚才计算得到的顺序而非标准的顺序来访问未被标记的顶点
 * 3. 在构造函数中, 所有在同一个递归 dfs() 调用中被访问到的顶点都在同一个强连通分量中. 将它们按照和
 *    CC 相同的方式识别出来
 *
 * 命题: p.381
 * - H: 正确性: 按照上述步骤, 构造函数中的每一次递归调用所标记的顶点都在同一个强连通分量中
 *   证明:
 *   + 首先证明 "每个和 s 强连通的顶点 v 都会在构造函数调用的 dfs(G, s) 中被访问到"
 *     用反证法: 假设有一个和 s 强连通的顶点 v 不会在构造函数调用的 dfs(G, s) 中被访问到. 因为
 *     存在从 s 到 v 的路径, 所以 v 肯定在之前就已经被标记过了. 但是因为也存在从 v 到 s 的路径,
 *     在 dfs(G, v) 调用中 s 肯定会被标记, 因此构造函数应该是不会调用 dfs(G, s) 的. 矛盾.
 *   + 其次证明 "构造函数调用的 dfs(G, s) 所到达的任意顶点 v 都必然是和 s 强连通的"
 *     设 v 为 dfs(G, s) 到达的某个顶点. 那么, G 中必然存在一条从 s 到 v 的路径, 因此只需要
 *     证明 G 中还存在一条从 v 到 s 的路径即可. 这也等价于 G' 中存在一条从 s 到 v 的路径, 因此
 *     只需要证明 G' 存在一条从 s 到 v 的路径即可.
 *     证明的核心在于, 按照逆后序进行的深度优先搜索意味着, 在 G' 中进行的深度优先搜索中, dfs(G, v)
 *     必然在 dfs(G, s) 之前就已经结束了 (因为逆后序是先 s 后 v, 所以后序则是先 v 后 s, 而后序
 *     就是遍历 "完成" 的顺序), 这样 dfs(G, v) 的调用就只会出现两种情况:
 *     - 调用在 dfs(G, s) 的调用之前 (并且也在 dfs(G, s) 的调用之前结束)
 *     - 调用在 dfs(G, s) 的调用之后 (并且也在 dfs(G, s) 的结束之前结束)
 *     第一种情况是不可能出现的, 因为在 G' 中存在一条从 v 到 s 的路径; 而第二种情况则说明 G'
 *     中存在一条从 s 到 v 的路径
 *   证毕
 * - I: Kosaraju 算法的预处理所需的时间和空间与 V+E 成正比且支持常数时间的有向图强连通性的查询
 *   证明: 该算法会处理有向图的反向图并进行两次深度优先搜索. 这 3 步所需的时间都与 V+E 成正比.
 *   反向复制一幅有向图所需的空间与 V+E 成正比
 */
class KosarajuSCC
{
    private $marked;
    private $id;
    private $count = 0;

    public function __construct(Digraph $G)
    {
        $this->marked = new Arr('bool', $G->V());
        $this->id = new Arr('int', $G->V());
        $order = new DepthFirstOrder($G->reverse());
        foreach ($order->reversePost() as $s) {
            if (! $this->marked[$s]) {
                $this->dfs($G, $s);
                $this->count++;
            }
        }
    }

    private function dfs(Digraph $G, int $v): void
    {
        $this->marked[$v] = true;
        $this->id[$v] = $this->count;
        foreach ($G->adj($v) as $w) {
            if (! $this->marked[$w]) {
                $this->dfs($G, $w);
            }
        }
    }

    public function stronglyConnected(int $v, int $w): bool
    {
        return $this->id[$v] === $this->id[$w];
    }

    public function id(int $v): int
    {
        return $this->id[$v];
    }

    public function count(): int
    {
        return $this->count;
    }

    /**
     * % php KosarajuSCC.php ../resource/tinyDG.txt
     * 5 components
     * 1
     * 5 4 3 2 0
     * 12 11 10 9
     * 8 6
     * 7
     */
    public static function main(array $args): void
    {
        $G = new Digraph(new In($args[0]));
        $scc = new self($G);
        $M = $scc->count();
        StdOut::println("$M components");

        $components = new Arr(Bag::class, $M);
        for ($i = 0; $i < $M; $i++) {
            $components[$i] = new Bag();
        }
        for ($v = 0; $v < $G->V(); $v++) {
            $components[$scc->id($v)]->add($v);
        }
        for ($i = 0; $i < $M; $i++) {
            foreach ($components[$i] as $v) {
                StdOut::print("$v ");
            }
            StdOut::println();
        }
    }
}

