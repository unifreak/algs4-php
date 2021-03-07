<?php
namespace Algs;

/**
 * p.233, p.236, t.3.1.4
 *
 * 基于无序链表的顺序查找的符号表
 *
 * 非常低效
 *
 * 命题 p.237
 * - A: 未命中的查找和插入操作都需要 N 次比较. 命中的查找在最坏情况下需要 N 次比较. 特别地,
 *   向一个空表中插入 N 个不同的键需要 ~N^2/2 次比较
 */
class SequentialSearchST
{
    private $first;

    /**
     * 查找给定的键, 返回相关联的值
     */
    public function get($key)
    {
        for ($x = $this->first; $x != null; $x = $x->next) {
            if ($key == $x->key) {
                return $x->val; // 命中
            }
        }
        return null; // 未命中
    }

    /**
     * 查找给定的键, 找到则更新其值, 否则在表中新建结点
     */
    public function put($key, $val)
    {
        for ($x = $this->first; $x != null; $x = $x->next) {
            if ($key == $x->key) {
                $x->val = $val;
                return;
            }
        }
        $this->first = new STNode($key, $val, $this->first); // 前插
    }

    /**
     * 从表中删去键 key
     *
     * 对于链表, 为了删除指定任意键, 标准做法是使用双向链表
     * 这段代码则展示了如何使用单向链表进行删除. 这里用的递归, 也可用循环实现
     * 
     * 如对于无序链表: first -> 4:C -> 3:R -> 2:A -> 1:E -> 0:S
     * 调用 delete(2) 以删除 A:
     * 
     *   first =  deleteAt(first, 2)
     *            first -> deleteAt(4:C, 2)
     *                     4:C -> deleteAt(3:R, 2)
     *                            3:R -> deleteAt(2:A, 2)
     *                                   返回 1:E (x->next)
     *                            返回 3:R (x)
     *                     返回 4:C (x)
     *            返回 first 
     *                            
     */
    public function delete($key): void
    {
        $this->first = deleteAt($this->first, $key);
    }

    private function deleteAt($x, $key)
    {
        if ($x == null) return null;
        if ($key == $x->key) {
            return $x->next;
        }
        // 如果递归中的下个结点删除成功, x->next 即为被删除结点的 next 结点
        $x->next = $this->deleteAt($x->next, $key);
        // 否则仍是 x->next 结点
        return $x;
    }

    public function contains($key)
    {
        return $this->get($key) !== null;
    }

    /**
     * 返回表中所有键的可遍历集合
     */
    public function keys()
    {
        // 因为 Queue 本身已实现 Iterator, 直接用 Queue 实现可遍历
        $queue = new Queue();
        for ($x = $this->first; $x != null; $x = $x->next)
            $queue->enqueue($x->key);
        return $queue;
    }

    /**
     * 标准索引用例
     *
     * % php SequentialSearchST.php
     * S E A R C H E X A M P L E
     * L 11
     * P 10
     * M 9
     * X 7
     * H 5
     * C 4
     * R 3
     * A 8
     * E 12
     * S 0
     */
    public static function main(array $args): void
    {
        $st = new self();
        for ($i = 0; !StdIn::isEmpty(); $i++) {
            $key = StdIn::readString();
            if ($key == null) break;
            $st->put($key, $i);
        }
        foreach ($st->keys() as $s) {
            StdOut::println("$s {$st->get($s)}");
        }
    }
}

class STNode
{
    public $key;
    public $val;
    public $next;

    public function __construct($key, $val, $next)
    {
        $this->key = $key;
        $this->val = $val;
        $this->next = $next;
    }
}