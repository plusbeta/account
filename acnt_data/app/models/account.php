<?php
/*
	モデル 受注情報（基本レコード）
*/
class Account extends AppModel
{
	var $name = "Account";
	var $belongsTo = array('Client','Member');
	var $hasMany = array('Item','Contractor');

	var $validate = array(
		'name'=>array(
			'rule' => VALID_NOT_EMPTY,
			'message'=>'件名を入力してください。'
		),
		'member_id'=>array(
			'rule' => VALID_NOT_EMPTY,
			'message'=>'担当者を選択してください。'
		),
		'client_id'=>array(
			'rule' => VALID_NOT_EMPTY,
			'message'=>'顧客を選択してください。'
		)
	);
}
?>