<!-- main -->  
	<div id="main">
		<div id="search_box" class="clearfix">
		
		<ul>
			<li><?php echo $form->year('searchYear',date('Y')-MIN_YEAR,date('Y'),$seach_arg['year'],array('id'=>'searchYear'),"すべて")."年"; ?></li>
			<li><?php echo $form->month('searchMonth', $seach_arg['month'],array('id'=>'searchMonth','value'=>'','monthNames' =>  false),"すべて")."月"; ?></li>
			<li>案件名 or クライアント名<input type="text" id="searchText" size="30" value="<?php echo $seach_arg['word'] ?>" /></li>
			<li><select id="searchFlag">
				<option value="">全て</option>
				<option value="est">見積済</option>
				<option value="bill">請求済</option>
				<option value="deli">納品済</option>
				<option value="recv">回収済</option>
			</select>
			</li>
		</ul>
		</div>
<?php $session->flash(); ?>
		<!-- 現在のページ番号を表示する。 -->
		<?php echo $paginator->numbers(); ?>&nbsp;&nbsp;&nbsp;
		<?php echo $paginator->counter(array(
			'format' => '件数 <span id="cnt">%count%</span> 件'
		)); 
		?>
		<!-- 次のページへのリンクを表示する。 -->
		<?php
			//echo $paginator->prev('« 前へ ', null, null, array('class' => 'disabled'));
			//echo $paginator->next(' 次へ »', null, null, array('class' => 'disabled'));
		?> 
		<?php //echo $paginator->counter(); ?>
		<table id="account_list">
		  <tr>
		    <th class="date">作成日</th>
		    <th class="date">請求日</th>
		    <th class="proj">案件名</th>
		    <th class="client">クライアント名</th>
		    <th>請求金額</th>
		    <th class="member">担当者</th>
		    <th>ステータス</th>
		  </tr>
<?php 
foreach($account_list as $row){
?>
		  <tr class="a_row">
		    <td><?php echo $row['Account']['created'] ?></td>
		    <td><?php echo $row['Account']['bill_date'] ?></td>
		    <td><a href="<?php echo "/sys/accounts/view/".$row['Account']['id']; ?>"><?php echo $row['Account']['name'] ?></a></td>
		    <td><?php echo $row['Client']['name'] ?></td>
		    <td class="number">\ <?php echo number_format($row['Account']['contract_price']); ?></td>
		    <td><?php echo $row['Member']['name'] ?></td>
		    <td><?php echo $form->select('estimate_flag',array('0'=>'未見積','1'=>'見積済'),$row['Account']['estimate_flag'],array('name'=>'estimate_flag','class'=>'flags','target'=>$row['Account']['id']),null); ?>&nbsp;
				<?php echo $form->select('bill_flag',array('0'=>'未請求','1'=>'請求済'),$row['Account']['bill_flag'],array('name'=>'bill_flag','class'=>'flags','target'=>$row['Account']['id']),null); ?>&nbsp;
				<?php echo $form->select('delivery_flag',array('0'=>'未納品','1'=>'納品済'),$row['Account']['delivery_flag'],array('name'=>'delivery_flag','class'=>'flags','target'=>$row['Account']['id']),null); ?>&nbsp;
				<?php echo $form->select('receive_flag',array('0'=>'未回収','1'=>'回収済'),$row['Account']['receive_flag'],array('name'=>'receive_flag','class'=>'flags','target'=>$row['Account']['id']),null); ?>
		    </td>
		  </tr>
<?php 
}
?>

		</table>
	</div>
<!-- /main --> 
