<?php
/*
	モデル 外注先データ
*/
class Contractor extends AppModel
{
	var $name = "Contractor";
	var $belongsTo = array('Account');
	
}
?>
