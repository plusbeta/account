<?php
/*
	モデル 顧客情報（企業レコード）
*/
class Client extends AppModel
{
	var $name = "Client";
	var $hasMany = array(
			'Client_person',
			'Account'
			);
	
	function findClient(){
		$cond=array(
            'fields' => array('Client.id','Client.name'),
            'conditions' => array('Client.type'=>1),
            'order' => array('Client.id'=>'ASC')
			);
		$arrCont=$this->Account->Client->find('all',$cond);
		
		return $arrCont;
	}
	function findContractor(){
		$cond=array(
            'fields' => array('Client.id','Client.name'),
            'conditions' => array('Client.type'=>2),
            'order' => array('Client.id'=>'ASC')
			);
		$arrCont=$this->Account->Client->find('all',$cond);
		
		return $arrCont;
	}
}
?>