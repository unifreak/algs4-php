<?php
/**
 * java args 不包含脚本名
 */
global $argv;
$commandPath = array_shift($argv);

/**
 * 如果是单独调用的类文件, 自动运行 main 方法测试
 */
$runningFile = basename($_SERVER['PHP_SELF'], '.php');
$command = basename($commandPath, '.php');
$class = "Algs\\${command}";
if ($runningFile == $command && method_exists($class, "main")) {
    call_user_func([$class, 'main'], $argv);
}