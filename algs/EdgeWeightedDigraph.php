<?php
namespace Algs;

/**
 * t.4.4.3, p.415
 *
 * 加权有向图的数据类型
 *
 * 这个实现混合了 @see EdgeWeightedGraph 和 Digraph 类
 * 与 Digraph 一样, 每条边在邻接表中出现一次: 如果一条边从 v 指向 w, 它只会出现在 v 的邻接表中
 * 这个类可以处理自环和平行边
 */
class EdgeWeightedDigraph
{
    private $V;
    private $E;
    private $adj;

    /**
     * 创建一幅含有 V 个顶点的加权有向图
     * - 如果传入一个整数: 创建一个含有 V 个顶点但不含有边的图
     * - 如果传入一个输入流: 从标准输入流 in 从读入一幅度
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
     * 初始化邻接表, 创建一个不含边的加权无向图
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
            $weight = $in->readDouble();
            $this->addEdge(new DirectedEdge($v, $w, $weight)); // 添加一条连接它们的边
        }
    }

    /**
     * 图的顶点数
     */
    public function V(): int
    {
        return $this->V;
    }

    /**
     * 图的边数
     */
    public function E(): int
    {
        return $this->E;
    }

    /**
     * 向图中添加一条边 e
     */
    public function addEdge(DirectedEdge $e): void
    {
        $this->adj[$e->from()]->add($e);
        $this->E++;
    }

    /**
     * 和 v 相关联的所有边
     */
    public function adj(int $v): \Iterator
    {
        return $this->adj[$v];
    }

    /**
     * 图的所有边
     */
    public function edges(): \Iterator
    {
        $bag = new Bag();
        for ($v = 0; $v < $this->V; $v++) {
            foreach ($this->adj[$v] as $e) {
                $bag->add($e);
            }
        }
        return $bag;
    }
}