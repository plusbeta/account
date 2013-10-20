<?php
/*
	モデル 担当者情報
*/
class Member extends AppModel
{
	var $name = "Member";
	var $hasMany = array('Account');
}
?>