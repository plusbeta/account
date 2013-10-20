<?php
$values = array('no' => $anum['no'], 'no_t' => $anum['no_t']);

$json = json_encode($values);
// string '{"company":"\u30a6\u30ce\u30a6","name":"yamaoka"}' (length=49)

$values = json_decode($json);
e($anum['no_t']);
?>

