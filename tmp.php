<?php
$data = [["id" => 1, "name" => 'Tom'], ["id" => 2, "name" => 'Fred']];
$name = "";
["id" => $id, "name" => &$name] = $data[1];

var_dump($id, $name);