<!-- main -->  
	<div id="main">
	<?php $session->flash(); ?>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">会社名</th>
				<td colspan="3"><?php echo $account['Client']['name']; ?></td>
			</tr>
			<tr>
				<th>見積書番号</th>
				<td style="width:250px"><?php echo $account['Account']['account_no_t']; ?></td>
				<th style="width:150px">データ作成日</th>
				<td style="width:250px"><?php echo $account['Account']['created_t']; ?></td>
			</tr>
			<tr>
				<th>相手先部署名</th>
				<td><?php echo $account['Client_person']['division']; ?></td>
				<th>相手先担当者</th>
				<td><?php echo $account['Client_person']['name']; ?></td>
			</tr>
			<tr>
				<th>自社担当者１</th>
				<td><?php echo $account['Member']['name']; ?></td>
				<th>自社担当者２</th>
				<td><?php echo $account['SubMember']['name']; ?></td>
			</tr>
			<tr>
				<th>外注先１（発注額）</th>
				<td><?php echo $account['Contractor'][0]['name']."<br />". $account['Contractor'][0]['price']."円"; ?></td>
				<th>外注先２（発注額）</th>
				<td><?php echo $account['Contractor'][1]['name']."<br />". $account['Contractor'][1]['price']."円"; ?></td>
			</tr>
			<tr>
				<th>案件名</th>
				<td colspan="3"><?php echo $account['Account']['name']; ?></td>
			</tr>
		</table>
	</div>
	<div id="item">
		<h2>商品情報</h2>
		<table>
			<tr>
				<th style="width:150px">項目</th>
				<th style="width:500px">内容</th>
				<th style="width:50px">数量</th>
				<th style="width:50px">単価</th>
				<th style="width:50px">金額</th>
			</tr>
<?php
	for($i=0;$i<PRODUCT_ROWS;$i++){
?>
			<tr>
				<td><?php echo $account['Item'][$i]['name']; ?>&nbsp;</td>
				<td><?php echo $account['Item'][$i]['content']; ?>&nbsp;</td>
				<td><?php echo $account['Item'][$i]['number']; ?>&nbsp;</td>
				<td><?php echo $account['Item'][$i]['unit_price']; ?>&nbsp;</td>
				<td><?php echo $account['Item'][$i]['amount']; ?>&nbsp;</td>
			</tr>
<?php

	}
?>	
			<tr>
				<td colspan="2" rowspan="3"></td>
				<td colspan="2">小計</td>
				<td><?php echo $account['Account']['subTotal']; ?></td>
			</tr>
			<tr>
				<td colspan="2">消費税</td>
				<td><?php echo $account['Account']['Tax']; ?></td>
			</tr>
			<tr>
				<td colspan="2">合計</td>
				<td><?php echo $account['Account']['contract_price']; ?></td>
			</tr>
		</table>
	</div>
	<div id="other">
		<h2>その他情報</h2>
		<table>
			<tr>
				<th style="width:150px">振込先</th>
				<td style="width:650px"><?php echo $account['Bank']['name']; ?></td>
			</tr>
			<tr>
				<th>見積有効期限</th>
				<td><?php echo $account['Account']['estimate_limit']; ?></td>
			</tr>
			<tr>
				<th>見積日付</th>
				<td><?php echo $account['Account']['estimate_date']; ?></td>
			</tr>
			<tr>
				<th>納品日付</th>
				<td><?php echo $account['Account']['delivery_date']; ?></td>
			</tr>
			<tr>
				<th>請求日付</th>
				<td><?php echo $account['Account']['bill_date']; ?></td>
			</tr>
			<tr>
				<th>回収日付</th>
				<td><?php echo $account['Account']['receive_date']; ?></td>
			</tr>
			<tr>
				<th>支払条件</th>
				<td><?php echo $account['Account']['condition']; ?></td>
			</tr>
			<tr>
				<th>その他備考（見積書）</th>
				<td><?php echo $account['Account']['terms1']; ?></td>
			</tr>
			<tr>
				<th>その他備考（請求書）</th>
				<td><?php echo $account['Account']['terms2']; ?></td>
			</tr>
			<tr>
				<th>その他備考（検収書）</th>
				<td><?php echo $account['Account']['terms3']; ?></td>
			</tr>
			<tr>
				<th>その他備考（納品書）</th>
				<td><?php echo $account['Account']['terms4']; ?></td>
			</tr>
		</table>
	</div>
	
	<div id="local_menu"><a href="/sys/accounts/edit/<?php echo $account['Account']['id']; ?>">編集</a><br />
						<a href="javascript:void(0);" onclick="confirmEvent('copy','<?php echo $account['Account']['id']; ?>')">複写</a><br />
						<a href="javascript:void(0);" onclick="confirmEvent('delete','<?php echo $account['Account']['id']; ?>')">削除</a><br /></div>

	</div>
<!-- /main --> 
