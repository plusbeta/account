<?php
/*
	モデル 受注情報（詳細レコード）
*/
class Item extends AppModel
{
	var $name = "Item";
	var $belongsTo = array('Account');
	
	var $validate = array(
		'amount'=>array(
			'rule' => VALID_NOT_EMPTY,
			'message'=>'計'
		)
	);
}
?>
