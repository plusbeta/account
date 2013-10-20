<!-- main -->  
	<div id="main">
<?php $session->flash(); ?>
		<div class="clearfix" style="width:900px;padding:0 0 5px 0">
			<div style="float:left;">
			<!-- 現在のページ番号を表示する。 -->
			<?php echo $paginator->numbers(); ?>&nbsp;&nbsp;&nbsp;
			<?php echo $paginator->counter(array(
				'format' => '件数 <span id="cnt">%count%</span> 件'
			)); 
			?>
			</div>
			<div style="float:right;">
			<a href="/sys/clients/getCsv"><input type="button" value="CSVダウンロード" /></a>
			</div>
		</div>
		<table id="client_list">
		  <tr>
		    <th class="client">会社名</th>
		    <th class="cnt">スタンス</th>
		    <th class="cnt">取引件数</th>
		    <th class="code">コード</th>
		    <th class="member">相手先担当者</th>
		    <th class="date">登録日</th>
		  </tr>
<?php 
foreach($client_list as $key=>$row){
?>
		  <tr class="a_row">
		    <td><a href="<?php echo "/sys/clients/view/".$row['Client']['id']; ?>"><?php echo $row['Client']['name'] ?></a></td>
		    <td class="number"><?php echo count($row['Account']) ?> 件</td>
		    <td><?php echo $row['Client']['type_t'] ?></td>
		    <td><?php echo $row['Client']['code'] ?></td>
		    <td><?php echo $row['Client_person']['0']['name'] ?></td>
		    <td><?php echo $row['Client']['created'] ?></td>
		  </tr>
<?php 
}
?>

		</table>
	</div>
<!-- /main --> 
