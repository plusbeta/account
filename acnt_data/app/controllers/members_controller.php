<?php
/* *****
	担当者情報
***** */

class MembersController extends AppController
{
	var $name = "Members";
	var $scaffold;
	
	function beforeFilter() {
		$this->Auth->allow('add');
	}

    /* ====================================================== */
	/* メンバー情報一覧                                       */
    /* ====================================================== */
    function index(){
    	$member_list=$this->Member->find('all');
		$this->set("valid",array("無効","有効"));
		$this->set("member_list",$member_list);
	}
    /* ====================================================== */
	/* メンバー情報 新規作成                                  */
    /* ====================================================== */
	function add() {
	    if (!empty($this->data)) {
	            $chkMember=$this->Member->find('first',array(
	            	'conditions'=>array('Member.username'=>$this->data['Member']['username'])
	            	)
	            );
	            $error=0;
	             if(!empty($chkMember)){
	            		$error=1;
	                    $this->Session->setFlash(__('ユーザーIDが登録済みです。', true));
	             }
	             
	             if($error==0){
	            	$this->Member->create();
		            if ($this->Member->save($this->data)) {
		                    $this->Session->setFlash(__('登録しました', true));
		                    $this->redirect(array('action' => 'index'));
		            } else {
		                    $this->Session->setFlash(__('登録できませんでした', true));
		            }
		         }else{
		         	$this->data['Member']['password']="";
		         }
	    }else{
	    
	    	$this->data['Member']['name']="";
	    	$this->data['Member']['username']="";
	    	$this->data['Member']['password']="";
	    }
	    $this->set('member',$this->data);
	}
    /* ====================================================== */
	/* メンバー情報 編集                                      */
    /* ====================================================== */
	function edit($id=null) {
		if(empty($id)){$this->redirect(array('action' => 'index'));}
		
	    if (!empty($this->data)) {
	            $chkMember=$this->Member->find('first',array(
	            	'conditions'=>array('Member.username'=>$this->data['Member']['username'],
	            						'Member.id <>'=>$this->data['Member']['id']
	            	)
	            	)
	            );
	            $error=0;
	             if(!empty($chkMember)){
	             	 	
	            		$error=1;
	                    $this->Session->setFlash(__('ユーザーIDが登録済みです。', true));
	             }
	             if($error==0){
	             
		            $this->Member->create();
		            if ($this->Member->save($this->data)) {
		                    $this->Session->setFlash(__('更新しました', true));
		                    $this->redirect(array('action' => 'index'));
		            } else {
		                    $this->Session->setFlash(__('更新できませんでした', true));
		            }
		         }else{
		         	$this->data['Member']['password']="";
		         }
	    }else{
	    
			$this->data=$this->Member->findById($id);
			$this->data['Member']['password']="";
	    }
	    $this->set('member',$this->data);
	}

}

?>
