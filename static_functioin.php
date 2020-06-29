<?php
namespace Algs;

/**
 * p.13, t.1.1.5
 */

/**
 * 绝对值
 */
function abs($x)
{
    if ($x < 0) return -$x;
    return $x;
}

/**
 * 是否素数
 */
function isPrime($n)
{
    if ($n < 2) return false;
    for ($i = 2; $i * $i <= $n; $i++) {
        if ($n % $i == 0) return false;
    }
    return true;
}

/**
 * 牛顿迭代法求平方根
 *
 * @see <https://blog.csdn.net/chenrenxiang/article/details/78286599>
 */
function sqrt($c)
{
    if ($c < 0) return null;
    $t = $c;
    while (abs($t - $c/$t) > 1e-15 * $t) { // 即测误差: t^2 - c > 1e-15
        $t = ($c / $t + $t) / 2;
    }
    return $t;
}

/**
 * 直角三角形斜边长
 */
function hypotenuse($a, $b) {
    return sqrt($a*$a + $b*$b);
}

/**
 * 调和级数
 */
function H($n) {
    $sum = 0;
    for ($i = 1; $i <= $n; $i++) {
        $sum += 1 / $i;
    }
    return $sum;
}

// ===============================================================

dump("abs of -5: " . abs(-5));
dump("is prime: 5: " . isPrime(5));
dump("sqrt of 5: " . sqrt(5));
dump("H of 5: " . H(5));