<?php
namespace Algs;

/**
 * p.50
 */

final class StringOps
{
    public static function isPalindrom($s)
    {
        $N = mb_strlen($s);
        for ($i = 0; $i < $N/2; $i++)
            if (charAt($s, $i) != charAt($s, $N-1-$i))
                return false;
        return true;
    }

    public static function basename($s)
    {
        // PHP's basename() need to specify extension
        $dot = mb_strpos($s, ".");
        return mb_substr($s, 0, $dot);
    }

    public static function extension($s)
    {
        $dot = mb_strpos($s, ".");
        return mb_substr($s, $dot+1);
    }

    /**
     * 检查数组中的字符串是否排序
     *
     * NOTE:
     * - 不支持多字节编码
     * - 检查是否从小到大排序
     */
    public static function isSorted(array $a)
    {
        for ($i = 1; $i < count($a); $i++) {
            if ($a[$i-1] > $a[$i])
                return false;
        }
        return true;
    }

    public static function main($args)
    {
        dump("is Palindrom HIHA: " . (int) self::isPalindrom('YES'));
        dump("is Palindrom 你好你: " . (int) self::isPalindrom('你好你'));
        dump("base name of something.txt.md: " . self::basename("something.txt.md"));
        dump("extension of something.txt.md: " . self::extension("something.txt.md"));
        dump("[a, b, c] is sorted: " . self::isSorted(['a', 'b', 'c']));
    }
}