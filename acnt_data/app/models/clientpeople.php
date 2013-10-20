<?php
/*
	モデル 顧客情報（担当者レコード）
*/
class Client_people extends AppModel
{
	var $name = "Client_people";
	var $belongsTo = array('Client');
}
?>