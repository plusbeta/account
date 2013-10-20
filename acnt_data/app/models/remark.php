<?php
/*
	モデル 備考定型文情報
*/
class Remark extends AppModel
{
	var $name = "Remark";
	var $belongsTo = array('Setting');
	
}
?>