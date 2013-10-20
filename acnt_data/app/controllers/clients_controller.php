<?php
/* *****
	顧客情報（企業レコード）
***** */

class ClientsController extends AppController
{
	var $name = "Clients";
	var $scaffold;
	
	//ページ付け
    var $paginate = array(
        'limit' => PAGE_LIMIT,
        'order' => array(
            'Client.name' => 'asc'
        )
    );
    
    /* ====================================================== */
	/* クライアント一覧                                       */
    /* ====================================================== */
    function index(){
		$client_list=$this->paginate('Client');  //  ページ設定

		for($i=0;$i<count($client_list);$i++){
			$tmpDate=date_parse($client_list[$i]['Client']['created']);
			$client_list[$i]['Client']['created']=$tmpDate['year']."-".$tmpDate['month']."-".$tmpDate['day'];
			if($client_list[$i]['Client']['type']==1){
				$client_list[$i]['Client']['type_t']="受注先";
			}else{
				$client_list[$i]['Client']['type_t']="外注先";
			}
			if(empty($client_list[$i]['Client_person'])){
				$client_list[$i]['Client_person'][0]['name']="";
			}
		
		}
		$this->set("client_list",$client_list);
		return;	
    }
    /* ====================================================== */
	/* クライアント情報詳細                                   */
    /* ====================================================== */
    function view($id=null){
		if(empty($id)){ $this->redirect('/clients'); }
		$this->data=$this->Client->findById($id);
		
		if($this->data['Client']['type']=="1"){
			$this->data['Client']['type_t']="受注先";
		}elseif($this->data['Client']['type']=="2"){
			$this->data['Client']['type_t']="外注先";
		}
		
		for($i=0;$i<count($this->data['Account']);$i++){
			$tmpDate=date_parse($this->data['Account'][$i]['created']);
			$this->data['Account'][$i]['created']=$tmpDate['year']."-".$tmpDate['month']."-".$tmpDate['day'];
			$this->data['Account'][$i]['contract_price']=number_format($this->data['Account'][$i]['contract_price']);
		}
		
		$this->set("client",$this->data);
    
    }
    /* ====================================================== */
	/* クライアント情報追加                                   */
    /* ====================================================== */
    function add(){
		if(empty($this->data['Client']['mode'])){
		//初期状態
			$conf=$this->Session->read('client');
			$this->Session->del('client');
			if(!empty($conf)){
				// 確認からの戻りの場合、セッションデータ利用
				$this->data=$conf;
			}else{
				$this->data=$this->initClient();
			}
			$page="add";
			$this->set("prePage","add");
		}elseif($this->data['Client']['mode']=='conf'){
		//確認画面

			$this->data['Errors']['hit']=0;
			if($this->data['Client']['code']==''){
				$this->data['Errors']['hit']=1;
				$this->data['Errors']['code']="クライアントコードを入力してください。";
			}else{
				$this->data['Errors']['code']="";
			}
			if($this->data['Client']['name']==''){
				$this->data['Errors']['hit']=1;
				$this->data['Errors']['name']="会社名を入力してください。";
			}else{
				$this->data['Errors']['name']="";
			}
			if($this->data['Client']['type']=="1"){
				$this->data['Client']['type_t']="受注先";
			}elseif($this->data['Client']['type']=="2"){
				$this->data['Client']['type_t']="外注先";
			}else{
				$this->data['Client']['type']="1";
				$this->data['Client']['type_t']="受注先";
			}
			$this->Session->write('client',$this->data);
			
			//削除対象の担当者データを表示しない（セッションには残す）
			if(!empty($this->data['Client_person'])){
				for($i=0;$i<count($this->data['Client_person']);$i++){
					if($this->data['Client_person'][$i]['name']==""){
						unset($this->data['Client_person'][$i]);
					}
				}
			}else{
				$this->data['Client_person']=array();
			}
			
			$page="add_conf";
			$this->data['Return']="/sys/clients/".$this->params['form']['prePage']."/".$this->data['Client']['id'];
		}elseif($this->data['Client']['mode']=='save'){
		//登録
			$client=$this->Session->read('client');
			$this->Session->del('client');

			//削除対象の担当者レコードを削除
			$this->loadModel('ClientPerson');
			for($i=0;$i<count($client['Client_person']);$i++){
				if($client['Client_person'][$i]['name']==""){
					$this->ClientPerson->delete($client['Client_person'][$i]['id']);
					unset($client['Client_person'][$i]);
				}
			}
			if($this->Client->saveAll($client)){
				//セッションにフラッシュメッセージをセットしリダイレクトする
				$this->Session->setFlash("登録しました。");
				$this->redirect('/clients/view/'.$this->Client->id);
			}else{
				
				$this->Session->setFlash("登録に失敗しました。");
				$this->redirect('/clients');
			}
		}
		

		$this->set('arrType',array("1"=>"受注先","2"=>"外注先"));
		$this->set('client',$this->data);
		$this->render($page);	
	}
    /* ====================================================== */
	/* クライアント情報詳細                                   */
    /* ====================================================== */
    function edit($id=null){
		if(empty($id)){ $this->redirect('/clients'); }
		if(empty($this->data['Client']['mode'])){
		//初期
			$conf=$this->Session->read('client');
			$this->Session->del('client');
			if(!empty($conf)){
				// 確認からの戻りの場合、セッションデータ利用
				$this->data=$conf;
			}else{
				$this->data = $this->Client->findById($id);

			}
			
			$page="edit";
			$this->set("prePage","edit");
		}elseif($this->data['Client']['mode']=='conf'){
		//確認画面
		//** 確認画面以降は add へ
		}elseif($this->data['Client']['mode']=='save'){
		//登録
		//** 確認画面以降は add へ
		}
		$this->set('arrType',array("1"=>"受注先","2"=>"外注先"));
		$this->set('client',$this->data);
		$this->render($page);	
    }
    /* ====================================================== */
	/* 受注詳細の削除                                         */
	/*   削除日を設定しレコードは残す                         */
    /* ====================================================== */
	function delete($id = null){
		if(empty($id)){ $this->redirect('/clients'); }

		if ($id!="") {
			//クライアントレコード
			$this->Client->delete($id);
			//担当者レコード
			$cond=array('ClientPerson.client_id'=>$id);
			$this->loadModel('ClientPerson');
			$this->ClientPerson->deleteAll($cond);
		}
		$this->Session->setFlash("削除しました。");
		$this->redirect('index');		
	}
    /* ====================================================== */
	/* クライアント情報 CSVダウンロード                       */
	/*                                                        */
    /* ====================================================== */
	function getCsv(){

		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);

		$types=array("1"=>"受注先","2"=>"外注先");
		
		$client_list=$this->Client->find('all');

		$data=array();
		array_push($data,array("コード","会社名","郵便番号","住所","電話番号","FAX番号","主なスタンス","その他備考"));
		for($i=0;$i<count($client_list);$i++){
			array_push($data,array(
				$client_list[$i]['Client']['code'],
				$client_list[$i]['Client']['name'],
				$client_list[$i]['Client']['zip'],
				$client_list[$i]['Client']['address'],
				$client_list[$i]['Client']['tel'],
				$client_list[$i]['Client']['fax'],
				$types[$client_list[$i]['Client']['type']],
				$client_list[$i]['Client']['remark']
			));
		}

		$this->CSVDownload($data);

	}
    /* ====================================================== */
	/* サジェスト用リスト作成 (ajaxの応答                     */
    /* ====================================================== */
	function sglist(){
		$word=$this->params['url']['q'];
		$this->layout = 'plain';
		$this->autoRender=false;
		Configure::write("debug",0);
		if(empty($this->data)){
			$cond=array(
			        'conditions' => array('Client.name LIKE ' => '%'.$word.'%'),
			        'fields' => array('Client.code','Client.name','Client.id')
			                         //   key            val          group
				);
			$this->data = $this->Client->find('list',$cond);
		}
		$this->set('data', $this->data);
		$this->render('list');
	}
	
    /* ====================================================== */
	/*   サブルーチン                                         */
    /* ====================================================== */
	//** クライアント情報の初期化
	function initClient(){
		$client=array();
		$client['Client']['id']="";
		$client['Client']['code']="";
		$client['Client']['name']="";
		$client['Client']['zip']="";
		$client['Client']['address']="";
		$client['Client']['tel']="";
		$client['Client']['fax']="";
		$client['Client']['type']="1";
		$client['Client']['remark']="";
	}

    function CSVDownload($data=null){

        for ($i = 0; $i < count($data); $i++){
            for ($j = 0; $j < count($data[$i]); $j++ ){
                if ( $j > 0 ) $outData .= ",";
                $outData .= "\"";
                $data[$i][$j]=mb_ereg_replace( "\r\n","",$data[$i][$j] );
                $outData .= mb_ereg_replace("<","＜",mb_ereg_replace( "\"","\"\"",$data[$i][$j] )) ."\"";
            }
            $outData .= "\n";
        }
        

        
        $file_name = date("YmdHis") .".csv";

        /* HTTPヘッダの出力 */
        Header("Content-disposition: attachment; filename=${file_name}");
        Header("Content-type: application/octet-stream; name=${file_name}");
        Header("Cache-Control: ");
        Header("Pragma: ");

        //if (mb_internal_encoding() == CHAR_CODE){
            $outData = mb_convert_encoding($outData,'SJIS-Win','UTF-8');
        //}

        /* データを出力 */
        echo $outData;
    }

}

?>