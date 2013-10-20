<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Account',array('action' => 'add','name'=>'account'));
	echo $form->hidden('id',array('value'=>$account['Account']['id']));
	echo $form->hidden('mode',array('value'=>'conf'));
	echo $form->hidden('contract_price',array('value'=>$account['Account']['contract_price']));
	echo $form->hidden('account_no',array('value'=>$account['Account']['account_no']));
	echo $form->hidden('client_id',array('value'=>$account['Account']['client_id']));
	echo $form->hidden('created',array('value'=>$account['Account']['created']));
?>
	<input type="hidden" name="prePage" value="<?php echo $prePage; ?>" />
	<div id="page_title">新規登録</div>
	<div id="base">
		<h2>基本情報</h2>
		<table>
			<tr>
				<th style="width:150px">会社名</th>
				<td colspan="3">
					<input name="data[Client][name]" id="clientname" size="100" value="<?php echo $account['Client']['name']; ?>" />
					<?php echo $form->error('client_id'); ?></td>
			</tr>
			<tr>
				<th>見積書番号</th>
				<td style="width:250px"><?php echo $html->tag('div','',array('id'=>'AccountAccountNoT')); ?></td>
				<th style="width:150px">データ作成日</th>
				<td style="width:250px"><?php echo $account['Account']['created_t'];?></td>
			</tr>
			<tr>
				<th>相手先部署名</th>
				<td><input type="hidden" id="client_people_id" name="client_people_id" value="<?php echo $account['Account']['client_people_id']; ?>">
					<select id="divBox" name="data[Account][client_people_id]">
						<option value="">選択してください</option>
					</select></td>
				<th>相手先担当者</th>
				<td>
					<select id="memBox">
						<option value="">選択してください</option>
					</select>
				</td>
			</tr>
			<tr>
				<th>自社担当者１</th>
				<td><?php echo $form->input('member_id',array('value'=>$account['Account']['member_id'],'empty' => '選択','label'=>'')); ?></td>
				<th>自社担当者２</th>
				<td><?php echo $form->select('sub_member_id',$members,$account['Account']['sub_member_id'],array(),null); ?></td>
			</tr>
			<tr>
				<th>外注先１（発注額）</th>
				<td>
				<?php 
					echo $form->hidden('Contractor.0.id',array('value'=>$account['Contractor'][0]['id']));
					echo $form->select('Contractor.0.client_id',$contractors,$account['Contractor'][0]['client_id'],array(),'選択してください。'); 
				?><br />
					<input type="text" name="data[Contractor][0][price]" value="<?php echo $account['Contractor'][0]['price'] ?>" /> 円
				</td>
				<th>外注先２（発注額）</th>
				<td>
				<?php
					echo $form->hidden('Contractor.1.id',array('value'=>$account['Contractor'][1]['id']));
					echo $form->select('Contractor.1.client_id',$contractors,$account['Contractor'][1]['client_id'],array(),'選択してください。'); ?><br />
					<input type="text" name="data[Contractor][1][price]" value="<?php echo $account['Contractor'][1]['price'] ?>" /> 円
				</td>
			</tr>
			<tr>
				<th>案件名</th>
				<td colspan="3"><?php echo $form->input('name',array('value'=>$account['Account']['name'],'label'=>'','size'=>'100')); ?></td>
			</tr>
		</table>
		<br />
		<table>
			<tr>
				<th style="width:150px">その他設定</th>
				<td style="width:600px">
				<?php echo $form->input('temporary',array('value'=>$account['Account']['temporary'],'label'=>'概算（見積書タイトルに「概算」が付加されます。）')); 
						echo $form->input('only_estimate',array('value'=>$account['Account']['only_estimate'],'label'=>'見積りのみ')); 
					  echo $form->input('disp_member',array('value'=>$account['Account']['disp_member'],'label'=>'自社担当者を印字する')); ?>
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
?>
			<tr>
				<td>
<?php
	echo $form->input('Item.'.$i.'.id',array('type'=>'hidden','value'=>$account['Item'][$i]['id'],'label'=>''));
	echo $form->input('Item.'.$i.'.order',array('type'=>'hidden','value'=>$account['Item'][$i]['order'],'label'=>''));
	echo $form->input('Item.'.$i.'.name',array('value'=>$account['Item'][$i]['name'],'label'=>''));
?>
				</td>
				<td><?php echo $form->input('Item.'.$i.'.content',array('value'=>$account['Item'][$i]['content'],'label'=>'','size'=>'60')); ?></td>
				<td><?php echo $form->input('Item.'.$i.'.number',array('value'=>$account['Item'][$i]['number'],'label'=>'','size'=>'10')); ?></td>
				<td><?php echo $form->input('Item.'.$i.'.unit_price',array('value'=>$account['Item'][$i]['unit_price'],'label'=>'','size'=>'10')); ?></td>
				<td><?php echo $form->input('Item.'.$i.'.amount',array('value'=>$account['Item'][$i]['amount'],'label'=>'','class'=>'amount','size'=>'10')); ?></td>
			</tr>
<?php
	}
?>	
			<tr>
				<td colspan="2" rowspan="3" valign="top">管理進行費設定
				<select id="tax">
					<option value="">--</option>
					<option value="0.05">5%</option>
					<option value="0.1">10%</option>
					<option value="0.15">15%</option>
					<option value="0.2">20%</option>
				</select>
				</td>
				<td colspan="2">小計</td>
				<td><?php echo $form->input('subTotal',array('value'=>'','label'=>'','id'=>'subTotal','size'=>'10')); ?></td>
			</tr>
			<tr>
				<td colspan="2">消費税</td>
				<td><?php echo $form->input('Tax',array('value'=>'','label'=>'','id'=>'Tax','size'=>'10')); ?></td>
			</tr>
			<tr>
				<td colspan="2">合計</td>
				<td><?php echo $form->input('Total',array('value'=>'','label'=>'','id'=>'Total','size'=>'10')); ?></td>
			</tr>
		</table>
	</div>
	<div id="other">
		<h2>その他情報</h2>
		<table>
			<tr>
				<th style="width:150px">振込先</th>
				<td style="width:650px"><?php echo $form->select('bank_id',$banks,$account['Account']['bank_id'],array(),'選択'); ?></td>
			</tr>
			<tr>
				<th>見積有効期限</th>
				<td>
<?php
	echo $form->year('estimate_limit',date('Y') - MIN_YEAR,date('Y') + MAX_YEAR) ."年";
	echo $form->month('estimate_limit', null,array('value'=>'','monthNames' =>  false))."月";
	echo $form->day('estimate_limit')."日";
?>
			</td>
			</tr>
			<tr>
				<th>見積日付</th>
				<td>
<?php
	echo $form->year('estimate_date',date('Y') - MIN_YEAR,date('Y') + MAX_YEAR)."年";
	echo $form->month('estimate_date', null,array('value'=>'','monthNames' =>  false))."月";
	echo $form->day('estimate_date')."日&nbsp;&nbsp;&nbsp;&nbsp;";
	
	echo $form->hidden('estimate_flag',array('value'=>$account['Account']['estimate_flag']));
?>
				</td>
			</tr>
			<tr>
				<th>納品日付</th>
				<td>
<?php
	echo $form->year('delivery_date',date('Y') - MIN_YEAR,date('Y') + MAX_YEAR)."年";
	echo $form->month('delivery_date', null,array('monthNames' =>  false))."月";
	echo $form->day('delivery_date')."日&nbsp;&nbsp;&nbsp;&nbsp;";
	
	echo $form->hidden('delivery_flag',array('value'=>$account['Account']['delivery_flag']));
?>
				</td>
			</tr>
			<tr>
				<th>請求日付</th>
				<td>
<?php
	echo $form->year('bill_date',date('Y') - MIN_YEAR,date('Y') + MAX_YEAR)."年";
	echo $form->month('bill_date', null,array('monthNames' =>  false))."月";
	echo $form->day('bill_date')."日&nbsp;&nbsp;&nbsp;&nbsp;";

	echo $form->hidden('bill_flag',array('value'=>$account['Account']['bill_flag']));
?>
				</td>
			</tr>
			<tr>
				<th>回収日付</th>
				<td>
<?php
	echo $form->year('receive_date',date('Y') - MIN_YEAR,date('Y') + MAX_YEAR)."年";
	echo $form->month('receive_date', null,array('monthNames' =>  false))."月";
	echo $form->day('receive_date')."日&nbsp;&nbsp;&nbsp;&nbsp;";

	echo $form->hidden('receive_flag',array('value'=>$account['Account']['receive_flag']));
?>
				</td>
			</tr>
			<tr>
				<th>支払条件</th>
				<td><?php echo $form->input('condition',array('label'=>'','size'=>'50')); ?></td>
			</tr>
			<tr>
				<th>その他備考（見積書）</th>
				<td><?php echo $form->textarea('terms1',array('cols'=>'60','rows'=>'5')); ?></td>
			</tr>
			<tr>
				<th>その他備考（請求書）</th>
				<td><?php echo $form->textarea('terms2',array('cols'=>'60','rows'=>'5')); ?><br /> 
				<a id="selSentence" href="javascript:void(0);">定型文を選択</a></td>
			</tr>
			<tr>
				<th>その他備考（検収書）</th>
				<td><?php echo $form->textarea('terms3',array('cols'=>'60','rows'=>'5')); ?></td>
			</tr>
			<tr>
				<th>その他備考（納品書）</th>
				<td><?php echo $form->textarea('terms4',array('cols'=>'60','rows'=>'5')); ?></td>
			</tr>
		</table>
		<div id="dispSentence">
			<ul>
			</ul>
			<a href="javascript:void(0);">close</a>
		</div>
	</div>
	<!--/other -->
	<div id="local_menu">
		<div class="menu_block">
			<h3>書類作成</h3>
			<ul>
				<li><a href="javascript:void(0);" onclick="document.account.submit();">確認</a></li>
			</ul>
		</div>
	</div>
	</form>
	</div>
<!-- /main --> 

