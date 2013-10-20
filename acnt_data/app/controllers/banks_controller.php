<?php
/* *****
	設定情報（口座レコード）
***** */

class BanksController extends AppController
{
	var $name = "Banks";
	var $scaffold;
	
    /* ====================================================== */
	/* 口座情報一覧                                           */
    /* ====================================================== */
    function index(){
		$bank_reg=array();
		if(empty($this->data['Bank']['mode'])){
			//初期処理
			$bank_reg['Bank']['id']="";
			$bank_reg['Bank']['setting_id']="1";
			$bank_reg['Bank']['name']="";
			$bank_reg['Bank']['kind']="";
			$bank_reg['Bank']['number']="";
		}elseif($this->data['Bank']['mode']=="reg"){
		//新規or更新
			$this->Bank->save($this->data);
			
			$bank_reg['Bank']['id']="";
			$bank_reg['Bank']['setting_id']="1";
			$bank_reg['Bank']['name']="";
			$bank_reg['Bank']['kind']="";
			$bank_reg['Bank']['number']="";
			
		}elseif($this->data['Bank']['mode']=="edit"){
		//edit領域へデータ転送
			$bank_reg=$this->Bank->findById($this->data['Bank']['id']);
		}

		$bank_list=$this->Bank->find('all'); //口座情報取得
		for($i=0;$i<count($bank_list);$i++){
			if($bank_list[$i]['Bank']['kind']=="1"){
				$bank_list[$i]['Bank']['kind_t']="普通";
			}elseif($bank_list[$i]['Bank']['kind']=="2"){
				$bank_list[$i]['Bank']['kind_t']="当座";
			}elseif($bank_list[$i]['Bank']['kind']=="3"){
				$bank_list[$i]['Bank']['kind_t']="貯蓄";
			}
		}

		$bank_list['mode']="";
		$this->set("bank_list",$bank_list);
		$this->set("bank_reg",$bank_reg);
		return;	
    }
}

?>