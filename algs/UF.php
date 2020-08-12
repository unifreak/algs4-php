<?php
namespace Algs;

/**
 * t.1.5.1
 *
 * 动态连通性问题的四种实现:
 * - quick-find @see QuickFindUF
 * - quick-union @see QuickUnionUF
 * - 加权 quick-union @see WeightedQuickUnionUF
 * - 路径压缩的加权 quick-union @todo
 */
abstract class UF
{
    protected $id;
    protected $count = 0;

    public function __construct(int $N)
    {
        $this->count = $N;
        $this->id = new Arr('int', $N);
        for ($i = 0; $i < $N; $i++) {
            $this->id[$i] = $i;
        }
    }

    public function count()
    {
        return $this->count;
    }

    public function connected(int $p, int $q)
    {
        return $this->find($p) == $this->find($q);
    }

    abstract public function find($p);

    abstract public function union($p, $q);

    /**
     * % php QuickFindUF.php < ../resource/tinyUF.txt
     * 4 3
     * 3 8
     * 6 5
     * ...
     * 2 components
     *
     * % php QuickFindUF.php < ../resource/mediumUF.txt
     * 528 503
     * 548 523
     * 389 414
     * ...
     * 3 components
     *
     * % php WeightedQuickUnionUF.php < ../resource/largeUF.txt
     * 786321 134521
     * 696834 98245
     * 135991 549478
     * ...
     * 6 components
     */
    public static function main(array $args)
    {
        $N = StdIn::readInt();
        $uf = new static($N);
        while (! StdIn::isEmpty()) {
            $p = StdIn::readInt();
            $q = StdIn::readInt();
            if (is_null($p) || is_null($q)) {
                break;
            }
            if ($uf->connected($p, $q)) {
                continue;
            }
            $uf->union($p, $q);
            StdOut::println("$p $q");
        }
        StdOut::println("{$uf->count()} components");
    }
}