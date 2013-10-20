<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Client',array('action' => 'add','name'=>'client'));
	echo $form->hidden('id',array('value'=>$client['Client']['id']));
	echo $form->hidden('mode',array('value'=>'conf'));
?>
	<input type="hidden" name="prePage" value="<?php echo $prePage; ?>" />
	<div id="page_title">編集</div>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">クライアントコード</th>
				<td><?php echo $form->input('code',array('label'=>'','value'=>$client['Client']['code'],'size'=>'20')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">会社名</th>
				<td><?php echo $form->input('name',array('label'=>'','value'=>$client['Client']['name'],'size'=>'80')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">郵便番号</th>
				<td><?php echo $form->input('zip',array('label'=>'','value'=>$client['Client']['zip'],'size'=>'20')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">住所</th>
				<td><?php echo $form->input('address',array('label'=>'','value'=>$client['Client']['address'],'size'=>'80')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">電話番号</th>
				<td><?php echo $form->input('tel',array('label'=>'','value'=>$client['Client']['tel'],'size'=>'20')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">FAX番号</th>
				<td><?php echo $form->input('fax',array('label'=>'','value'=>$client['Client']['fax'],'size'=>'20')); ?></td>
			</tr>
			<tr>
				<th style="width:150px">主なスタンス</th>
				<td><?php echo $form->select('type',$arrType,$client['Client']['type'],array(),'選択');?></td>
			</tr>
			<tr>
				<th style="width:150px">その他備考</th>
				<td><?php echo $form->input('remark',array('label'=>'','value'=>$client['Client']['remark'],'size'=>'80')); ?></td>
			</tr>
		</table>
	</div>
	<div id="item">
		<h2>担当者情報</h2>
		<table id="person">
			<tr>
				<th style="width:140px">部署</th>
				<th style="width:150px">担当者名</th>
				<th style="width:150px">担当者フリガナ</th>
				<th style="width:100px">肩書き</th>
				<th style="width:150px">Eメール</th>
				<th style="width:100px">電話番号</th>
			</tr>
<?php
	for($i=0;$i<count($client['Client_person']);$i++){
?>
			<tr>
				<td><?php echo $form->hidden('Client_person.'.$i.'.id',array('label'=>'','value'=>$client['Client_person'][$i]['id']));
						  echo $form->hidden('Client_person.'.$i.'.client_id',array('label'=>'','value'=>$client['Client_person'][$i]['client_id']));
	echo $form->input('Client_person.'.$i.'.devision',array('label'=>'','value'=>$client['Client_person'][$i]['devision'])); ?></td>
				<td><?php echo $form->input('Client_person.'.$i.'.name',array('label'=>'','value'=>$client['Client_person'][$i]['name'])); ?></td>
				<td><?php echo $form->input('Client_person.'.$i.'.kana',array('label'=>'','value'=>$client['Client_person'][$i]['kana'])); ?></td>
				<td><?php echo $form->input('Client_person.'.$i.'.title',array('label'=>'','value'=>$client['Client_person'][$i]['title'])); ?></td>
				<td><?php echo $form->input('Client_person.'.$i.'.email',array('label'=>'','value'=>$client['Client_person'][$i]['email'])); ?></td>
				<td><?php echo $form->input('Client_person.'.$i.'.tel',array('label'=>'','value'=>$client['Client_person'][$i]['tel'])); ?></td>
			</tr>
<?php

	}
?>	
		</table><a href="javascript:void(0);" onclick="addPersonRow()" >担当者を追加</a>
	</div>
	<div id="other">
	</div>
	
	<div id="local_menu">
		<div class="menu_block">
			<h3>クライアント作成</h3>
			<ul>
				<li><a href="/sys/clients/view/<?php echo $client['Client']['id']; ?>">詳細へ戻る</a></li>
				<li><a href="javascript:void(0);" onclick="document.client.submit();">確認</a></li>
			</ul>
		</div>

	</div></form>
	</div>
<!-- /main --> 
