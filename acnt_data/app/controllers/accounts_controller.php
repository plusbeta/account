<?php
/* *****
	受注情報
***** */
App::import('Vendor', 'tcpdf', array('file' => 'tcpdf' . DS . 'tcpdf.php'));
App::import('Vendor', 'eng', array('file' => 'tcpdf' . DS . 'config' . DS . 'lang' . DS . 'eng.php'));
App::import('Vendor', 'fpdi', array('file' => 'fpdi' . DS . 'fpdi.php'));

class AccountsController extends AppController
{
	var $name = "Accounts";
	var $scaffold;
	
	//ページ付け
    var $paginate = array(
    	'conditions' =>  array(),
        'limit' => PAGE_LIMIT,
        'order' => array(
            'Account.id' => 'desc'
        )


    );
 
    
    /* ====================================================== */
	/* 受注台帳一覧                                           */
    /* ====================================================== */
	function index($key = null,$order = null){
		$seach_arg=array();
		$seach_arg['word']=$this->Session->read('word');
		$seach_arg['year']=$this->Session->read('year');
		if($seach_arg['year']==''){
			//$seach_arg['year']=date('Y');
		}
		$seach_arg['month']=$this->Session->read('month');
		if($seach_arg['month']==''){
			//$seach_arg['month']=date('m');
		}
		$seach_arg['status']=$this->Session->read('status');
		$tmp_word="";
		if($seach_arg['word']=="all"){
			$tmp_word=$seach_arg['word'];
			$seach_arg['word']="";
		}
		$this->set("seach_arg",$seach_arg);

//
		if($tmp_word=="all"){
			$cond_name="";
		}else{
			$cond_name=array('or' => array(
		    			'Account.name LIKE ' => '%'.$tmp_word.'%',
		    			'Client.name LIKE ' => '%'.$tmp_word.'%'
		    			)
		    	);
		}
	    if($seach_arg['status']=="est"){
			$cond_sts=array('Account.estimate_flag' => 1);
	    }elseif($seach_arg['status']=="bill"){
			$cond_sts=array('Account.bill_flag' => 1);
	    }elseif($seach_arg['status']=="deli"){
			$cond_sts=array('Account.delivery_flag' => 1);
	    }elseif($seach_arg['status']=="recv"){
			$cond_sts=array('Account.receive_flag' => 1);
	    }else{
			$cond_sts="";
	    }
		if($seach_arg['year'] !=""){
			if($seach_arg['month'] !=""){
				$cond_created=array(
					'Account.created >='=>$seach_arg['year']."-".$seach_arg['month']."-01 00:00:00",
		    		'Account.created <'=>$seach_arg['year']."-".($seach_arg['month']+1)."-01 00:00:00");
		    }else{
		    	//month=all
				$cond_created=array(
					'Account.created >='=>$seach_arg['year']."-01-01 00:00:00",
		    		'Account.created <'=>$seach_arg['year']."-12-31 23:59:59");
		    }
		}else{
		    	//year=all
		    	$cond_created=array();
		}

//
		$account_list=$this->paginate('Account',
								    array(
						    		$cond_name,
			    					$cond_sts,
    								$cond_created
    							)
						);  //  ページ設定
		
		for($i=0;$i<count($account_list);$i++){
			$tmpDate=date_parse($account_list[$i]['Account']['created']);
			$account_list[$i]['Account']['created']=$tmpDate['year']."-".$tmpDate['month']."-".$tmpDate['day'];

		}


		$this->set("account_list",$account_list);
		

		/*
App::import('Helper', 'Paginator');
$paginator = new PaginatorHelper();
e($paginator->numbers());
*/
		return;	
	}
	
    /* ====================================================== */
	/* 受注詳細                                               */
    /* ====================================================== */
	function view($id = null){
		if(empty($id)){ $this->redirect('/accounts'); }

		$this->data=$this->Account->findById($id);
		//商品情報の位置調整と小計、消費税算出
		$this->data['Item']=$this->sortItems();
		$this->data['Account']['account_no_t']=$this->makeAccountNo();
		
		$tmpDate=date_parse($this->data['Account']['created']);
		$this->data['Account']['created_t']=$tmpDate['year']."年&nbsp;".$tmpDate['month']."月&nbsp;".$tmpDate['day']."日";
		
		//顧客担当
		if($this->data['Account']['client_people_id']!=""){
			$this->loadModel('ClientPerson');
			$client_people=$this->ClientPerson->findById($this->data['Account']['client_people_id']);
			$this->data['Client_person']['division']=$client_people['ClientPerson']['devision'];
			$this->data['Client_person']['name']=$client_people['ClientPerson']['name'];
		}else{
			$this->data['Client_person']['division']="";
			$this->data['Client_person']['name']="";
		}
		//自社担当２

		$members=$this->Account->Member->findById($this->data['Account']['sub_member_id']);
		$this->data['SubMember']['name']=$members['Member']['name'];

		// その他設定
		if($this->data['Account']['temporary']==1){
			$this->data['Account']['temporary_t']="概算見積書";
		}else{
			$this->data['Account']['temporary_t']="通常見積書";
		}
		if($this->data['Account']['only_estimate']==1){
			$this->data['Account']['only_estimate_t']="見積りのみ";
		}else{
			$this->data['Account']['only_estimate_t']="";
		}
		if($this->data['Account']['disp_member']==1){
			$this->data['Account']['disp_member_t']="自社担当者名を印字する";
		}else{
			$this->data['Account']['disp_member_t']="自社担当者名を印字しない";
		}
		
		// 各種ステータス
		if($this->data['Account']['estimate_flag']){
			$this->data['Account']['estimate_flag_t']="見積済";
		}else{
			$this->data['Account']['estimate_flag_t']="";
		}
		if($this->data['Account']['bill_flag']){
			$this->data['Account']['bill_flag_t']="請求済";
		}else{
			$this->data['Account']['bill_flag_t']="";
		}
		if($this->data['Account']['delivery_flag']){
			$this->data['Account']['delivery_flag_t']="納品済";
		}else{
			$this->data['Account']['delivery_flag_t']="";
		}
		if($this->data['Account']['receive_flag']){
			$this->data['Account']['receive_flag_t']="回収済";
		}else{
			$this->data['Account']['receive_flag_t']="";
		}
		//外注先リスト
		$arrCont=$this->contractorList();			
		//外注先
		for($i=0;$i<CONTRACTORS;$i++){
			if(!empty($this->data['Contractor'][$i]['client_id'])){
				$this->data['Contractor'][$i]['name']=$arrCont[$this->data['Contractor'][$i]['client_id']];
			}else{
				$this->data['Contractor'][$i]['name']="";
				$this->data['Contractor'][$i]['price']="";
			}
		}
		//商品情報　内容のみの対応
		for($i=0;$i<count($this->data['Item']);$i++){
			if($this->data['Item'][$i]['number']==0){$this->data['Item'][$i]['number']="";}
			if($this->data['Item'][$i]['unit_price']==0){$this->data['Item'][$i]['unit_price']="";}
			if($this->data['Item'][$i]['amount']==0){$this->data['Item'][$i]['amount']="";}
		}
		//口座情報
		$this->loadModel('Bank');
		$this->data['Bank']['name']=$this->Bank->getBankName($this->data['Account']['bank_id']);
		$this->set("account",$this->data);

	}
    /* ====================================================== */
	/* 受注追加                                               */
    /* ====================================================== */
	function add(){
		
		//外注先リスト
		$arrCont=$this->contractorList();
		
		if(empty($this->data['Account']['mode'])){
		//初期状態
			$conf=$this->Session->read('account');
			$this->Session->del('account');
			if(!empty($conf)){
				// 確認からの戻りの場合、セッションデータ利用
				$this->data=$conf;

			}else{
				$this->data=$this->initAccount();
			}
			
			$login=$this->Auth->user();
			$this->data['Account']['member_id']=$login['Member']['id'];
			
			$this->data['Account']['created']=date("Y-m-d H:i:s");
			//　作成日フォーマット
			$this->data['Account']['created_t']=$this->formatDate($this->data['Account']['created']);
			
			//その他情報
			$this->loadModel('Setting');
			$setting=$this->Setting->find('first');
			$this->data['Account']['terms1']=$setting['Setting']['terms1'];
			$this->data['Account']['terms2']=$setting['Setting']['terms2'];
			$this->data['Account']['terms3']=$setting['Setting']['terms3'];
			$this->data['Account']['terms4']=$setting['Setting']['terms4'];

			// 口座情報
			$banks=array();
			foreach($setting['Bank'] as $ent){
				$banks[$ent['id']]=$ent['name']." ".$ent['number'];
			}
			$this->set('banks',$banks);

			//有効期限
			$est_limit=time()+($setting['Setting']['estimate_limit']*24*60*60);
			//回収日
			$rsv_limit=time()+($setting['Setting']['receive_limit']*24*60*60);
			//各種日付
			$this->data['Account']['estimate_limit']=date('Y-m-d',$est_limit);
			$this->data['Account']['estimate_date']=date('Y-m-d');
			$this->data['Account']['bill_date']=date('Y-m-d');
			$this->data['Account']['delivery_date']=date('Y-m-d');
			$this->data['Account']['receive_date']=date('Y-m-d',$rsv_limit);
			
			//外注先テーブル
			$this->set('contractors',$arrCont);
			//担当者テーブル
			$members=$this->Account->Member->find('list');
			$this->set('members',$members);
			$page="add";
			$this->set("prePage","add");

		}elseif($this->data['Account']['mode']=='conf'){
		//確認画面

			$this->data['Errors']['hit']=0;
			if($this->data['Account']['client_id']==''){
				$this->data['Errors']['hit']=1;
				$this->data['Errors']['client_id']="顧客名を選択してください。";
				$this->data['Account']['account_no_t']="";
			}else{
				$this->data['Errors']['client_id']="";
				//顧客情報の取得
				$tmpClient=$this->Account->Client->findById($this->data['Account']['client_id']);
				$this->data['Client']=$tmpClient['Client'];
				//アカウントNO採番
				$this->data['Account']['account_no']=$this->getAccountNo($this->data['Account']['client_id']);
				$this->data['Account']['account_no_t']=$this->makeAccountNo();
			}
			if($this->data['Account']['name']==''){
				$this->data['Errors']['hit']=1;
				$this->data['Errors']['name']="案件名を入力してください。";
			}else{
				$this->data['Errors']['name']="";
			}
			
			//作成日フォーマット
			$this->data['Account']['created_t']=$this->formatDate($this->data['Account']['created']);
			// 顧客部署、担当
			if($this->data['Account']['client_people_id']!=""){
				$this->loadModel('ClientPerson');
				$client_people=$this->ClientPerson->findById($this->data['Account']['client_people_id']);
				$this->data['Client_person']['division']=$client_people['ClientPerson']['devision'];
				$this->data['Client_person']['name']=$client_people['ClientPerson']['name'];
			}else{
				$this->data['Client_person']['division']="";
				$this->data['Client_person']['name']="";
			
			}
			
			//外注先
			for($i=0;$i<count($this->data['Contractor']);$i++){
				if($this->data['Contractor'][$i]['client_id']!=""){
					$this->data['Contractor'][$i]['name']=$arrCont[$this->data['Contractor'][$i]['client_id']];
				}else{
					$this->data['Contractor'][$i]['name']="";
				}
			}
			
			// その他設定
			if($this->data['Account']['temporary']==1){
				$this->data['Account']['temporary_t']="概算見積書";
			}else{
				$this->data['Account']['temporary_t']="通常見積書";
			}
			if($this->data['Account']['only_estimate']==1){
				$this->data['Account']['only_estimate_t']="見積りのみ";
			}else{
				$this->data['Account']['only_estimate_t']="";
			}
			if($this->data['Account']['disp_member']==1){
				$this->data['Account']['disp_member_t']="自社担当者名を印字する";
			}else{
				$this->data['Account']['disp_member_t']="自社担当者名を印字しない";
			}
			
			//口座情報
			$this->loadModel('Bank');
			$this->data['Bank']['name']=$this->Bank->getBankName($this->data['Account']['bank_id']);
			//自社担当1
			$this->Account->Member->id=$this->data['Account']['member_id'];
			$this->data['Member']['name']=$this->Account->Member->field('name');
			//自社担当2
			$this->Account->Member->id=$this->data['Account']['sub_member_id'];
			$this->data['SubMember']['name']=$this->Account->Member->field('name');

			// 各種ステータス
			if($this->data['Account']['estimate_flag']){
				$this->data['Account']['estimate_flag_t']="見積済";
			}else{
				$this->data['Account']['estimate_flag_t']="";
			}
			if($this->data['Account']['bill_flag']){
				$this->data['Account']['bill_flag_t']="請求済";
			}else{
				$this->data['Account']['bill_flag_t']="";
			}
			if($this->data['Account']['delivery_flag']){
				$this->data['Account']['delivery_flag_t']="納品済";
			}else{
				$this->data['Account']['delivery_flag_t']="";
			}
			if($this->data['Account']['receive_flag']){
				$this->data['Account']['receive_flag_t']="回収済";
			}else{
				$this->data['Account']['receive_flag_t']="";
			}
			
			$this->Session->write('account',$this->data);

			$page="add_conf";
			// 新規/編集/複写画面への戻り先
			$this->data['Return']="/sys/accounts/".$this->params['form']['prePage']."/".$this->data['Account']['id'];

		}elseif($this->data['Account']['mode']=='save'){
		//登録
			$account=$this->Session->read('account');
			$this->Session->del('account');
			
			unset($account['Member']);
			unset($account['Client']);
			unset($account['Client_person']);

			//外注先チェック
			if($account['Contractor'][0]['client_id']==""){
				unset($account['Contractor'][0]);
			}
			if($account['Contractor'][1]['client_id']==""){
				unset($account['Contractor'][1]);
			}
			if(empty($account['Contractor'])){
				unset($account['Contractor']);
			}
			
			// content のみ入力対応
			$this->loadModel('Item');
			for($i=0;$i<count($account["Item"]);$i++){
				if($account["Item"][$i]["content"]!=null){
					if($account["Item"][$i]["number"]==null){$account["Item"][$i]["number"]=0;}
					if($account["Item"][$i]["unit_price"]==null){$account["Item"][$i]["unit_price"]=0;}
					if($account["Item"][$i]["amount"]==null){$account["Item"][$i]["amount"]=0;}
				}else{
			
				    $this->Item->delete($account["Item"][$i]["id"]);
				    unset($account["Item"][$i]);
				}
			}
			// ここまで
			if($this->Account->saveAll($account)){
				//セッションにフラッシュメッセージをセットしリダイレクトする
				$this->Session->setFlash("登録しました。");
				$this->redirect('/accounts/view/'.$this->Account->id);
			}else{
				
				$this->Session->setFlash("登録に失敗しました。");
				$this->redirect('/accounts');
			}
			
		}

		
		$this->set('members', $this->Account->Member->find('list'));
		$this->set('account',$this->data);
		$this->render($page);	
	}
    /* ====================================================== */
	/* 受注詳細                                               */
    /* ====================================================== */
	function edit($id = null){
		if(empty($id)){ $this->redirect('/accounts'); }
		//外注先リスト
		$arrCont=$this->contractorList();
		
		if(empty($this->data['Account']['mode'])){
		//初期
			$conf=$this->Session->read('account');
			$this->Session->del('account');
			if(!empty($conf)){
				// 確認からの戻りの場合、セッションデータ利用
				$this->data=$conf;
			}else{
				$this->data = $this->Account->findById($id);
				//商品情報の位置調整と小計、消費税算出
				$this->data['Item']=$this->sortItems();
			}		
			
			//作成日フォーマット
			$this->data['Account']['created_t']=$this->formatDate($this->data['Account']['created']);
			

			//その他情報
			$this->loadModel('Setting');
			$setting=$this->Setting->find('first');
			
			//外注先　　***** ループ数要注意
			for($i=0;$i<2;$i++){
				if(!empty($this->data['Contractor'][$i]['client_id'])){
					$this->data['Contractor'][$i]['name']=$arrCont[$this->data['Contractor'][$i]['client_id']];
				}else{
					$this->data['Contractor'][$i]['id']="";
					$this->data['Contractor'][$i]['client_id']="";
					$this->data['Contractor'][$i]['price']="";
				}
			}
			//商品情報　内容のみの対応
			for($i=0;$i<count($this->data['Item']);$i++){
				if($this->data['Item'][$i]['number']==0){$this->data['Item'][$i]['number']="";}
				if($this->data['Item'][$i]['unit_price']==0){$this->data['Item'][$i]['unit_price']="";}
				if($this->data['Item'][$i]['amount']==0){$this->data['Item'][$i]['amount']="";}
			}
			// 口座情報
			$banks=array();
			foreach($setting['Bank'] as $ent){
				if($ent['kind']=="1"){
					$kind_t="普通";
				}elseif($ent['kind']=="2"){
					$kind_t="当座";
				}elseif($ent['kind']=="3"){
					$kind_t="貯蓄";
				}
				$banks[$ent['id']]=$ent['name']." ".$kind_t." ".$ent['number'];
			}
			$this->set('banks',$banks);
			
			//外注先テーブル
			$this->set('contractors',$arrCont);
			
			//担当者テーブル
			$members=$this->Account->Member->find('list');
			$this->set('members',$members);

			$this->set('message', '');		
			$page="edit";
			$this->set("prePage","edit");
		}elseif($this->data['Account']['mode']=='conf'){
		//確認画面
		//** 確認画面以降は add へ
		}elseif($this->data['Account']['mode']=='save'){
		//登録
		//** 確認画面以降は add へ
		}
		$this->set('account',$this->data);
		$this->render($page);	
	}
	

    /* ====================================================== */
	/* 受注詳細の複写                                         */
    /* ====================================================== */
	function copy($id = null){

		//外注先リスト
		$arrCont=$this->contractorList();
		
		if(empty($this->data['Account']['mode'])){
		//初期
			$conf=$this->Session->read('account');

			if(!empty($conf)){
				// 確認からの戻りの場合、セッションデータ利用
				$this->data=$conf;
			}else{
				$this->data = $this->Account->findById($id);
				//商品情報の位置調整と小計、消費税算出
				$this->data['Item']=$this->sortItems();
				$this->data['Account']['original_id']=$this->data['Account']['id'];
			}
		
			
			// 各レコードID初期化
			$this->data['Account']['id']='';
			for($i=0;$i<count($this->data['Item']);$i++){
				$this->data['Item'][$i]['id']="";
			}
			//作成日初期化&フォーマット
			$this->data['Account']['created']=date("Y-m-d H:i:s");
			$this->data['Account']['created_t']=$this->formatDate($this->data['Account']['created']);
			//アカウントNo.（util.jsで設定）

			//その他情報
			$this->loadModel('Setting');
			$setting=$this->Setting->find('first');
			
			//外注先　　***** ループ数要注意
			for($i=0;$i<2;$i++){
				if(!empty($this->data['Contractor'][$i]['client_id'])){
					$this->data['Contractor'][$i]['name']=$arrCont[$this->data['Contractor'][$i]['client_id']];
				}else{
					$this->data['Contractor'][$i]['id']="";
					$this->data['Contractor'][$i]['client_id']="";
					$this->data['Contractor'][$i]['price']="";
				}
			}
			
			// その他設定
			$this->data['Account']['temporary']=0;
			$this->data['Account']['only_estimate']=0;
			$this->data['Account']['disp_member']=1;
			
			// 口座情報
			$banks=array();
			foreach($setting['Bank'] as $ent){
				$banks[$ent['id']]=$ent['name']." ".$ent['number'];
			}
			$this->set('banks',$banks);

			//有効期限
			$est_limit=time()+($setting['Setting']['estimate_limit']*24*60*60);
			//回収日
			$rsv_limit=time()+($setting['Setting']['receive_limit']*24*60*60);

			$this->data['Account']['estimate_limit']=date('Y-m-d',$est_limit);
			$this->data['Account']['estimate_date']=date('Y-m-d');
			$this->data['Account']['bill_date']=date('Y-m-d');
			$this->data['Account']['delivery_date']=date('Y-m-d');
			$this->data['Account']['receive_date']=date('Y-m-d',$rsv_limit);
			
			//日付フラグ
			$this->data['Account']['estimate_flag']=0;
			$this->data['Account']['bill_flag']=0;
			$this->data['Account']['delivery_flag']=0;
			$this->data['Account']['receive_flag']=0;
			
			
			//外注先テーブル
			$this->set('contractors',$arrCont);
			
			//担当者テーブル
			$members=$this->Account->Member->find('list');
			$this->set('members',$members);

			$this->set('message', '');		
			$page="copy";
			$this->set("prePage","copy");
		}elseif($this->data['Account']['mode']=='conf'){
		//確認画面
		//** 確認画面以降は add へ
		}elseif($this->data['Account']['mode']=='save'){
		//登録
		//** 確認画面以降は add へ
		}
		$this->set('account',$this->data);
		$this->render($page);	
	}

    /* ====================================================== */
	/* 受注詳細の削除                                         */
	/*   削除日を設定しレコードは残す                         */
    /* ====================================================== */
	function delete($id = null){
		if(empty($id)){ $this->redirect('/accounts'); }

		if ($id!="") {
			//受注レコード
			$this->Account->delete($id);
			//商品レコード
			$cond=array('Item.account_id'=>$id);
			$this->Account->Item->deleteAll($cond);
			//外注先レコード
			$cond=array('Contractor.account_id'=>$id);
			$this->Account->Contractor->deleteAll($cond);
			//$this->Account->Item->delete($id);
		}
		$this->Session->setFlash("削除しました。");
		$this->redirect('index');		
	}
    /* ====================================================== */
	/* PDF出力                                                */
    /* ====================================================== */
	function pdf($mode=null,$id=null){
		if(empty($id)){ $this->redirect('/accounts'); }
		// PDF出力設定
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		$this->data=$this->Account->findById($id);
		//商品情報の位置調整と小計、消費税算出
		$this->data['Item']=$this->sortItems();
		//商品情報　内容のみの対応
		for($i=0;$i<count($this->data['Item']);$i++){
			if($this->data['Item'][$i]['number']==0){$this->data['Item'][$i]['number']="";}
			if($this->data['Item'][$i]['unit_price']==0){$this->data['Item'][$i]['unit_price']="";}
			if($this->data['Item'][$i]['amount']==0){$this->data['Item'][$i]['amount']="";}
		}

		$this->data['Account']['account_no_t']=$this->makeAccountNo();
		

		$this->Account->id=$id;
		if($mode=="e"){
			$this->Account->saveField('estimate_flag',1);
			$prefix="estimate_";
			$tmpDate=date_parse($this->data['Account']['estimate_date']);
		}elseif($mode=="b"){
			$this->Account->saveField('bill_flag',1);
			$prefix="bill_";
			$tmpDate=date_parse($this->data['Account']['bill_date']);
		}elseif($mode=="d"){
			$this->Account->saveField('delivery_flag',1);
			$prefix="deliver_";
			$tmpDate=date_parse($this->data['Account']['delivery_date']);
		}elseif($mode=="r"){
			$this->Account->saveField('receive_flag',1);
			$prefix="check_";
			$tmpDate=date_parse($this->data['Account']['receive_date']);
		}
		$this->data['Account']['created_t']=$tmpDate['year']."年 ".$tmpDate['month']."月 ".$tmpDate['day']."日";
/*-----*/
		ini_set("memory_limit", "64M");
		// initiate FPDI
		$pdf =& new FPDI();
		$pdf->setPrintHeader(false); 
		$pdf->setPrintFooter(false); 
		// set the sourcefile
		if($mode=="e"){
			$pdf->setSourceFile(APP.'template/t_mitsu.pdf');
		}elseif($mode=="b"){
			$pdf->setSourceFile(APP.'template/t_sei.pdf');
		}elseif($mode=="d"){
			$pdf->setSourceFile(APP.'template/t_nou.pdf');
		}elseif($mode=="r"){
			$pdf->setSourceFile(APP.'template/t_ken.pdf');
		}
		// import page 1
		$tplIdx = $pdf->importPage(1);
		// add a page
		$pdf->AddPage();
		// use the imported page and place it at point 10,10 with a width of 100 mm
		$pdf->useTemplate($tplIdx);

		$pdf->SetTextColor(0,0,0);
			
		$pdf->SetFont('hiraminpro-w3', '', 14);
		
		if($mode=="r"){
			$pdf->SetXY(135, 47);
			$pdf->Write(0, $this->data['Client']['name']);
		}else{
			$pdf->SetXY(15, 47);
			$pdf->Write(0, $this->data['Client']['name']."  御中");
		}
		
		$pdf->SetFont('hirakakupro-w3', '', 14);
		if($mode=="d"){
			$pdf->SetXY(33, 93.5);
		}elseif($mode=="r"){
			$pdf->SetXY(33, 96);
		}else{
			$pdf->SetXY(33, 67);
		}
		//件名 UTFなので１文字が長さ３
		if(mb_strlen($this->data['Account']['name'])>120){
			if($mode=="d"){
				$pdf->SetXY(33, 93.5);
			}elseif($mode=="r"){
				$pdf->SetXY(33, 96);
			}else{
				$pdf->SetXY(33, 68);
			}
			$pdf->SetFont('hirakakupro-w3', '', 6);
		}elseif(mb_strlen($this->data['Account']['name'])>110){
			if($mode=="d"){
				$pdf->SetXY(33, 93.5);
			}elseif($mode=="r"){
				$pdf->SetXY(33, 96);
			}else{
				$pdf->SetXY(33, 68);
			}
			$pdf->SetFont('hirakakupro-w3', '', 7.5);
		}elseif(mb_strlen($this->data['Account']['name'])>90){
			if($mode=="d"){
				$pdf->SetXY(33, 93.5);
			}elseif($mode=="r"){
				$pdf->SetXY(33, 96);
			}else{
				$pdf->SetXY(33, 68);
			}
			$pdf->SetFont('hirakakupro-w3', '', 8);
		}elseif(mb_strlen($this->data['Account']['name'])>75){
			if($mode=="d"){
				$pdf->SetXY(33, 93.5);
			}elseif($mode=="r"){
				$pdf->SetXY(33, 96);
			}else{
				$pdf->SetXY(33, 68);
			}
			$pdf->SetFont('hirakakupro-w3', '', 10);
		}elseif(mb_strlen($this->data['Account']['name'])>60){
			if($mode=="d"){
				$pdf->SetXY(33, 93.5);
			}elseif($mode=="r"){
				$pdf->SetXY(33, 96);
			}else{
				$pdf->SetXY(33, 67);
			}
			$pdf->SetFont('hirakakupro-w3', '', 12);
		
		}
		$pdf->Write(0, $this->data['Account']['name']);
		
		// now write some text above the imported page
		$pdf->SetFont('hirakakupro-w3', '', 14);		
		if($mode=="e"){
			$pdf->SetFont('hirakakupro-w6', '', 20.5);
			if($this->data['Account']['temporary']==1){
				$pdf->SetXY(149, 24);
				$pdf->Write(0, "概算");
			}
		}
		
		$pdf->SetFont('hirakakupro-w3', '', 16);
		if(($mode=="e")||($mode=="b")){
			$pdf->SetXY(45, 86);
			$pdf->Cell(75, 5, "￥  ".number_format($this->data['Account']['contract_price']),0,0,"R");
			//$pdf->Write(0, "￥  ".number_format($this->data['Account']['contract_price']),null,0,"L");
		}
		
		$pdf->SetFont('hirakakupro-w3', '', 9);
		
		if(($mode=="e")||($mode=="b")){
			$pdf->SetXY(15, 29);
		}else{
			$pdf->SetXY(17, 29);
		}
		$pdf->Cell(36, 5, $this->data['Account']['created_t'],0,0,"L");
		if($mode!="r"){
			$pdf->SetXY(161.5, 35);
			$pdf->Cell(35, 5, $this->data['Account']['account_no_t'],0,0,"R");
		}
		if($this->data['Account']['disp_member']==1){
			if(($mode=="e")||($mode=="b")){
				$pdf->SetXY(161.5, 50);
				$this->data['Member']['name']=str_replace("　"," ",$this->data['Member']['name']);
				$pdf->Cell(35, 5, $this->data['Member']['name'],0,0,"R");
			}
		}

		if(($mode=="e")||($mode=="b")){
			$offsetY=106;
			$defaultHeight=5.93;
		}elseif($mode=="d"){
			$offsetY=119;
			$defaultHeight=5.85;
		}elseif($mode=="r"){
			$offsetY=120.5;
			$defaultHeight=5.85;
		}
		$colMaxText=25;
		
		$itemHeight=$defaultHeight;
		$rowTop=$offsetY;
		$lineDec=0;
		$pdf->SetLineStyle(array("dash"=>"1","color"=>array(0,0,0)));
		
		for($i=0;$i<count($this->data['Item']);$i++){
			if($lineDec>0){
				if($this->data['Item'][$i]['amount']==""){
					$lineDec--;
					continue;
				}
			}
			if(mb_strlen($this->data['Item'][$i]['content'],'utf8')>$colMaxText){
				$itemHeight=$itemHeight+$defaultHeight;
				$lineDec++;
			}
			
				$this->data['Item'][$i]['name']=str_replace("　"," ",$this->data['Item'][$i]['name']);
			if(($mode=="e")||($mode=="b")){
				$pdf->SetXY(15.9, $rowTop);
				$pdf->MultiCell(30, $itemHeight, $this->data['Item'][$i]['name'],"B");
			}else{
				$pdf->SetXY(17, $rowTop);
				$pdf->MultiCell(29, $itemHeight, $this->data['Item'][$i]['name'],"B");
			}
			
			//$pdf->SetXY(45.9, ($itemHeight*$i)+$offsetY);
			$this->data['Item'][$i]['content']=str_replace("　"," ",$this->data['Item'][$i]['content']);
			if(($mode=="e")||($mode=="b")){
				$pdf->MultiCell(73, $itemHeight, $this->data['Item'][$i]['content'],"B","L",0,0,45.9, $rowTop,true,0,false,true);
			}else{
				$pdf->MultiCell(73, $itemHeight, $this->data['Item'][$i]['content'],"B","L",0,0,47, $rowTop,true,0,false,true);
			}
			$pdf->SetXY(118.9, $rowTop);
			if($this->data['Item'][$i]['number']==""){
				$this->data['Item'][$i]['number']="";
			}else{
				$this->data['Item'][$i]['number']=number_format($this->data['Item'][$i]['number'],1);
			}
			$pdf->MultiCell(13, $itemHeight, $this->data['Item'][$i]['number'],"B",0,"C");

			$pdf->SetXY(131.9, $rowTop);
			if($this->data['Item'][$i]['unit_price']==""){
				$this->data['Item'][$i]['unit_price']="";
			}else{
				$this->data['Item'][$i]['unit_price']=number_format($this->data['Item'][$i]['unit_price']);
			}
			$pdf->MultiCell(26, $itemHeight, $this->data['Item'][$i]['unit_price'],"B","R");

			$pdf->SetXY(157.9, $rowTop);
			if($this->data['Item'][$i]['amount']==""){
				$this->data['Item'][$i]['amount']="";
			}else{
				$this->data['Item'][$i]['amount']=number_format($this->data['Item'][$i]['amount']);
			}
			$pdf->MultiCell(38.5, $itemHeight, $this->data['Item'][$i]['amount'],"B","R");
		
			$rowTop+=$itemHeight;
			$itemHeight=$defaultHeight;
		}
		if(($mode=="e")||($mode=="b")){
			$offsetY=0;
		}elseif($mode=="d"){
			$offsetY=11.8;
		}elseif($mode=="r"){
			$offsetY=12.5;
		}

		$pdf->SetXY(157.9, 213+$offsetY);
		$this->data['Account']['subTotal']=number_format($this->data['Account']['subTotal']);
		$pdf->Cell(38.5, $itemHeight, "￥  ".$this->data['Account']['subTotal'],0,0,R);

		$pdf->SetXY(157.9, 219+$offsetY);
		$this->data['Account']['Tax']=number_format($this->data['Account']['Tax']);
		$pdf->Cell(38.5, $itemHeight, "￥  ".$this->data['Account']['Tax'],0,0,R);

		$pdf->SetXY(157.9, 225+$offsetY);
		$this->data['Account']['contract_price']=number_format($this->data['Account']['contract_price']);
		$pdf->Cell(38.5, $itemHeight, "￥  ".$this->data['Account']['contract_price'],0,0,R);

		if($mode=="e"){
			$pdf->SetXY(46, 237.5);
			$this->data['Account']['estimate_limit']=$this->data['Account']['estimate_limit'];
			$pdf->Write(0, $this->data['Account']['estimate_limit']);

			$pdf->SetXY(46, 243.5);
			$this->data['Account']['condition']=str_replace("　"," ",$this->data['Account']['condition']);
			$pdf->Write(0, $this->data['Account']['condition']);

			$pdf->SetXY(17, 249);
			$this->data['Account']['terms1']=str_replace("　"," ",$this->data['Account']['terms1']);
			//$pdf->Cell(180,$defaultHeight, $this->data['Account']['terms1']);
			//$pdf->Write(0, $this->data['Account']['terms1']);
			$arrTerm=array();
			$tmpTerm=explode("\n",$this->data['Account']['terms1']);
			foreach($tmpTerm as $e){
				while(mb_strlen($e)>0){
					if(mb_strlen($e)>63){
						array_push($arrTerm,mb_substr($e,0,56, 'UTF-8'));
						$e=mb_substr($e,63,(mb_strlen($e)-56), 'UTF-8');
					}else{
						array_push($arrTerm,$e);
						$e="";
					}
				}
			}
			for($i=0;$i<count($arrTerm);$i++){
				$pdf->MultiCell(180,$defaultHeight, $arrTerm[$i],0,"L",0,0,16,249+($i*$defaultHeight), true,0,false,false);
			}
		}elseif($mode=="b"){
						
			$this->data['Account']['terms2']=str_replace("　"," ",$this->data['Account']['terms2']);
			
			$arrTerm=array();
			$tmpTerm=explode("\n",$this->data['Account']['terms2']);
			foreach($tmpTerm as $e){
				while(mb_strlen($e)>0){
					if(mb_strlen($e)>63){
						array_push($arrTerm,mb_substr($e,0,63, 'UTF-8'));
						$e=mb_substr($e,63,(mb_strlen($e)-63), 'UTF-8');
					}else{
						array_push($arrTerm,$e);
						$e="";
					}
				}
			}
			for($i=0;$i<count($arrTerm);$i++){
				$pdf->MultiCell(180,$defaultHeight, $arrTerm[$i],0,"L",0,0,16,243.5+($i*$defaultHeight), true,0,false,false);
			}

			//口座情報
			$this->loadModel('Bank');
			$bank=$this->Bank->find('first',$this->data['Account']['bank_id']);
			$bankname=$this->Bank->getBankName($this->data['Account']['bank_id']);
			$pdf->SetXY(30, 266.5);
			//$pdf->Write(0, $bank['Bank']['name']." ".$bank['Bank']['number']);
			$pdf->Write(0, $bankname);
			
		
		}elseif($mode=="d"){
			$this->data['Account']['terms3']=str_replace("　"," ",$this->data['Account']['terms3']);
			$pdf->MultiCell(180,$defaultHeight, $this->data['Account']['terms3'],0,"L",0,0,17, 253.5, true,0,false,false);
			
		}elseif($mode=="r"){
			$this->data['Account']['terms4']=str_replace("　"," ",$this->data['Account']['terms4']);
			$pdf->MultiCell(180,$defaultHeight, $this->data['Account']['terms4'],0,"L",0,0,17.5, 255.5, true,0,false,false);
			
		}
/*
// Debug Grid
$a = array ("width"=>"0.1"
	,"cap"=>"butt"
	,"join"=>"miter"
	,"dash"=>"2"
	,"phase"=>"0"
	,"color"=>array(255,0,0)); 
$pdf->SetLineStyle($a); 
for($i=5;$i<310;$i+=5){
$pdf->Line(0, $i, 300, $i); //ラインを引く
}
for($i=5;$i<310;$i+=5){
$pdf->Line($i, 0, $i,400 ); //ラインを引く
}
*/
		$pdf->Output($prefix.$this->data['Account']['account_no_t'].'.pdf', 'I');
	}
	function _pdf($mode=null,$id=null){
		// PDF出力設定
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		if (!empty($this->data)) {

		}
			
		$this->data=$this->Account->findById($id);
		$this->data['Account']['account_no_t']=$this->makeAccountNo();
		
		$this->loadModel('Setting');
		$setting=$this->Setting->find('first');
		if($this->data['Account']['temporary']){
			$setting['Setting']['estimate_title']=$setting['Setting']['estimate_prefix'].$setting['Setting']['estimate_title'];
		}
		$setting['Setting']['estimate_sentense']=str_replace("\r\n","<br />",$setting['Setting']['estimate_sentense']);
		$cond = array(
		        'conditions' => array('Item.account_id =' => $id),
		        'fields' => array('SUM(Item.amount) as num')
		);
		$no=$this->Account->Item->find('all',$cond);
		
		$this->data['Account']['sub_total']=$no[0][0]['num'];
		$this->data['Account']['tax']=$no[0][0]['num']*CONS_TAX;
		
		
		$this->set('pdf_data',$this->data);
		$this->set('items', $this->data['Item']);
		$this->set('setting', $setting);
		
		$this->Account->id=$id;
		if($mode=="e"){
			$this->Account->saveField('estimate_flag',1);
		}
		
/*-----*/
		//require_once(APP.'vendors/tcpdf/config/lang/eng.php');

		Configure::write("debug",0);
		$this->autoRender=false;
		$this->output = $this->render($mode);
		
		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
		$pdf->setPrintHeader(false); 
		$pdf->setPrintFooter(false); 
 
		// set font
		$pdf->SetFont('kozgopromedium', '', 9);

		// add a page
		$pdf->AddPage();

		// output the HTML content
		$pdf->writeHTML($this->output, true, 0, true, 0);

		// reset pointer to the last page
		//$pdf->lastPage();

		// ---------------------------------------------------------

		//Close and output PDF document
		$pdf->Output('estimate.pdf', 'I');
/*-----*/		
	
	}
    /* ====================================================== */
	/* サジェスト用リスト作成 (ajaxの応答                     */
    /* ====================================================== */
	function sglist(){
		$word=$this->params['url']['q'];
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		$this->loadModel('Client');
		
		if(empty($this->data)){
			$cond=array(
			        'conditions' => array('Account.name LIKE ' => '%'.$word.'%'),
			        'fields' => array('Account.name','Account.id')
			                         //   key            val          group
				);
			$account = $this->Account->find('list',$cond);

			$cond=array(
			        'conditions' => array('Client.name LIKE ' => '%'.$word.'%'),
			        'fields' => array('Client.name','Client.id')
			                         //   key            val          group
				);
			$client = $this->Client->find('list',$cond);

			$this->data=array_merge($account,$client);
			ksort($this->data);
		}
		$this->set('data', $this->data);
		$this->render('list');
	}
    /* ====================================================== */
	/* 案件一覧用リスト作成 (ajaxの応答                       */
    /* ====================================================== */
	function getlist(){
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		$word=$this->params['url']['w'];
		$year=$this->params['url']['y'];
		$month=$this->params['url']['m'];
		$status=$this->params['url']['s'];
		
		$this->Session->write('word',$word);
		$this->Session->write('year',$year);
		$this->Session->write('month',$month);
		$this->Session->write('status',$status);
		
		if($year !=""){
			if($month !=""){
				$cond_created=array(
					'Account.created >='=>$year."-".$month."-01 00:00:00",
		    		'Account.created <'=>$year."-".($month+1)."-01 00:00:00");
		    }else{
		    	//month=all
				$cond_created=array(
					'Account.created >='=>$year."-01-01 00:00:00",
		    		'Account.created <'=>$year."-12-31 23:59:59");
		    }
		}else{
		    	//year=all
		    	$cond_created=array();
		}
		if($word=="all"){
			$cond_name="";
		}else{
			$cond_name=array('or' => array(
		    			'Account.name LIKE ' => '%'.$word.'%',
		    			'Client.name LIKE ' => '%'.$word.'%'
		    			)
		    	);
		}
	    if($status=="est"){
			$cond_sts=array('Account.estimate_flag' => 1);
	    }elseif($status=="bill"){
			$cond_sts=array('Account.bill_flag' => 1);
	    }elseif($status=="deli"){
			$cond_sts=array('Account.delivery_flag' => 1);
	    }elseif($status=="recv"){
			$cond_sts=array('Account.receive_flag' => 1);
	    }else{
			$cond_sts="";
	    }
	    $cond = array(
	    	'conditions' => array(
	    		$cond_name,
	    		$cond_sts,
	    		$cond_created,
	    		),
	    		
	    	'fields' =>array(
	    		'Account.id','Account.name','Account.created','Account.bill_date','Account.contract_price',
	    		'Account.estimate_flag','Account.bill_flag','Account.delivery_flag','Account.receive_flag',
	    		'Client.name','Member.name'
	    	),
	        'order' => array(
	        	'Account.id' => 'desc'
	       		)
	    );
		$account=$this->Account->find('all',$cond);
	
		$values=array();
		foreach($account as $key =>$val){
			$tmpDate=date_parse($val['Account']['created']);
			$val['Account']['created']=$tmpDate['year']."-".$tmpDate['month']."-".$tmpDate['day'];
			// 各種ステータス
			if($val['Account']['estimate_flag']){
				$val['Account']['estimate_flag_t']="見積済";
			}else{
				$val['Account']['estimate_flag_t']="　　　";
			}
			if($val['Account']['bill_flag']){
				$val['Account']['bill_flag_t']="請求済";
			}else{
				$val['Account']['bill_flag_t']="　　　";
			}
			if($val['Account']['delivery_flag']){
				$val['Account']['delivery_flag_t']="納品済";
			}else{
				$val['Account']['delivery_flag_t']="　　　";
			}
			if($val['Account']['receive_flag']){
				$val['Account']['receive_flag_t']="回収済";
			}else{
				$val['Account']['receive_flag_t']="　　　";
			}

			array_push($values,	array(
					'id'=>$val['Account']['id'],
					'name'=>$val['Account']['name'],
					'created'=>$val['Account']['created'],
					'bill_date'=>$val['Account']['bill_date'],
					'contract_price'=>number_format($val['Account']['contract_price']),
					'cname'=>$val['Client']['name'],
					'mname'=>$val['Member']['name'],
					'e_flg'=>$val['Account']['estimate_flag_t'],
					'b_flg'=>$val['Account']['bill_flag_t'],
					'd_flg'=>$val['Account']['delivery_flag_t'],
					'r_flg'=>$val['Account']['receive_flag_t']
				)
			);
		}
		//pr($cond);
		$json = json_encode($values);

		header('Content-type: application/json');
		echo $json;

		
	}
    /* ====================================================== */
	/* 案件一覧用リスト作成 (ajaxの応答                       */
    /* ====================================================== */
	function dispPage(){
		$this->layout = 'plain';
		//$this->autoRender=false;
		Configure::write("debug",0);
		
		$seach_arg['word']=$this->Session->read('word');
		$seach_arg['year']=$this->Session->read('year');
		if($seach_arg['year']==''){
			$seach_arg['year']=date('Y');
		}
		$seach_arg['month']=$this->Session->read('month');
		if($seach_arg['month']==''){
			$seach_arg['month']=date('m');
		}
		
		$seach_arg['status']=$this->Session->read('status');
		$tmp_word="";
		if($seach_arg['word']=="all"){
			$tmp_word=$seach_arg['word'];
			$seach_arg['word']="";
		}
		$this->set("seach_arg",$seach_arg);

//
		if($tmp_word=="all"){
			$cond_name="";
		}else{
			$cond_name=array('or' => array(
		    			'Account.name LIKE ' => '%'.$tmp_word.'%',
		    			'Client.name LIKE ' => '%'.$tmp_word.'%'
		    			)
		    	);
		}
	    if($seach_arg['status']=="est"){
			$cond_sts=array('Account.estimate_flag' => 1);
	    }elseif($seach_arg['status']=="bill"){
			$cond_sts=array('Account.bill_flag' => 1);
	    }elseif($seach_arg['status']=="deli"){
			$cond_sts=array('Account.delivery_flag' => 1);
	    }elseif($seach_arg['status']=="recv"){
			$cond_sts=array('Account.receive_flag' => 1);
	    }else{
			$cond_sts="";
	    }

		$account_list=$this->paginate('Account',
								    array(
						    		$cond_name,
			    					$cond_sts,
    								'Account.created >=' => $seach_arg['year'].'-'.$seach_arg['month'].'-01 00:00:00',
    								'Account.created <' => $seach_arg['year'].'-'.($seach_arg['month']+1).'-01 00:00:00'
    							)
						);  //  ページ設定
		


		//$account_list=$this->paginate();
	}
    /* ====================================================== */
	/*   サブルーチン                                         */
    /* ====================================================== */
    //** ステータス　文字変換
	function sts2txt($status=null){
		if($status==1){
			return "見積";
		}elseif($status==2){
			return "納品";
		}elseif($status==3){
			return "請求";
		}elseif($status==4){
			return "領収";
		}else{
			return "登録";
		}
	}
   //** アカウントNo採番
	function getAccountNo($id=null,$cdate=null){
		if($cdate==''){
			$cdate=$this->data['Account']['created'];
		}
		if($id!=''){
			$tmpDate=date_parse($cdate);
			$dateMIN=$tmpDate['year'].'-'.sprintf("%02s",$tmpDate['month']).'-'.sprintf("%02s",$tmpDate['day']).' 00:00:00';
			$dateMAX=$tmpDate['year'].'-'.sprintf("%02s",$tmpDate['month']).'-'.sprintf("%02s",$tmpDate['day']).' 23:59:59';
			$cond = array(
			        'conditions' => array(
			    		'Account.client_id =' => $id,
			    		'Account.created >=' =>$dateMIN ,
			    		'Account.created <' =>$dateMAX ,
			    		'Account.deleted =' => '0000-00-00',
			    		'Account.account_no >' => 0),
			        'fields' => array('MAX(Account.account_no) as num'),
			        'order' => array('Account.account_no DESC'),
			    	'group' => array('Account.client_id')
			);
			$no=$this->Account->find('all',$cond);

			if(empty($no)){
			//初期
				return 1;
			}else{
			//採番
				return $no[0][0]['num']+1;
			
			}
		}
	
	}
    //** アカウントコード作成	
	function makeAccountNo($cid=null,$cdate=null,$accountNo=null){
		//TST2010040501
		if($cdate==''){
			$cdate=$this->data['Account']['created'];
		}
		if($cid==''){
			// 内部call
			$ccode=$this->data['Client']['code'];
		}else{
			//外部call
			$tmpClients=$this->Account->Client->findById($cid);
			$ccode=$tmpClients['Client']['code'];
		}
		if($accountNo==''){
			$accountNo=$this->data['Account']['account_no'];
		}
		$tmpDate=date_parse($cdate);

		return $ccode.$tmpDate['year'].sprintf("%02s",$tmpDate['month']).sprintf("%02s",$tmpDate['day']).sprintf("%02d",$accountNo);
	}

	//** アカウントNo.採番（JQuery call)
	function anum(){
		
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		$id=$this->params['url']['id'];
		$cdate=$this->params['url']['date'];
		
		$client=$this->Account->Client->findById($id);
		$div=array();
		$mem=array();
		for($i=0;$i<count($client['Client_person']);$i++){
			array_push($div,array($client['Client_person'][$i]['id'],$client['Client_person'][$i]['devision']));
			array_push($mem,array($client['Client_person'][$i]['id'],$client['Client_person'][$i]['name']));
		}
		
		
		$data=array();
		$data['no']=$this->getAccountNo($id,$cdate);
		$data['no_t']=$this->makeAccountNo($id,$cdate,$data['no']);
		
		
		$values = array(
				'no' => $data['no'], 
				'no_t' => $data['no_t'],
				'div' => $div,
				'mem' => $mem);
				
		$json = json_encode($values);

		header('Content-type: application/json');
		echo $json;

	}
	//** ステータス更新（JQuery call)
	function chgStatus(){
		
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		
		$id=$this->params['form']['id'];
		$flag=$this->params['form']['flg'];
		$status=$this->params['form']['sts'];
		
		$this->Account->id=$id;
		$this->Account->saveField($flag,$status);
		
		$json = json_encode(array("success"));
		header('Content-type: application/json');
		echo $json;

	}
	//** 外注先リスト
	function contractorList (){
		
		$arrCont=$this->Account->Client->findContractor();
		$contList=array();
		if(!empty($arrCont)){
			foreach($arrCont as $row){
				$contList[$row['Client']['id']]=$row['Client']['name'];
			}
		}
		return $contList;
	}
	
	//** 商品情報のソート
	function sortItems(){
		function cmp($a,$b){
		    if ($a['order'] == $b['order']) {
		        return 0;
		    }
		    return ($a['order'] < $b['order']) ? -1 : 1;
		}
		
		//$items=$this->data['Item'];
		
		
		$items=array();
		$subTotal=0;
		for($i=0;$i<PRODUCT_ROWS;$i++){
			//各項目初期化
			$items[$i]['order']=$i;
			$items[$i]['id']="";
			$items[$i]['name']="";
			$items[$i]['content']="";
			$items[$i]['number']="";
			$items[$i]['unit_price']="";
			$items[$i]['amount']="";
			// Itemから該当項目取得
			for($k=0;$k<count($this->data['Item']);$k++){
				if($this->data['Item'][$k]['order']==$i){
					$items[$i]=$this->data['Item'][$k];
					$subTotal+=$this->data['Item'][$k]['amount'];
				}
			}
		}
		uasort($items,'cmp');
		$this->data['Account']['subTotal']=$subTotal;
		$this->data['Account']['Tax']=round($subTotal*CONS_TAX,0);

		return $items;
	}
	//** 受注情報の初期化
	function initAccount(){
		$account=array();
		$account['Account']['client_id']="";
		$account['Account']['account_no_t']="";
		$account['Client']['name']='';
		
		$account['Account']['id']="";
		$account['Account']['contract_price']="";
		$account['Account']['account_no']="";
		$account['Account']['sub_member_id']="";
		$account['Account']['name']="";
		$account['Account']['bank_id']="";
		$account['Account']['condition']="";
		$account['Account']['client_people_id']="";
		$account['Account']['temporary']=0;
		$account['Account']['only_estimate']=0;
		$account['Account']['disp_member']=1;
		$account['Account']['estimate_flag']=0;
		$account['Account']['bill_flag']=0;
		$account['Account']['delivery_flag']=0;
		$account['Account']['receive_flag']=0;
		
		$account['Contractor'][0]['id']="";
		$account['Contractor'][0]['client_id']="";
		$account['Contractor'][0]['price']="";
		$account['Contractor'][1]['id']="";
		$account['Contractor'][1]['client_id']="";
		$account['Contractor'][1]['price']="";
		
		for($i=0;$i<PRODUCT_ROWS;$i++){
			$account['Item'][$i]['id']="";
			$account['Item'][$i]['order']=$i;
			$account['Item'][$i]['name']="";
			$account['Item'][$i]['content']="";
			$account['Item'][$i]['number']="";
			$account['Item'][$i]['unit_price']="";
			$account['Item'][$i]['amount']="";
		}
		$account['Item']['subTotal']="";
		$account['Item']['Tax']="";
		$account['Item']['Total']="";
		
		$account['Contractor'][0]['id']="";
		$account['Contractor'][0]['client_id']="";
		$account['Contractor'][0]['price']="";
		
		$account['Contractor'][1]['id']="";
		$account['Contractor'][1]['client_id']="";
		$account['Contractor'][1]['price']="";
		
		return $account;
	}
	//** 日付フォーマット
	function formatDate($d=null){
		$tmpDate=date_parse($d);
		return $tmpDate['year']."年&nbsp;".$tmpDate['month']."月&nbsp;".$tmpDate['day']."日";

	}
}

?>