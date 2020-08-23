<?php
namespace Algs;

/**
 * p.297
 *
 * 基于拉链法的散列表
 *
 * **拉链法**: 将大小为 M 的数组中的每个元素指向一条链表, 链表中的每个结点都存储了散列值为该元素的
 * 索引的键值对. 这个方法的基本思想就是选择足够大的 M, 使得所有链表都尽可能短以保证高效的查找. 查找
 * 分两步: 先根据散列值找到对应的链表, 然后沿着链表顺序查找相应的键
 *
 * 命题 p.298
 * - K: 在一张含有 M 条链表和 N 个键的散列表中, (在假设 J 成立的前提下) 任意一条链表中键的数量均
 *   在 N/M 的常数因子范围内的概率无限趋向于 1
 *   证明: 略
 *
 * 性质 p.299
 * - L: 在一张含有 M 条链表和 N 个键的散列表中, 未命中查找和插入操作所需要的比较次数为 ~N/M
 */
class SeparateChainingHashST
{
    private $N = 0;
    private $M = 0;
    private $st = [];

    public function __construct(int $M=997)
    {
        $this->M = $M;
        $this->st = new Arr(SequentialSearchST::class, $M);
        for ($i = 0; $i < $M; $i++) {
            $this->st[$i] = new SequentialSearchST();
        }
    }

    private function hash($key): int
    {
        return abs(hashCode($key)) % $this->M;
    }

    public function get($key)
    {
        return $this->st[$this->hash($key)]->get($key);
    }

    public function put($key, $val)
    {
        $this->st[$this->hash($key)]->put($key, $val);
    }

    public function keys()
    {
        $q = new Queue();
        foreach ($this->st as $st) {
            foreach ($st->keys() as $key) {
                $q->enqueue($key);
            }
        }
        return $q;
    }

    /**
     * % php SeparateChainingHashST.php < ../resource/tinyST.txt
     * A  8
     * C  4
     * E  12
     * H  5
     * L  11
     * M  9
     * P  10
     * R  3
     * S  0
     * X  7
     */
    public static function main(array $args): void
    {
        $st = new self();
        for ($i = 0; ! StdIn::isEmpty(); $i++) {
            $key = StdIn::readString();
            if ($key === null) break;
            $st->put($key, $i);
        }

        foreach ($st->keys() as $s) {
            StdOut::println("$s  {$st->get($s)}");
        }
    }
}