<?php
namespace Algs;

// 更改这句以测试不同的 ST 实现, 可能需要微调某些参数
// use Algs\BinarySearchST as ST;
use Algs\BST as ST;

/**
 * p.234
 *
 * 符号表性能测试用例: 统计标准输入中各个单词的出现频率, 将频率最高的单词打印出来
 */
class FrequencyCounter
{
    /**
     * 注意: 取决于不同的 ST 实现, 打印出来的单词可能不一样
     *
     * % php FrequencyCounter.php 1 < ../data/tinyTale.txt
     * of 10
     *
     * % php FrequencyCounter.php 8 < ../data/tale.txt
     * business 122
     *
     * % php FrequencyCounter.php 8 < ../data/leipzig100K.txt
     * government 2549
     */
    public static function main(array $args): void
    {
        $minlen = (int) $args[0]; // 最小键长
        $st = new ST();
        while (! StdIn::isEmpty()) {
            // 构造符号表并统计频率
            $word = StdIn::readString();
            if ($word == null) break;
            if (mb_strlen($word) < $minlen) continue;
            if (! $st->contains($word)) $st->put($word, 1);
            else $st->put($word, ($st->get($word) + 1));
        }

        // 找出出现频率最高的单词
        $max = " ";
        $st->put($max, 0);
        foreach ($st->keys($st->min(), $st->max()) as $word) {
            if ($st->get($word) > $st->get($max)) {
                $max = $word;
            }
        }
        StdOut::println("$max {$st->get($max)}");
    }
}