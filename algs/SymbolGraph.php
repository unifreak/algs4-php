<?php
namespace Algs;
use Algs\BST as ST;

/**
 * t.4.1.8, p.354, p.356
 *
 * 符号图
 *
 * @todo 数据文件太大的话, 直接报 segement fault 11 错误
 *
 * 注意, 这是一幅二分图 -- 电影顶点之间或者演员结点之间没有边相连
 * 二分图的性质自动完成了反向索引. 这将成为处理更复杂的和图有关的问题的基础
 */
class SymbolGraph
{
    private $st;    // 符号名 -> 索引
    private $keys;  // 索引 -> 符号名 (反向索引)
    private $G;     // 图

    /**
     * 根据 stream 指定的文件构造图, 使用 sp 来分割顶点名
     *
     * 我们定义用例输入格式如下:
     * - 顶点名为字符串
     * - 用指定的分隔符来隔开顶点名
     * - 每一行都表示一组边的集合, 每一条边都连接着这一行的第一个名称表示的顶点和其他名称表示的顶点
     * - 顶点总数 V 和边的总数 E 都是隐式定义的
     */
    public function __construct(string $stream, string $sp)
    {
        $this->st = new ST();
        $in = new In($stream);
        while ($in->hasNextLine()) {
            $a = explode($sp, $in->readline());
            for ($i = 0; $i < count($a); $i++) {
                if (! $this->st->contains($a[$i])) {
                    // key: 符号 (如 JFK); val: st 当前大小, 用作索引
                    $this->st->put($a[$i], $this->st->size());
                }
            }
        }

        $this->keys = new Arr('string', $this->st->size());
        foreach ($this->st->keys($this->st->min(), $this->st->max()) as $name) {
            $this->keys[$this->st->get($name)] = $name;
        }

        $this->G = new Graph($this->st->size());
        $in = new In($stream);
        while ($in->hasNextLine()) {
            $a = explode($sp, $in->readLine());
            $v = $this->st->get($a[0]);
            for ($i = 1; $i < count($a); $i++) {
                $this->G->addEdge($v, $this->st->get($a[$i]));
            }
        }
    }

    /**
     * s 是一个顶点吗
     */
    public function contains(string $s): bool
    {
        return $this->st->contains($s);
    }

    /**
     * s 的索引
     */
    public function index(string $s): ?int
    {
        return $this->st->get($s);
    }

    /**
     * 索引 v 的顶点名
     */
    public function name(int $v): string
    {
        return $this->keys[$v];
    }

    /**
     * 隐藏的 Graph 对象
     */
    public function G(): Graph
    {
        return $this->G;
    }

    /**
     * 用例 1: 正好是 C3.5 研究过的反向索引的功能 @see LookUpIndex
     *
     * % php SymbolGraph.php ../resource/routes.txt " "
     * JFK
     *     ORD
     *     ATL
     *     MCO
     * LAX
     *     LAS
     *     PHX
     *
     * 用例 2: 名 Kevin Bacon 的游戏, 找到社交网路中两人之间间隔的度数
     *
     * % php SymbolGraph.php ../resource/movies.txt "/"
     * Bacon, Kevin
     *     Friday the 13th (1980)
     *     Footloose (1984)
     *     ...
     */
    public static function main(array $args): void
    {
        $filename = $args[0];
        $delim = $args[1];
        $sg = new SymbolGraph($filename, $delim);

        $G = $sg->G();
        while (StdIn::hasNextLine()) {
            $source = StdIn::readLine();
            if (! $sg->contains($source)) {
                StdOut::println("$source not in database");
                return;
            }
            foreach ($G->adj($sg->index($source)) as $w) {
                StdOut::println("    {$sg->name($w)}");
            }
        }
    }
}