<?php
namespace Algs;

/**
 * p.395, p.394, t.4.3.3
 *
 * 加权无向图的数据类型
 *
 * 与 @see Graph 一样:
 * - 每条边都会出现两次
 * - 允许存在平行边. 也可以用更复杂的方式消除平行边, 比如只保留平行的边中的权重最小者
 * - 允许存在自环. 但没并没有实现统计它们, 这对 MST 算法没有影响, 因为 MST 肯定不会含有环
 */
class EdgeWeightedGraph
{
    private $V;
    private $E;
    private $adj;

    /**
     * 创建一幅含有 V 个顶点的加权无向图
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
            $this->addEdge(new Edge($v, $w, $weight)); // 添加一条连接它们的边
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
    public function addEdge(Edge $e): void
    {
        $v = $e->either();
        $w = $e->other($v);
        $this->adj[$v]->add($e);
        $this->adj[$w]->add($e);
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
        $b = new Bag();
        for ($v = 0; $v < $this->V; $v++) {
            foreach ($this->adj[$v] as $e) {
                if ($e->other($v) > $v) {   // 这个判断用于去重
                    $b->add($e);
                }
            }
        }
        return $b;
    }
}