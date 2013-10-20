<?php
/* */
pr($data);

foreach($data as $key => $val){
	foreach($val as $k => $v){
		e($v.'|'.$k.'|'.$key."\n");
	}
}
?>