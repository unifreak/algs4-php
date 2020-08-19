<?php
namespace Algs;

/**
 * p.276
 *
 * 红黑二叉查找树
 *
 * 某些操作可能会出现红色右链接或者两条连续的红链接, 但这些情况都会被_旋转操作_修复
 * - 一条红色的右链接需要被转化为左链接, 这个操作叫做_左旋转_ @see rotateLeft()
 * - 将一个红色左链接转换为一个红色右链接, 叫做_右旋转_ @see rotateRight()
 *
 * 旋转操作可以保持红黑树的四个重要性质:
 * - 有序性
 * - 完美平衡性
 * - 不存在两条连续的红链接
 * - 不存在红色的右链接
 *
 * 红黑树最吸引人的一点是它的实现中最复杂的代码仅限于 put() 和删除方法. 二叉查找树中的其他方法
 * 可以不作任何改动即可继续使用
 *
 * 命题 p.284 p.285
 * - G: 一颗大小为 N 的红黑树的高度不会超过 2lgN
 *   简略证明: 红黑树的最坏情况是它所对应的 2-3 树中构成最左边的路径结点全部都是 3-结点而其余
 *   均为 2-结点, 最左边的路径长度是只包含 2-结点的路径长度 (~lgN) 的两倍
 * - H: 一颗大小为 N 的红黑树中, 根结点到任意结点的平均长度为 ~1.00lgN
 * - I: 在一棵红黑树中, 以下操作在最坏情况下所需的时间是对数级别的: get(), put(), min(), max(),
 *   deleteMin(), deleteMax(), floor(), ceiling(), rank(), select() 和 range()
 *
 * 这是我们见过的第一种能够同时实现高效的查找, 插入和删除操作的符号表实现. 所有基于红黑树的符号表
 * 实现都能保证操作的运行时间为对数级别 (范围查找除外, 它所需的额外时间和返回的键的数量成正比).
 * 这个结论十分重要. 它意味着表的大小可能上千亿, 但我们仍能够确保在几十次比较之内就完成这些操作
 */
class RedBlackBST extends BST
{
    private const RED = true;
    private const BLACK = false;

    private $root; // 根结点

    /**
     * 结点和它的父结点之间的链接是否是红链接
     * 注意, 当提到一个结点的颜色时, 指的是指向该结点的链接的颜色, 反之亦然
     */
    private function isRed($x)
    {
        if ($x === null) return false;
        return $x->color == self::RED;
    }

    /**
     * 左旋转, 将红色右链接转换为红色左链接
     * 这个操作其实就是将两个键中的较小者作为根结点变为将较大者作为根结点
     *
     *
     *         E <- h                      S <- x
     *       /  \\                       //  \
     *     <E    S <- x     -->     h-> E    <S
     *         /   \                   / \
     *       E~S   >S                <E  E~S
     *
     * 这种简洁的代码是我们使用递归实现二叉查找树的各种方法的主要原因, 它使得旋转操作成为了
     * 普通插入操作的一个简单补充
     */
    private function rotateLeft($h)
    {
        $x = $h->right;
        $h->right = $x->left;
        $x->left = $h;
        $x->color = $h->color;
        $h->color = self::RED;
        $x->N = $h->N;
        $h->N = 1 + $this->sizeOf($h->left) + $this->sizeOf($h->right);
        return $x;
    }

    /**
     * 右旋转, 将红色右链接转换为红色左链接
     * 与左旋转类似
     */
    private function rotateRight($h)
    {
        $x = $h->left;
        $h->left = $x->right;
        $x->right = $h;
        $x->color = $h->color;
        $h->color = self::RED;
        $x->N = $h->N;
        $h->N = 1 + $this->sizeOf($h->right) + $this->sizeOf($h->left);
        return $x;
    }

    /**
     * 通过转换链接的颜色来分解 4-结点
     * 这项操作最重要的性质在于它和旋转操作一样是_局部变换_
     */
    private function flipColors($h)
    {
        $h->color = self::RED;
        $h->left->color = self::BLACK;
        $h->right->color = self::BLACK;
    }

    /**
     * 查找 key, 找到则更新其值, 否则为它新建一个结点
     * 这个方法是本书中最复杂的实现之一, 而红黑树的 deleteMin(), deleteMax() 和 delete() 更麻烦
     *
     * 以下这些转换是红黑树的动态变化的关键
     *
     * 1. 向单个 2-结点中插入新键 (2-结点的根结点)
     *   1.1 新键小于老键, 只需新增一个红色的结点
     *   1.2 新键大于老键, 会产生一条红色右链接, 需要左旋转修正
     *
     * 2. 向树底部的 2-结点插入新键 (2-结点的底部结点): 总是用红链接将新结点和它的父链接相连
     *   2.1 如果指向新结点的是父结点的左链接, 父结点就直接成为了一个 3-结点
     *   2.2 如果指向新结点的是父结点的右链接, 需要左旋转修正
     *
     * 3. 向一个双键树中插入新键 (3-结点的根结点), 分三种情况处理
     *   3.1 新键最大, 被连接到 3-结点的右链接, 产生两条红链接分别和较小和较大的结点相连:
     *       将两条链接的颜色都变黑, 得到一颗由三个结点组成, 高为 2 的平衡树 @see flipColors()
     *   3.2 新键最小, 被连接到 3-结点最左边的空链接, 产生两条连续的红链接:
     *       将上层的红链接右旋转, 得到情况 3.1 继续处理
     *   3.3 新键介于原树两个键之间, 又会产生两条连续的红链接:
     *       将下层红链接左旋转, 得到情况 3.2 继续处理
     *   注意, 这三种情况都会使根结点变为红色, 但根结点并非一个 3-结点的一部分. 因此在每次
     *   插入后都会将根节点设为黑色. 每当根结点由红变黑时, 树的黒链接高度就会加 1
     *
     * 4. 向树底部的 3-结点插入新键 (3-结点的底部结点): 按照情况 3 进行处理.
     *
     * 以上考虑的所有情况正是为了达成这个目标: 每次必要的旋转之后都会进行颜色转换, 这使中结点变红.
     * 在父结点看来, 处理这样一个红色结点的方式和处理一个新插入的红色结点完全相同, 即继续把红链接
     * 转移到中结点上去. 本质上就是实现了 2-3 树中的插入操作步骤: 要在一个 3-结点下插入新键, 先
     * 创建一个临时的 4-结点, 将其分解并将红链接由中间键传递给它的父结点. 重复这个过程, 直到遇到
     * 一个 2-结点或者根结点
     *
     * 总之, 只要谨慎地使用左旋转, 右旋转和颜色转换, 我们就能保证插入操作后红黑
     * 树和 2-3 树的一一对应关系. 在沿着插入点到根结点的路径向上移动时在所经过的每个结点中顺序完成
     * 以下**标准操作**, 就能完成插入操作:
     * 1. 如果右子结点是红色而左子结点是黑色, 进行左旋转
     * 2. 如果左子结点是红色且它的左子结点也是红色, 进行右旋转
     * 3. 如果左右子结点均为红色, 进行颜色转换
     */
    public function put($key, $val)
    {
        $this->root = $this->putTo($this->root, $key, $val);
        $this->root->color = self::BLACK;
    }

    /**
     * @see BST::putTo()
     *
     * 因为保持树的平衡性所需的操作是_由下向上_在每个所经过的结点中进行的, 将它们植入已有的
     * BST 实现十分简单, 只需要在递归调用后完成这些操作即可
     */
    private function putTo($h, $key, $val)
    {
        if ($h === null) { // 标准的插入操作, 和父结点用红链接相连
            return new RBTNode($key, $val, 1, self::RED);
        }

        if ($key < $h->key) $h->left = $this->putTo($h->left, $key, $val);
        elseif ($key > $h->key) $h->right = $this->putTo($h->right, $key, $val);
        else $h->val = $val;

        // 标准操作 1
        if ($this->isRed($h->right) && !$this->isRed($h->left)) $h = $this->rotateLeft($h);
        // 标准操作 2
        if ($this->isRed($h->left) && $this->isRed($h->left->left)) $h = $this->rotateRight($h);
        // 标准操作 3
        if ($this->isRed($h->left) && $this->isRed($h->right)) $this->flipColors($h);

        $h->N = $this->sizeOf($h->left) + $this->sizeOf($h->right) + 1;
        return $h;
    }

    public static function main(array $args): void
    {
        $st = new self();
        for ($i = 0; !StdIn::isEmpty(); $i++) {
            $key = StdIn::readString();
            if ($key == null) break;
            $st->put($key, $i);
        }

        // $st->deleteMin(); // 删除最小键 A
        // $st->deleteMax(); // 删除最大键 X
        // $st->delete('C'); // 删除一个无子结点的结点
        // $st->delete('R'); // 删除一个只有左链接的结点
        // $st->delete('H'); // 删除一个只有右链接的结点
        // $st->delete('M'); // 删除有两个链接的结点
        dump($st);

        // foreach ($st->keys($st->min(), $st->max()) as $s) {
        //     StdOut::println("$s {$st->get($s)}");
        // }
    }

    /**
     * @todo p.269
     */
    public function get($key)
    {

    }

    /**
     * @todo p.283, p.290, p.291
     */
    public function delete()
    {

    }
}

class RBTNode
{
    public $key; // 键
    public $val; // 关联的值
    public $left; // 左子树
    public $right; // 右子树
    public $N; // 子树中结点总数
    public $color; // 由其父结点指向它的链接的颜色

    public function __construct($key, $val, int $N, bool $color)
    {
        $this->key = $key;
        $this->val = $val;
        $this->N = $N;
        $this->color = $color;
    }
}

