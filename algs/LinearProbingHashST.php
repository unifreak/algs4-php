<?php
namespace Algs;

/**
 * p.301
 *
 * 基于线性探测的符号表
 *
 * 开放地址散列表
 * : 用大小为 M 的数组保存 N 个键值对, 其中 M > N. 需要依靠数组中的空位解决碰撞冲突.
 *   其核心思想是与其将内存用作链表, 不如将它们作为在散列表中的空元素.
 *   开放地址散列表中最简单的方法叫做
 * 线性探测法
 * : 当碰撞发生时, 直接检查(_探测_) 散列表中下一个位置
 *
 * 平均成本取决于元素在插入数组后聚集成的一组连续的条目, 也叫做 **键簇**. 显然, 短小的键簇才能保证较高的效率
 * 本实现中, 将键和值分别保存在两个数组中, 使用 null 表示一簇键的结束
 *
 * 命题 p.304, p.305
 * - M: (假设 J 前提下) 当散列表快满的时候查找所需的探测次数是巨大的, 但当使用率 α 小于 1/2 时
 *   探测预计次数只在 1.5 ~ 2.5 之间
 * - N: 假设一张散列表能自己调整数组大小, 初始为空. 基于假设 J, 执行任意顺序的 t 次查找, 插入和
 *   删除操作所需的时间和 t 成正比, 所使用的的内存量总是在表中的键的总数的常数因子范围内
 */
class LinearProbingHashST
{
    private $N;         // 符号表中键值对的总数
    private $M;         // 线性探测表的大小
    private $keyType;
    private $keys;      // 键
    private $valType;
    private $vals;      // 值

    public function __construct(string $keyType, string $valType, int $cap=16)
    {
        $this->M = $cap;
        $this->N = 0;
        $this->keyType = $keyType;
        $this->valType = $valType;
        $this->keys = new Arr($keyType, $this->M);
        $this->vals = new Arr($valType, $this->M);
    }

    public function size()
    {
        return $this->N;
    }

    public function isEmpty()
    {
        return $this->size() == 0;
    }

    public function contains($key)
    {
        return $this->get($key) !== null;
    }

    private function hash($key): int
    {
        return abs(hashCode($key) % $this->M);
    }

    private function resize(int $cap)
    {
        $t = new self($this->keyType, $this->valType, $cap);
        for ($i = 0; $i < $this->M; $i++) {
            if ($this->keys[$i] !== null) {
                $t->put($this->keys[$i], $this->vals[$i]);
            }
        }
        $this->keys = $t->keys;
        $this->vals = $t->vals;
        $this->M = $t->M;
    }

    public function put($key, $val)
    {
        // 将 M 加倍
        // 注意: 这会导致散列表的大小总是 2 的幂, 导致 hash() 只能使用 hashCode() 返回值的低位, p.307
        if ($this->N >= $this->M / 2) $this->resize(2 * $this->M);

        for ($i = $this->hash($key); $this->keys[$i] != null; $i = ($i + 1) % $this->M) {
            // 使用 i=(i+1)%M 增长, 确保索引始终在 M 内
            if ($this->keys[$i] == $key) { // 如果已经有, 则更新
                $this->vals[$i] = $val;
                return;
            }
        }
        $this->keys[$i] = $key;
        $this->vals[$i] = $val;
        $this->N++;
    }

    public function get($key)
    {
        for ($i = $this->hash($key); $this->keys[$i] != null; $i = ($i + 1) % $this->M) {
            if ($this->keys[$i] == $key) {
                return $this->vals[$i];
            }
        }
        return null;
    }

    /**
     * 删除指定键
     *
     * 如果直接将该键所在位置设置 null 是不行的, 这会使得在此位置之后的元素无法被查找.
     * 需要将簇中被删除的右侧的所有键重新插入散列表
     */
    public function delete($key)
    {
        if (! $this->contains($key)) return;

        // 找到 key 并删除 (将其位置设为 null)
        $i = $this->hash($key);
        while (! $key == $this->keys[$i]) {
            $i = ($i + 1) % $this->M;
        }
        $this->keys[$i] = null;
        $this->vals[$i] = null;

        // 将其之后位置的元素重新插入
        $i = ($i + 1) % $this->M;
        while ($this->keys[$i] !== null) {
            $keyToRedo = $this->keys[$i];
            $valToRedo = $this->vals[$i];
            $this->keys[$i] = null;
            $this->vals[$i] = null;
            $this->N--; // 这里的减一会被 put() 调用中的加一抹平
            $this->put($keyToRedo, $valToRedo);
            $i = ($i + 1) % $this->M;
        }

        $this->N--;
        if ($this->N > 0 && $this->N == $this->M / 8) {
            $this->resize($this->M / 2);
        }
    }

    public function keys()
    {
        $q = new Queue();
        for ($i = 0; $i < $this->M; $i++) {
            if ($this->keys[$i] !== null) {
                $q->enqueue($this->keys[$i]);
            }
        }
        return $q;
    }

    /**
     * % php SeparateChainingHashST.php < ../data/tinyST.txt
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
        $st = new self('string', 'int');
        for ($i = 0; ! StdIn::isEmpty(); $i++) {
            $key = StdIn::readString();
            if ($key === null) break;
            $st->put($key, $i);
        }

        StdOut::println("size: {$st->size()}");
        foreach ($st->keys() as $s) {
            StdOut::println("$s  {$st->get($s)}");
        }
    }
}
