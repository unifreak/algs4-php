<?php
namespace Algs;
use SGH\Comparable\Comparable;

/**
 * p.48, t.1.2.6
 */
class Transaction implements Comparable
{
    private $who;
    private $when;
    private $amount;

    public function __construct(string $transaction) {
        $a = preg_split("/[\s]+/", $transaction);
        $this->who = $a[0];
        $this->when = new Date($a[1]);
        $this->amount = (double) $a[2];
    }

    public function who(): string
    {
        return $this->who;
    }

    public function when(): Date
    {
        return $this->when;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function __toString() {
        return sprintf("%-10s %10s %8.2f", $this->who, $this->when, $this->amount);
    }

    public function compareTo($that)
    {
        if (! $that instanceof static) {
            throw new \LogicException(
                'You cannot compare sheep with the goat.'
            );
        }

        if ($this->amount() < $that->amount()) return -1;
        elseif ($this->amount() > $that->amount()) return 1;
        else return 0;
    }

    public static function main(array $args): void
    {
        $a = new Arr(Transaction::class, 4);
        $a[0] = new Transaction("Turing   6/17/1990  644.08");
        $a[1] = new Transaction("Tarjan   3/26/2002 4121.85");
        $a[2] = new Transaction("Knuth    6/14/1999  288.34");
        $a[3] = new Transaction("Dijkstra 8/22/2007 2678.40");

        for ($i = 0; $i < count($a); $i++)
            StdOut::println($a[$i]);
        StdOut::println();
    }
}
