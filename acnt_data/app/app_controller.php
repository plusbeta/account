<?php
require_once(CONFIGS.'define.php');

class AppController extends Controller
{
var $helpers = array('Html','Ajax','Javascript');
	// Authコンポーネントを使用する
	var $components = array('Auth');
    
    function beforeFilter() {
    	$this->disableCache();
	    // モデルMemberにアサイン
	    $this->Auth->userModel = 'Member';
		$this->Auth->fields = array(
			'username' => 'username',
			'password' => 'password'
		);
		$this->Auth->authError = "ログインしてください。";
		$this->Auth->loginError = "ログインに失敗しました。";
		$this->Auth->authorize = "controller";
		// ログイン後処理を有効にする
		//$this->Auth->autoRedirect = false;
        $this->Auth->loginRedirect=array('controller' => 'accounts', 'action' => 'index');
		//$this->Session
		if($this->Auth){
			$data=$this->Auth->user();
			$this->set('username',$data['Member']['name']);
		}

    }
    function isAuthorized() {
    	return true;
    }
    function login() {
		// ログイン後処理（autoRedirect = falseのとき有効）
		// 受注台帳の画面へ
        //$this->redirect('/Orders');
    }
    function logout() {
        $this->redirect($this->Auth->logout());
    }

}
?>