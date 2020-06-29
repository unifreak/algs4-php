<?php
namespace Algs;

/**
 * p.1
 */

/**
 * 欧几里得算法 (也叫辗转相除法), 求最大公约数
 */
function gcd($p, $q) // gcd: Greatest Common Divisor
{
    if ($q == 0) return $p;
    $r = $p % $q;
    return gcd($q, $r);
}

// ===============================================================

dump("gcd of 10, 5 is " . gcd(10, 5));
dump("gcd of 5, 3 is " . gcd(5, 3));
