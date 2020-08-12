<?php
namespace Algs;

/**
 * p.80
 *
 * Dijkstra 的双栈算术表达式求值算法
 *
 * 这段代码是一个简单的**解释器**, 它展示了一种重要的计算模型: 将一个字符串解释为一段程序并执行
 * 该程序的到结果.
 *
 * NOTE:
 * - 假设表达式没有省略任何括号
 * - 数字和字符均以空格字符相隔
 */
class Evaluate
{
    // % php Evaluate.php
    // ( 1 + ( ( 2 + 3 ) * ( 4 * 5 ) ) )
    // 101.0
    public static function main($args)
    {
        $ops = new Stack();
        $vals = new Stack();
        while (! StdIn::isEmpty()) {
            $s = StdIn::readString();
            if (is_null($s)) break;

            switch ($s) {
                case "(":
                    break;
                case "+":
                case "-":
                case "*":
                case "/":
                case "sqrt":
                    $ops->push($s);
                    break;
                case ")":
                    $op = $ops->pop();
                    $v = $vals->pop();
                    if ($op == "+")         $v = $vals->pop() + $v;
                    elseif ($op == "-")     $v = $vals->pop() - $v;
                    elseif ($op == "*")     $v = $vals->pop() * $v;
                    elseif ($op == "/")     $v = $vals->pop() / $v;
                    elseif ($op == "sqrt")  $v = sqrt($v);
                    $vals->push($v);
                    break;
                default:
                    $vals->push((double) $s);
                    break;
            }
        }
        StdOut::println($vals->pop());
    }
}