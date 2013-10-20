<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Client',array('action' => 'add','name'=>'client'));
	echo $form->hidden('id');
	echo $form->hidden('mode',array('value'=>'save'));
?>
	<div id="page_title">内容確認</div>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">クライアントコード</th>
				<td><?php echo $client['Client']['code']; ?><span style="color:red;"><?php echo $client['Errors']['code']; ?></span></td>
			</tr>
			<tr>
				<th style="width:150px">会社名</th>
				<td><?php echo $client['Client']['name']; ?><span style="color:red;"><?php echo $client['Errors']['name']; ?></span></td>
			</tr>
			<tr>
				<th style="width:150px">郵便番号</th>
				<td><?php echo $client['Client']['zip']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">住所</th>
				<td><?php echo $client['Client']['address']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">電話番号</th>
				<td><?php echo $client['Client']['tel']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">FAX番号</th>
				<td><?php echo $client['Client']['fax']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">主なスタンス</th>
				<td><?php echo $client['Client']['type_t']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">その他備考</th>
				<td><?php echo $client['Client']['remark']; ?></td>
			</tr>
		</table>
	</div>
	<div id="item">
		<h2>担当者情報</h2>
		<table>
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
				<td><?php echo $client['Client_person'][$i]['devision']; ?>&nbsp;</td>
				<td><?php echo $client['Client_person'][$i]['name']; ?>&nbsp;</td>
				<td><?php echo $client['Client_person'][$i]['kana']; ?>&nbsp;</td>
				<td><?php echo $client['Client_person'][$i]['title']; ?>&nbsp;</td>
				<td><?php echo $client['Client_person'][$i]['email']; ?>&nbsp;</td>
				<td><?php echo $client['Client_person'][$i]['tel']; ?>&nbsp;</td>
			</tr>
<?php

	}
?>	
		</table>
	</div>
	<div id="other">
	</div>
	
	<div id="local_menu">
		<div class="menu_block">
			<h3>クライアント作成</h3>
			<ul>
				<li><a href="javascript:void(0);" onclick="window.open('<?php echo $this->data['Return']; ?>','_self');">戻る</a></li>
	<?php
		if($client['Errors']['hit']==0){
	?>
					<li><a href="javascript:void(0);" onclick="document.client.submit();">登録</a></li>
	<?php
		}
	?>
			</ul>
		</div>

	</div></form>
	</div>
<!-- /main --> 
