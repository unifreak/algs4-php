<?php
namespace Algs;

/**
 * p.239, p.240, p.241, p.242
 *
 * 基于有序数组的二分查找的符号表
 *
 * 命题 p.242
 * - B: 查找最多需要 lgN+1 次比较 (无论是否成功)
 *   证明: 和对归并排序的分析类似 (@see TopDownMergeSort) @see p.243
 * - B: 插入一个新的元素在最坏情况下需要 ~2N 次数组访问, 因此向一个空符号表中插入 N 个元素在最坏
 *   情况下需要 ~N^2 次数组访问
 */
class BinarySearchST
{
    private $keys;
    private $vals;
    private $N = 0;

    public function __construct(string $keyType, string $valType, int $capacity)
    {
        $this->keys = new Arr($keyType, $capacity);
        $this->vals = new Arr($valType, $capacity);
    }

    /**
     * 返回表中键值对数量
     */
    public function size(): int
    {
        return $this->N;
    }

    /**
     * 表是否为空
     */
    public function isEmpty(): bool
    {
        return $this->N == 0;
    }

    /**
     * 获取键对应的值
     */
    public function get($key)
    {
        if ($this->isEmpty()) return null;
        $i = $this->rank($key);
        // 因为 rank() 返回的是表中小于给定键的数量, 所以这里的命中判定条件为
        //      i < N && keys[i] == key
        // 当查找的键超过最大键时, 就会出现 $i == $this->N 的情况
        if ($i < $this->N && $this->keys[$i] == $key) return $this->vals[$i];
        else return null;
    }

    /**
     * 将键值对存入表中
     */
    public function put($key, $val)
    {
        $i = $this->rank($key); // 查找
        if ($i < $this->N && $this->keys[$i] == $key) { // 命中, 则更新其值
            $this->vals[$i] = $val;
            return;
        }
        for ($j = $this->N; $j > $i; $j--) { // 未命中, 右移腾出位置, 插入键值对
            $this->keys[$j] = $this->keys[$j-1];
            $this->vals[$j] = $this->vals[$j-1];
        }
        $this->keys[$i] = $key;
        $this->vals[$i] = $val;
        $this->N++;
    }

    /**
     * 返回表中小于给定键的键的数量
     *
     * 这段代码用迭代的方式实现了二分查找. 也可用递归来实现
     * rank 是这个类的核心. 要透彻理解它返回的数值的意义
     * - 如果表中存在该键, 返回的是该键的位置, 也就是表中小于它的键的数量
     * - 如果表中不存在该键, 还是应该返回表中小于它的键的数量
     */
    public function rank($key)
    {
        $lo = 0; $hi = $this->N - 1;
        while ($lo <= $hi) {
            $mid = (int) ($lo + ($hi - $lo) / 2);
            if ($key < $this->keys[$mid]) $hi = $mid - 1;
            elseif ($key > $this->keys[$mid]) $lo = $mid + 1;
            else return $mid;
        }
        return $lo;
    }

    /**
     * 从表中删除键 key
     */
    public function delete($key)
    {
        $i = $this->rank($key);
        if ($i == $this->N || $this->keys[$i] != $key) { // 未命中
            return null;
        }

        for ($j = $i; $j < $this->N-1; $j++) { // 命中
            $this->keys[$j] = $this->keys[$j+1];
            $this->vals[$j] = $this->vals[$j+1];
        }

        $this->N--;
        unset($this->keys[$this->N]);
        unset($this->vals[$this->N]);
    }

    /**
     * 键 key 是否存在于表中
     */
    public function contains($key)
    {
        // PHP 中, 0 == null, 所以这里一定要用全等
        return $this->get($key) !== null;
    }

    /**
     * 返回最小的键
     */
    public function min()
    {
        return $this->keys[0];
    }

    /**
     * 返回最大的键
     */
    public function max()
    {
        return $this->keys[$this->N-1];
    }

    /**
     * 返回排名为 k 的键
     */
    public function select(int $k)
    {
        return $this->keys[$k];
    }

    /**
     * 返回大于等于 key 的最小键
     */
    public function ceiling($key)
    {
        $i = $this->rank($key);
        // 如果命中, 直接返回命中的位于 $i 的键
        // 如果未命中, 位于 $i 的键是大于 $key 键的第一个键
        return $this->keys[$i];
    }

    /**
     * 返回小于等于 key 的最大键
     */
    public function floor($key)
    {
        $i = $this->rank($key);
        if ($i < $this->N && $this->keys[$i] == $key) return $this->keys[$i];
        elseif ($i == 0) return null;
        return $this->keys[$i-1];
    }

    /**
     * 返回已排序的 lo..hi 之间的所有键的可迭代集合
     */
    public function keys($lo, $hi)
    {
        $q = new Queue();
        for ($i = $this->rank($lo); $i < $this->rank($hi); $i++) {
            $q->enqueue($this->keys[$i]);
        }
        if ($this->contains($hi)) {
            $q->enqueue($this->keys[$this->rank($hi)]);
        }
        return $q;
    }

    /**
     * % more ../resource/tinyST.txt
     * S E A R C H E X A M P L E
     *
     * % php BinarySearchST.php 10 < ../resource/tinyST.txt
     * A 8
     * C 4
     * E 12
     * L 11
     * M 9
     * P 10
     * R 3
     * S 0
     */
    public static function main(array $args): void
    {
        $st = new self('string', 'int', (int) $args[0]);
        for ($i = 0; !StdIn::isEmpty(); $i++) {
            $key = StdIn::readString();
            if ($key == null) break;
            $st->put($key, $i);
        }

        $st->delete('H');
        foreach ($st->keys('A', 'S') as $s) {
            StdOut::println("$s {$st->get($s)}");
        }
    }
}