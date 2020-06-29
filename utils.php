<?php

if (! function_exists('charAt')) {
    function charAt($string, $pos)
    {
        return mb_substr($string, $pos, 1);
    }
}

if (! function_exists('dump')) {
    function dump(...$vars)
    {
        foreach ($vars as $var) {
            if (is_array($var)) {
                dump_array($var);
            } else {
                print_r($var);
            }
            echo PHP_EOL;
        }
    }
}

if (! function_exists('dump_array')) {
    function dump_array($a, $level = 0)
    {
        echo "[" . PHP_EOL;

        $indent = "";
        for ($i = 0; $i < $level; $i++) {
            $indent .= "  ";
        }

        foreach ($a as $i => $v) {
            echo $indent . "  $i: ";
            if (is_array($v)) {
                dump_array($v, $level+1);
            } else {
                echo "$v" . PHP_EOL;
            }
        }
        echo $indent . "]" . PHP_EOL;
    }
}
