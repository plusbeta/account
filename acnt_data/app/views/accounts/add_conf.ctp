<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Account',array('action' => 'add','name'=>'account'));
	echo $form->hidden('id');
	echo $form->hidden('mode',array('value'=>'save'));
	echo $form->hidden('contract_price');
	echo $form->hidden('account_no');
	echo $form->hidden('client_id');
	echo $form->hidden('created');
?>
	<div id="page_title">内容確認</div>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">会社名</th>
				<td colspan="3"><?php echo $account['Client']['name']; ?><span style="color:red;"><?php echo $account['Errors']['client_id']; ?></span></td>
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
				<td><?php echo $account['Contractor'][0]['name'] ."<br />".$account['Contractor'][0]['price']."円"; ?></td>
				<th>外注先２（発注額）</th>
				<td><?php echo $account['Contractor'][1]['name'] ."<br />".$account['Contractor'][1]['price']."円"; ?></td>
			</tr>
			<tr>
				<th>案件名</th>
				<td colspan="3"><?php echo $account['Account']['name']; ?><span style="color:red;"><?php echo $account['Errors']['name']; ?></span></td>
			</tr>
		</table>
		<br />
		<table>
			<tr>
				<th style="width:150px">その他設定</th>
				<td style="width:600px">
				<?php echo $account['Account']['temporary_t']." <br />".$account['Account']['only_estimate_t']." <br />".$account['Account']['disp_member_t']; ?>
				</td>
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
		if(!empty($account['Item'][$i])){
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
				<td><?php echo $account['Account']['Total']; ?></td>
			</tr>
		</table>
	</div>
	<div id="other">
		<h2>その他情報</h2>
		<table>
			<tr>
				<th style="width:150px">振込先</th>
				<td style="width:650px"><?php echo $account['Bank']['name'] ?></td>
			</tr>
			<tr>
				<th>見積有効期限</th>
				<td>
<?php
	echo $account['Account']['estimate_limit']['year']."年";
	echo $account['Account']['estimate_limit']['month']."月";
	echo $account['Account']['estimate_limit']['day']."日";
?>
				</td>
			</tr>
			<tr>
				<th>見積日付</th>
				<td>
<?php
	echo $account['Account']['estimate_date']['year']."年";
	echo $account['Account']['estimate_date']['month']."月";
	echo $account['Account']['estimate_date']['day']."日";
?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $account['Account']['estimate_flag_t']; ?>
				</td>
			</tr>
			<tr>
				<th>納品日付</th>
				<td>
<?php
	echo $account['Account']['delivery_date']['year']."年";
	echo $account['Account']['delivery_date']['month']."月";
	echo $account['Account']['delivery_date']['day']."日";
?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $account['Account']['delivery_flag_t']; ?>
			</td>
			</tr>
			<tr>
				<th>請求日付</th>
				<td>
<?php
	echo $account['Account']['bill_date']['year']."年";
	echo $account['Account']['bill_date']['month']."月";
	echo $account['Account']['bill_date']['day']."日";
?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $account['Account']['bill_flag_t']; ?>
				</td>
			</tr>
			<tr>
				<th>回収日付</th>
				<td> 
<?php
	echo $account['Account']['receive_date']['year']."年";
	echo $account['Account']['receive_date']['month']."月";
	echo $account['Account']['receive_date']['day']."日";
?>
&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $account['Account']['receive_flag_t']; ?>
				</td>
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
	<!--/other -->
	<div id="local_menu">
		<div class="menu_block">
			<h3>書類作成</h3>
			<ul>
				<li><a href="javascript:void(0);" onclick="window.open('<?php echo $this->data['Return']; ?>','_self');">戻る</a></li>
	<?php
		if($account['Errors']['hit']==0){
	?>
					<li><a href="javascript:void(0);" onclick="document.account.submit();">登録</a></li>
	<?php
		}
	?>
			</ul>
		</div>
	</div>	
	</form>
	</div>
<!-- /main --> 
