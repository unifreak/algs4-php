<?php
/**
 * Java 中基本类型和对象都有一个默认的 hashCode() 实现, 这个实现会返回一个 32 位整数, 可以为负数
 * 这里的 Mock 则简单的模仿 Java 中 hashCode() 的行为, 为基本类型和其他类型提供默认的 hashCode() 实现,
 * 返回的是一个数值, 可为负, 但不一定是 32 位整数
 */
if (! function_exists('hashCode')) {
    function hashCode($x)
    {
        if (is_bool($x)) {
            return $x ? 0 : 1;
        }
        if (is_int($x)) {
            return $x;
        }
        if (is_float($x)) {
            return (int) ($x ^ ($x >> 32));
        }
        if (is_string($x)) {
            $hash = 0;
            for ($i = 0; $i < mb_strlen($x); $i++) {
                $hash = (31 * $hash + mb_ord(mb_substr($x, $i, 1)));
            }
            return $hash;
        }
        if (is_object($x)) {
            return hashCode(spl_object_hash($x));
        }
    }
}
