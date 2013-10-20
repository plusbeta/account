<?php
/*
	モデル 設定情報
*/
class Setting extends AppModel
{
	var $name = "Setting";
	var $hasMany = array('Bank',"Remark");
	var $validate = array(
		'company_name'=>array(
			'rule' => VALID_NOT_EMPTY,
			'message'=>'会社名を入力してください。'
		)
	);
}
?>