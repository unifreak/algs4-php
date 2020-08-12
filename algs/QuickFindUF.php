<?php
namespace Algs;

/**
 * p.138
 *
 * 动态连通性问题的 quick-find 实现:
 * - 将每个 $id 中的触点对应的值当做连通分量
 * - find() 很快, 但是 union() 是平方级别, 无法处理大型问题
 *
 * 成本模型
 *   数组的访问次数
 * 命题
 *   - find() 只需访问数组一次
 *   - union() 访问数组次数在 (N+3)~(2N+1) 之间
 *     1. 调用两次 find(): 2
 *     2. 检查整个数组: N
 *     3. 改变其中 1~N-1 个值
 */
class QuickFindUF extends UF
{
    public function find($p)
    {
        return $this->id[$p];
    }

    public function union($p, $q)
    {
        $pID = $this->find($p);
        $qID = $this->find($q);

        if ($pID == $qID) {
            return;
        }

        for ($i = 0; $i < count($this->id); $i++) {
            if ($this->id[$i] == $pID) {
                $this->id[$i] = $qID;
            }
        }
        $this->count--;
    }
}