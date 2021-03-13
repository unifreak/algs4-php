<?php
namespace Algs;
use Algs\BST as ST;

/**
 * p.376
 *
 * 符号有向图
 *
 * SymbolDiraph 和 SymbolGraph 代码几乎相同, 只需把所有 Graph 替换为 Digraph 即可
 */
class SymbolDigraph
{
    private $st;    // 符号名 -> 索引
    private $keys;  // 索引 -> 符号名 (反向索引)
    private $G;     // 图

    /**
     * 根据 stream 指定的文件构造图, 使用 sp 来分割顶点名
     */
    public function __construct(string $stream, string $sp)
    {
        $this->st = new ST();
        $in = new In($stream);
        /**
         * 我们定义用例输入格式如下:
         * - 顶点名为字符串
         * - 用指定的分隔符来隔开顶点名
         * - 每一行都表示一组边的集合, 每一条边都连接着这一行的第一个名称表示的顶点和其他名称表示的顶点
         * - 顶点总数 V 和边的总数 E 都是隐式定义的
         */
        while ($in->hasNextLine()) {
            $a = explode($sp, $in->readline());
            for ($i = 0; $i < count($a); $i++) {
                if (! $this->st->contains($a[$i])) {
                    $this->st->put($a[$i], $this->st->size());
                }
            }
        }

        $this->keys = new Arr('string', $this->st->size());
        foreach ($this->st->keys($this->st->min(), $this->st->max()) as $name) {
            $this->keys[$this->st->get($name)] = $name;
        }

        $this->G = new Digraph($this->st->size());
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
    public function index(string $s): int
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
     * 隐藏的 Digraph 对象
     */
    public function G(): Digraph
    {
        return $this->G;
    }

    /**
     * 这个用例正好是 C3.5 研究过的方向索引的功能 @see LookUpIndex
     *
     * % php SymbolDigraph.php ../resource/routes.txt " "
     * JFK
     *     ORD
     *     ATL
     *     MCO
     * LAX
     *     LAS
     *     PHX
     *
     * % php SymbolDigraph.php ../resource/movies.txt "/"
     * Tin Men (1987)
     *     Hershey, Barbara
     *     Geppi, Cindy
     *     Jones, Kathy (II)
     *     ...
     *     Herr, Marcia
     *     Sills, Ellen
     *     Pohlman, Patricia
     *     ...
     *     DeBoy, David
     * Bacon, Kevin
     *     Friday the 13th (1980)
     *     Footloose (1984)
     *     ...
     */
    public static function main(array $args): void
    {
        $filename = $args[0];
        $delim = $args[1];
        $sg = new SymbolDigraph($filename, $delim);

        $G = $sg->G();
        while (StdIn::hasNextLine()) {
            $source = StdIn::readLine();
            foreach ($G->adj($sg->index($source)) as $w) {
                StdOut::println("    {$sg->name($w)}");
            }
        }
    }
}