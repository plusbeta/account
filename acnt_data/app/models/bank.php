<?php
/*
	モデル 口座情報
*/
class Bank extends AppModel
{
	var $name = "Bank";
	var $belongsTo = array('Setting');
	
	function getBankName($id=null){
		$bank_name="";
		if($id){
			$data=$this->findById($id);
			if($data['Bank']['kind']=="1"){
				$kind_t="普通";
			}elseif($data['Bank']['kind']=="2"){
				$kind_t="当座";
			}elseif($data['Bank']['kind']=="3"){
				$kind_t="貯蓄";
			}
			$bank_name=$data['Bank']['name']." ".$kind_t." ".$data['Bank']['number'];
		}
		return $bank_name;
	}
}
?>