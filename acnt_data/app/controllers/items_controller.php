<?php
/* *****
	受注情報（詳細レコード）
***** */
class ItemsController extends AppController
{
	var $name = "Items";
	var $scaffold;
	
    /* ====================================================== */
	/* 詳細追加                                               */
    /* ====================================================== */
	function add(){
	
		if(empty($this->data)){
		//初期状態
		
		}else{
		//保存
			$this->Item->save($this->data['Item'],false);
		}
		$this->data['Item']['account_id']=$this->Session->read('account_id');
		$this->set('action', 'add');
		$this->render('item');
	}

    /* ====================================================== */
	/* 詳細編集                                               */
    /* ====================================================== */
	function edit($id = null){
		if (empty($this->data)) {
			$this->data = $this->Item->findById($id);
		} else {
			// Save logic goes here
			$this->Item->save($this->data['Item'],false);
		}
		$this->set('action', 'edit');
		$this->render('item');
	}

}

?>