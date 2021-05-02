<?php
namespace Algs;

/**
 * p.252, p.253, p.258, p.259
 *
 * 基于二叉查找树的符号表
 *
 * 基本的二叉查找树的实现常常是非递归的 @see e.3.2.13, 这里使用递归是为了
 * 1. 便于理解代码的工作方式
 * 2. 为学习更复杂的算法做准备 @see RedBlackBST
 *
 * 理解递归细节:
 * 将递归调用前的代码想象成 "沿着树向下走": 它将给定的键和每个结点的键比较, 根据比较结果决定向左还是向右
 * 移动到下一个结点. 将递归调用后的代码想象成 "沿着树向上爬": 对于 get(), 对应于一系列的 return 语句;
 * 对于 put(), 意味着重置搜索路径上每个父结点指向子结点的链接, 并增加每个结点中的计数器的值
 *
 * 二叉查找树的运行时间取决于树的形状 (是否平衡), 而树的形状又取决于键被插入的先后顺序
 *
 * 二叉查找树和_快速排序_ (@see QuickSort) 几乎就是 "双胞胎": 树的根节点就是快速排序中的第一
 * 个切分元素, 而这对于所有的子树同样适用, 这和快速排序中对子数组的递归排序完全对应. 这使我们能够
 * 分析得到二叉查找树的一些性质
 *
 * 命题: @see p.255
 * - C: 由 N 个随机键构造的二叉查找树中, 查找命中平均所需的比较次数为 ~2lnN (约 1.39lgN)
 * - D: 由 N 个随机键构造的二叉查找树中, 插入操作和查找未命中平均所需比较次数为 ~2lnN (约 1.39lgN)
 * - E: 在一颗二叉查找树中, 所有操作在最坏情况下所需的时间都和树的高度成正比
 *
 * 改进思路
 * - 如果输入不是随机的 (按顺序或逆序插入), 可能导致树不平衡, 性能糟糕 --> 平衡二叉查找树
 */
class BST
{
    private $root; // 根结点

    public function size(): int
    {
        return $this->sizeOf($this->root);
    }

    protected function sizeOf($x): int
    {
        if ($x === null) return 0;
        else return $x->N;
    }

    /**
     * 查找键为 key 的结点值
     */
    public function get($key)
    {
        return $this->getFrom($this->root, $key);
    }

    /**
     * 在以 x 为根结点的子树中查找并返回 key 所对应的值
     *
     * 和二分查找中每次迭代之后查找区间就会减半一样, 不断向下查找, 当前结点所表示的子树的大小也在减小
     * 一次查找也就定义了树的一条 **路径**
     */
    private function getFrom($x, $key)
    {
        if ($x === null) return null; // 树为空, 找不到, 返回 null
        elseif ($key < $x->key) return $this->getFrom($x->left, $key);  // 较小, 转入左子树
        elseif ($key > $x->key) return $this->getFrom($x->right, $key); // 较大, 转入右子树
        else return $x->val;
    }

    /**
     * 查找 key, 找到则更新其值, 否则插入新的键值对
     */
    public function put($key, $val)
    {
        $this->root = $this->putTo($this->root, $key, $val);
    }


    /**
     * 如果 key 存在于以 x 为根结点的子树中则更新它的值, 否则将以 key 和 val 为键值对的新结点插入到该子树中
     *
     * 这段代码几乎和二分查找一样简单, 这种简洁性就是二叉查找树的重要特性之一
     * 另一个更重要的特性就是插入的实现难度和查找差不多
     */
    private function putTo($x, $key, $val)
    {
        // 找不到, 创建新结点
        if ($x === null) return (new BSTNode($key, $val, 1));

        // 继续在左右子树中查找
        if ($key < $x->key) $x->left = $this->putTo($x->left, $key, $val); // key 小, 转到左子树
        else if ($key > $x->key) $x->right = $this->putTo($x->right, $key, $val); // key 大, 转到右子树
        else $x->val = $val; // 找到, 更新其值

        // 计算结点数
        $x->N = $this->sizeOf($x->left) + $this->sizeOf($x->right) + 1;
        return $x;
    }

    public function contains($key): bool
    {
        return $this->get($key) !== null;
    }

    /**
     * 返回最小键
     */
    public function min()
    {
        return $this->minOf($this->root)->key;
    }

    private function minOf($x)
    {
        // 如果根结点左链接为空, 那么一颗二叉查找树中最小键就是根结点
        if ($x->left === null) return $x;
        // 如果左链接非空, 那么树中的最小键就是左子树中的最小键
        return $this->minOf($x->left);
    }

    /**
     * 返回最大键
     */
    public function max()
    {
        return $this->maxOf($this->root)->key;
    }

    private function maxOf($x)
    {
        // 如果根结点右链接为空, 那么一颗二叉查找树中最大键就是根结点
        if ($x->right === null) return $x;
        // 如果右链接非空, 那么树中的最大键就是右子树中的最大键
        return $this->maxOf($x->right);
    }

    /**
     * 向下取整: 小于等于 key 的最大键
     */
    public function floor($key)
    {
        $x = $this->floorOf($this->root, $key);
        if ($x === null) return null;
        return $x->key;
    }

    private function floorOf($x, $key)
    {
        if ($x === null) return null;
        if ($key == $x->key) return $x;
        // 如果 key 小于根结点, 那么小于等于 key 的最大键 floor(key) 在根结点的左子树中
        if ($key < $x->key) return $this->floorOf($x->left, $key);
        // 如果 key 大于根结点, 那么
        // - 只有当根结点右子树中存在小于等于 key 的结点时, 小于等于 key 的最大键才会出现在右树中
        $t = $this->floorOf($x->right, $key);
        if ($t !== null) return $t;
        // - 否则根结点就是小于等于 key 的最大键
        else return $x;
    }

    /**
     * 向下取整: 大于等于 key 的最小键
     */
    public function ceiling($key)
    {
        $x = $this->ceilingOf($this->root, $key);
        if ($x === null) return null;
        return $x->key;
    }

    private function ceilingOf($x, $key) // 将 floor 的 left 变成 right, > 和 < 互换
    {
        if ($x === null) return null;
        if ($key == $x->key) return $x;
        // 如果 key 大于根结点, 那么大于等于 key 的最小键 ceiling(key) 在根结点的右子树中
        if ($key > $x->key) return $this->ceilingOf($x->right, $key);
        // 如果 key 小于根结点, 那么
        // - 只有当根结点左子树中存在大于等于 key 的结点时, 大于等于 key 的最小键才会出现在左树中
        $t = $this->ceilingOf($x->left, $key);
        if ($t !== null) return $t;
        // - 否则根结点就是大于等于 key 的最小键
        else return $x;
    }

    /**
     * 返回排名为 k 的结点
     */
    public function select(int $k)
    {
        return selectIn($this->root, $k)->key;
    }

    private function selectIn($x, int $k)
    {
        if ($x === null) return null;
        $t = $this->sizeOf($x->left);
        // 如果左子树中的结点数 t 大于 k, 就继续 (递归地) 在左子树中查找排名为 k 的键
        if ($t > $k) return $this->selectIn($x->left, $k);
        // 如果左子树中的结点数 t 小于 k, 就 (递归地) 的在右树中查找排名为 k-t-1 的键
        elseif ($t < $k) return $this->selectIn($x->right, $k-$t-1);
        // 如果 t 等于 k, 就返回根节点中的键
        else return $x;
    }

    /**
     * 返回给定键的排名
     * rank() 是 select() 的逆方法
     */
    public function rank($key): int
    {
        return $this->rankFrom($key, $this->root);
    }

    private function rankIn($key, $x): int
    {
        if ($x === null) return 0;
        // 如果给定的键小于根结点, 返回该键在左子树中的排名 (递归计算)
        if ($key < $x->key) return $this->rankIn($key, $x->left);
        // 如果给定的键大于根结点, 返回 t+1(根结点) 加上它在右子树中的排名 (递归计算)
        elseif ($key > $x->key) return 1 + $this->sizeOf($x->left) + $this->rankIn($key, $x->right);
        // 如果给定的键和根结点的键相等, 返回左子树中的结点总数
        else return $this->sizeOf($x->left);
    }

    /**
     * 删除最小键
     */
    public function deleteMin()
    {
        $this->root = $this->deleteMinFrom($this->root);
    }

    /**
     *         S                 S
     *        / \               / |
     *       E  X      变为    E   X
     *      / \              / |
     *     A  R             C  R
     *      \
     *       C
     */
    private function deleteMinFrom($x)
    {
        // 不断检索左子树直至遇见空的左链接, 此即子树中的最小结点
        // 返回该结点的右链接, 以让父结点的左链接指向被删除的最小结点的右子树
        // 左链接则会被当做垃圾回收 (删除)
        if ($x->left === null) return $x->right;
        // 递归返回的右链接则作为父结点的新左链接
        $x->left = $this->deleteMinFrom($x->left);
        // 递归调用后更新链接和结点计数器
        $x->N = $this->sizeOf($x->left) + $this->sizeOf($x->right) + 1;
        return $x;
    }

    /**
     * 删除最大键
     */
    public function deleteMax()
    {
        $this->root = $this->deleteMaxFrom($this->root);
    }

    private function deleteMaxFrom($x)
    {
        // 不断检索右子树直至遇见空的右链接, 此即子树的最大结点
        // 返回该结点的左链接. 以让父结点的右链接指向被删除的最大结点的左子树
        // 右链接会被当做垃圾回收 (删除)
        if ($x->right === null) return $x->left;
        // 递归返回的左链接则作为父结点的新右链接
        $x->right = $this->deleteMaxFrom($x->right);
        // 递归调用后更新链接和结点计数器
        $x->N = $this->sizeOf($x->left) + $this->sizeOf($x->right) + 1;
        return $x;
    }

    /**
     * 删除指定键
     *
     * 二叉查找树中最难实现的方法. 删除的处理分两种情况
     * - 删除只有一个子结点或没有子结点的结点 --> 用类似 deleteMin() 的方式
     * - 删除有两个子结点的结点  --> T. Hibbard 在 1962 年提出的方法
     *   这个方法在删除结点 x 后用它的_后继结点_填补它的位置. 因为 x 有一个右子结点, 因此它的
     *   后继结点就是其右子树中的最小结点. 因为这个后继结点大于 x 左子树中所有结点, 且小于 x 右
     *   子树中所有结点, 故用其替换 x 之后仍能保持有序性
     *
     *   它的缺点是可能会在实际应用中产生性能问题. 这个问题在于选用后继结点是一个随意的决定, 且
     *   没有考虑树的对称性
     */
    public function delete($key)
    {
        $this->root = $this->deleteFrom($this->root, $key);
    }

    private function deleteFrom($x, $key)
    {
        if ($x === null) return null;
        // 查找
        if ($key < $x->key) $x->left = $this->deleteFrom($x->left, $key);
        else if ($key > $x->key) $x->right = $this->deleteFrom($x->right, $key);
        // 找到, 进行删除
        else {
            // 删除只有一个子结点或没有子结点的结点: 使用类似 deleteMin() 和 deleteMax() 的方法
            if ($x->right === null) return $x->left;
            if ($x->left === null) return $x->right;
            // 删除有两个子结点的结点: Hibbard 方法 -- 用后继结点替补被删除结点位置
            //   1. 将指向即将被删除的结点的链接保存为 t
            $t = $x; // @?
            //   2. 将 x 指向它的后继结点 min(r->right)
            $x = $this->minOf($t->right);
            //   3. 将 x 的右链接指向 deleteMinFrom(t->right).
            //      这一步结束后, 后继结点从被删除结点的右子树中脱离(被删除)
            //      同时后继结点的右链接指向了被删除结点的右子树, 符合有序性
            $x->right = $this->deleteMinFrom($t->right);
            //   4. 将 x 的左链接设为 t->left,
            //      这一步结束后, 后继结点的左链接指向了被删除结点的左子树, 符合有序性
            $x->left = $t->left;
            //   替补完成
        }
        // 重新计算结点计数器
        $x->N = $this->sizeOf($x->left) + $this->sizeOf($x->right) + 1;
        return $x;
    }

    public function keys($lo, $hi)
    {
        $queue = new Queue();
        $this->collectKeys($this->root, $queue, $lo, $hi);
        return $queue;
    }

    private function collectKeys($x, Queue $queue, $lo, $hi)
    {
        // 使用中序遍历, 从小到大: 左 -> 根 -> 右
        if ($x === null) return;
        if ($lo < $x->key) $this->collectKeys($x->left, $queue, $lo, $hi);
        if ($lo <= $x->key && $hi >= $x->key) $queue->enqueue($x->key);
        if ($hi > $x->key) $this->collectKeys($x->right, $queue, $lo, $hi);
    }

    /**
     * % more ../data/tinyST.txt
     * S E A R C H E X A M P L E
     *
     * % php BST.php < ../data/tinyST.txt
     * E 12
     * L 11
     * P 10
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

        $st->deleteMin(); // 删除最小键 A
        $st->deleteMax(); // 删除最大键 X
        $st->delete('C'); // 删除一个无子结点的结点
        $st->delete('R'); // 删除一个只有左链接的结点
        $st->delete('H'); // 删除一个只有右链接的结点
        $st->delete('M'); // 删除有两个链接的结点
        foreach ($st->keys($st->min(), $st->max()) as $s) {
            StdOut::println("$s {$st->get($s)}");
        }
    }
}

// @todo Node Class Hierarchy: Node->STNOde->BSTNode?
class BSTNode
{
    public $key;   // 键
    public $val;   // 值
    public $left;  // 指向左子树的链接
    public $right; // 指向右子树的链接
    public $N;     // 结点计数器: 以该结点为根的子树中的结点总数

    public function __construct($key, $val, int $N)
    {
        $this->key = $key;
        $this->val = $val;
        $this->N = $N;
    }
}