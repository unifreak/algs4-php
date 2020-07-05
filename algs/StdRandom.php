<?php
namespace Algs;

/**
 * p.18, t.1.1.8
 */

final class StdRandom
{
    private static $seed;
    private static $inited = false;

    public static function init()
    {
        if (! self::$inited) {
            self::$seed = microtime(true);
            mt_srand(self::$seed);
            self::$inited == true;
        }
    }

    private function __construct() { }

    public static function setSeed($s)
    {
        self::$seed = $s;
        mt_srand(self::$seed);
    }

    public static function uniform(...$border)
    {
        $borderNum = count($border);
        switch ($borderNum) {
            case 0:
                return self::randomFloat();
            case 1:
                return mt_rand(0, $border[0] - 1);
                break;
            case 2:
                $low = $border[0];
                $high = $border[1];
                if (is_float($low)) {
                    return self::randomFloat($low, $high);
                }
                return mt_rand($low, $high-1);
            default:
                throw new \InvalidArgumentException("too many arguments, receive only one or two");
                break;
        }
    }

    public static function random(...$border): float
    {
        return self::uniform(...$border);
    }

    private static function randomFloat($low = 0.0, $high = 1.0)
    {
        $max = mt_getrandmax();
        return $low + mt_rand(0, $max-1) / mt_getrandmax() * ($high - $low);
    }

    public static function bernoulli($p) {
        if (!($p >= 0.0 && $p <= 1.0))
            throw new \InvalidArgumentException("probability p must be between 0.0 and 1.0: " + $p);
        return self::randomFloat() < $p;
    }

    public static function gaussian($m = 0, $s = 1)
    {
        do {
            $x = self::randomFloat(-1.0, 1.0);
            $y = self::randomFloat(-1.0, 1.0);
            $r = $x*$x + $y*$y;
        } while ($r >= 1 || $r == 0);
        return $m + $s * ($x * sqrt(-2 * log($r) / $r));
    }

    public static function discrete(array $probabilities)
    {
        $epsilon = 1.0E-14;
        $sum = 0.0;
        for ($i = 0; $i < count($probabilities); $i++) {
            if (!($probabilities[$i] >= 0.0))
                throw new \InvalidArgumentException(
                    "array entry " + $i + " must be nonnegative: " + $probabilities[$i]
                );
            $sum += $probabilities[$i];
        }
        if ($sum > 1.0 + $epsilon || $sum < 1.0 - $epsilon)
            throw new \InvalidArgumentException(
                "sum of array entries does not approximately equal 1.0: " . $sum
            );

        // the for loop may not return a value when both r is (nearly) 1.0 and when the
        // cumulative sum is less than 1.0 (as a result of floating-point roundoff error)
        while (true) {
            $r = self::randomFloat();
            $sum = 0.0;
            for ($i = 0; $i < count($probabilities); $i++) {
                $sum = $sum + $probabilities[$i];
                if ($sum > $r) {
                    return $i;
                }
            }
        }
    }

    public static function shuffle(array &$a)
    {
        $n = count($a);
        for ($i = 0; $i < $n; $i++) {
            $r = $i + self::uniform($n-$i);     // between i and n-1
            $temp = $a[$i];
            $a[$i] = $a[$r];
            $a[$r] = $temp;
        }
    }

    public static function main($args)
    {
        dump("uniform 5:" . StdRandom::uniform(5));
        dump("uniform 1,3:" . StdRandom::uniform(1, 3));
        dump("uniform 1.0, 3.5:" . StdRandom::uniform(1.0, 3.5));
        dump("bernoulli(0.7):" . StdRandom::bernoulli(0.7));
        dump("gaussian:" . StdRandom::gaussian());
        dump("discrete [0.1, 0.8, 0.1]:" . StdRandom::discrete([0.1, 0.2, 0.7]));
        $a = [1, 3, 5, 2];
        StdRandom::shuffle($a);
        dump($a);
    }
}

StdRandom::init();