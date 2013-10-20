<?php
/* *****
	設定情報
***** */

class SettingsController extends AppController
{
	var $name = "Settings";
	var $scaffold;
	
	// 設定情報 新規作成/編集
	function edit() {
	    if (!empty($this->data)) {
	            $this->Setting->create();
	            if ($this->Setting->save($this->data)) {
	                    $this->Session->setFlash(__('保存しました。', true));
	                    //$this->redirect(array('action' => 'index'));
	            } else {
	                    $this->Session->setFlash(__('保存できませんでした。', true));
	            }
	    }else{
	    	//空のとき レコードがすでにあるか確認
	    	$this->data=$this->Setting->find('first');
	    	if(!empty($this->data)){
	    	  //レコードあり
	    	}else{
	    	  //レコードなし
	    	}
	    }

	    $this->render();
	}
	function getlist(){
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		$remarks=$this->Setting->Remark->find('all');
		pr($remarks);
		$values = array();
		foreach($remarks as $ent){
			array_push($values,array('id' => $ent['Remark']['id'],'sentence' => $ent['Remark']['sentence']));
		
		}
		/*$values = array(
				array('id' => 1,'sentence' => '定型文1'),
				array('id' => 2,'sentence' => '定型文2')
		
		);*/
				
		$json = json_encode($values);

		header('Content-type: application/json');
		echo $json;
	}
}

?>