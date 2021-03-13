<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.394, t.4.3.2
 *
 * 带权重的边的数据类型
 *
 * 用例可以使用 other($v) 得到边的另一个顶点. 当两个顶点都是未知的时候, 可以使用惯用代码
 * `$v=$e->either(), $w=$e->other($v)` 来访问 Edge 对象的两个顶点
 */
class Edge implements Comparable
{
    private $v;      // 顶点之一
    private $w;      // 另一个顶点
    private $weight; // 边的权重

    public function __construct(int $v, int $w, float $weight)
    {
        $this->v = $v;
        $this->w = $w;
        $this->weight = $weight;
    }

    /**
     * 边的权重
     */
    public function weight(): float
    {
        return $this->weight;
    }

    /**
     * 边两端的顶点之一
     */
    public function either(): int
    {
        return $this->v;
    }

    /**
     * 另一个顶点
     */
    public function other(int $vertex)
    {
        if ($vertex == $this->v) return $this->w;
        else if ($vertex == $this->w) return $this->v;
        else throw new \InvalidArgumentException("Inconsitent edge");
    }

    /**
     * 将这条边与 that 比较
     */
    public function compareTo($that)
    {
        if ($this->weight() < $that->weight()) return -1;
        else if ($this->weight() > $that->weight()) return 1;
        else return 0;
    }

    /**
     * 对象的字符串表示
     */
    public function __toString(): string
    {
        return sprintf("%d-%d %.2f", $this->v, $this->w, $this->weight);
    }
}