<?php
namespace Algs;

/**
 * t.4.2.2, p.366
 *
 * 有向图数据类型
 *
 * 这段代码与 Graph 基本相同, 与 Graph 的区别:
 * - addEdge() 只调用 add() 一次
 * - 多了 reverse() 方法, 它将所有边方向反转. 这样用例就可以找出 "指向" 每个顶点的所有边
 */
class Digraph
{
    private $V;
    private $E;
    private $adj;

    /**
     * 创建一幅含有 V 个顶点但没有边的有向图
     */
    public function __construct($in)
    {
        if (is_int($in)) {
            $this->initAdjList($in);
        } else if ($in instanceof In) {
            $this->initGraphFrom($in);
        }
    }

    /**
     * 初始化邻接表
     */
    private function initAdjList(int $V)
    {
        $this->V = $V;
        $this->E = 0;
        $this->adj = new Arr(Bag::class, $V);   // 创建邻接表
        for ($v = 0; $v < $this->V; $v++) {     // 将所有链表初始化为空
            $this->adj[$v] = new Bag();
        }
    }

    /**
     * 从输入流读入图
     */
    private function initGraphFrom(In $in)
    {
        $this->initAdjList($in->readInt());  // 读取 V 并将图初始化
        $E = $in->readInt();                 // 读取 E
        // 添加一条边
        for ($i = 0; $i < $E; $i++) {
            $v = $in->readInt();             // 读取一个顶点
            $w = $in->readInt();             // 读取另一个顶点
            $this->addEdge($v, $w);          // 添加一条连接它们的边
        }
    }

    /**
     * 顶点总数
     */
    public function V(): int
    {
        return $this->V;
    }

    /**
     * 边的总数
     */
    public function E(): int
    {
        return $this->E;
    }

    /**
     * 向有向图中添加一条边 v->w
     *
     * 和 Graph 的区别在于, 只调用了 add() 一次
     */
    public function addEdge(int $v, int $w): void
    {
        $this->adj[$v]->add($w); // v -> w
        $this->E++;
    }

    /**
     * 由 v 指出的边连接的所有顶点
     */
    public function adj(int $v): \Iterator
    {
        return $this->adj[$v];
    }

    /**
     * 该图的反向图
     */
    public function reverse(): self
    {
        $R = new Digraph($this->V);
        for ($v = 0; $v < $this->V; $v++) {
            foreach ($this->adj($v) as $w) {
                $R->addEdge($w, $v); // w -> v
            }
        }
        return $R;
    }
}