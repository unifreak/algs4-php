<?php
namespace Algs;

/**
 * t.4.1.1, p.336
 *
 * 由顶点索引的整形链表数组实现的图
 *
 * 扩展
 * - 支持添加和删除顶点  --> 使用符号表 (ST) 代替由顶点索引构成的数组
 * - 支持删除边和检查边是否存在 --> 使用 SET 代替 Bag (称为**邻接集**)
 */
class Graph
{
    private $V;   // 顶点数目
    private $E;   // 边的数目
    private $adj; // 邻接表

    /**
     * 创建一幅无向图
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
     * 初始化邻接表, 创建一个不含边的无向图
     */
    private function initAdjList(int $V)
    {
        $this->V = $V;
        $this->E = 0;
        $this->adj = new Arr(Bag::class, $V);   // 创建邻接表: 使用 Bag 对象保证顺序无关
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
     * 向图中添加一条边 v-w
     */
    public function addEdge(int $v, int $w): void
    {
        $this->adj[$v]->add($w); // 将 w 添加到 v 的链表中
        $this->adj[$w]->add($v); // 将 v 添加到 w 的链表中
        $this->E++;
    }

    /**
     * 顶点数
     */
    public function V(): int
    {
        return $this->V;
    }

    /**
     * 边数
     */
    public function E(): int
    {
        return $this->E;
    }

    /**
     * 和 v 相邻的所有顶点
     */
    public function adj(int $v): \Iterator
    {
        return $this->adj[$v];
    }

    /**
     * 对象的字符串表示
     */
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
     * % php Graph.php ../data/tinyG.txt
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