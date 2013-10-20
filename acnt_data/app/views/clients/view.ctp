<!-- main -->  
	<div id="main">
	<?php $session->flash(); ?>
	<div id="page_title">詳細表示</div>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">クライアントコード</th>
				<td><?php echo $client['Client']['code']; ?></td>
			</tr>
			<tr>
				<th style="width:150px">会社名</th>
				<td><?php echo $client['Client']['name']; ?></td>
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
		<h2>取引情報</h2>
		<table>
			<tr>
				<th style="width:70px">作成日</th>
				<th style="width:70px">請求日</th>
				<th>案件名</th>
				<th style="width:100px">請求金額</th>
			</tr>
<?php
	for($i=0;$i<count($client['Account']);$i++){
?>
			<tr>
				<td><?php echo $client['Account'][$i]['created']; ?></td>
				<td><?php echo $client['Account'][$i]['bill_date']; ?></td>
				<td><a href="<?php echo "/sys/accounts/view/".$client['Account'][$i]['id']; ?>"><?php echo $client['Account'][$i]['name']; ?></a></td>
				<td style="text-align:right">￥ <?php echo $client['Account'][$i]['contract_price']; ?></td>
			</tr>
<?php

	}
?>	
		</table>
	</div>
	
	<div id="local_menu">
		<div class="menu_block">
			<h3>クライアント作成</h3>
			<ul>
				<li><a href="/sys/clients/edit/<?php echo $client['Client']['id']; ?>">編集</a></li>
				<li><a href="javascript:void(0);" onclick="confirmClient('delete','<?php echo $client['Client']['id']; ?>')">削除</a><br /></li>
			</ul>
		</div>

	</div>
	</div>
<!-- /main --> 
