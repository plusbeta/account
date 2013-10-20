<?php
/* *****
	設定情報（定型文レコード）
***** */

class RemarksController extends AppController
{
	var $name = "Remarks";
	var $scaffold;
	
    /* ====================================================== */
	/* 定型文一覧                                           */
    /* ====================================================== */
    function index(){
		$bank_reg=array();
		if(empty($this->data['Remark']['mode'])){
			//初期処理
			$remark_reg['Remark']['id']="";
			$remark_reg['Remark']['setting_id']="1";
			$remark_reg['Remark']['sentence']="";
		}elseif($this->data['Remark']['mode']=="reg"){
		//新規or更新
			$this->Remark->save($this->data);
			
			$remark_reg['Remark']['id']="";
			$remark_reg['Remark']['setting_id']="1";
			$remark_reg['Remark']['sentence']="";

			
		}elseif($this->data['Remark']['mode']=="edit"){
		//edit領域へデータ転送
			$remark_reg=$this->Remark->findById($this->data['Remark']['id']);
		}

		$remark_list=$this->Remark->find('all'); //定型文情報取得

		$remark_list['mode']="";
		$this->set("remark_list",$remark_list);
		$this->set("remark_reg",$remark_reg);
		return;	
    }

}

?>