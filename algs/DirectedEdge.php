<?php
namespace Algs;

/**
 * t.4.4.2, p.415
 *
 * 加权有向边的数据类型
 *
 * 比 Edge 类更简单, 因为边的两个端点是有区别的
 * 用例可以使用 v=e->to(), w=e->from() 访问其两个端点
 */
class DirectedEdge
{
    private $v;
    private $w;
    private $weight;

    public function __construct(int $v, int $w, float $weight)
    {
        $this->v = $v;
        $this->w = $w;
        $this->weight = $weight;
    }

    public function weight(): float
    {
        return $this->weight;
    }

    public function from(): int
    {
        return $this->v;
    }

    public function to(): int
    {
        return $this->w;
    }

    public function __toString(): string
    {
        return sprintf("%d->%d %.2f", $this->v, $this->w, $this->weight);
    }
}