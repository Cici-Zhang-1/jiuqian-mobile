<?php
var_dump($_SERVER);
var_dump($argc);
var_dump($argv);
$line = trim(fgets(STDIN)); // 从 STDIN 读取一行
echo $line;
fscanf(STDIN, "%d\n", $number); // 从 STDIN 读取数字
echo $number;
