<?php
namespace Algs;

/**
 * p.336
 *
 * 由顶点索引的整形链表数组实现的图
 *
 * 扩展
 * - 支持添加和删除顶点  --> 使用符号表 (ST) 代替由顶点索引构成的数组
 * - 支持删除边和检查边是否存在 --> 使用 SET 代替 Bag (称为**邻接集**)
 */
class Graph
{
    private $V; // 顶点数目
    private $E; // 边的数目
    private $adj; // 邻接表

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

    public function V(): int
    {
        return $this->V;
    }

    public function E(): int
    {
        return $this->E;
    }

    public function addEdge(int $v, int $w): void
    {
        $this->adj[$v]->add($w); // 将 w 添加到 v 的链表中
        $this->adj[$w]->add($v); // 将 v 添加到 w 的链表中
        $this->E++;
    }

    public function adj(int $v): \Iterator
    {
        return $this->adj[$v];
    }

    public function __toString()
    {
        $s = "$this->V vertices, $this->E edges\n";
        for ($v = 0; $v < $this->V; $v++) {
            $s .= "$v: ";
            foreach ($this->adj($v) as $w) {
                $s .= "$w ";
            }
            $s .= "\n";
        }
        return $s;
    }

    /**
     * % php Graph.php ../resource/tinyG.txt
     * 13 vertices, 13 edges
     * 0: 6 2 1 5
     * 1: 0
     * 2: 0
     * 3: 5 4
     * 4: 5 6 3
     * 5: 3 4 0
     * 6: 0 4
     * 7: 8
     * 8: 7
     * 9: 11 10 12
     * 10: 9
     * 11: 9 12
     * 12: 11 9
     */
    public static function main(array $args): void
    {
        $in = new In($args[0]);
        $G = new Graph($in);
        StdOut::println($G);
    }
}