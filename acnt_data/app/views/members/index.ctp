<!-- main -->  
	<div id="main">
<?php $session->flash(); ?>
	<a href="/sys/members/add">新規担当登録</a>
		<table id="member_list">
		  <tr>
		    <th class="client">担当者名</th>
		    <th class="cnt">ユーザーID</th>
		    <th></th>
		    <th class="code"></th>
		  </tr>
<?php 
foreach($member_list as $key=>$row){
?>
		  <tr class="a_row">
		    <td><?php echo $row['Member']['name'] ?></td>
		    <td><?php echo $row['Member']['username'] ?></td>
		    <td><?php echo $valid[$row['Member']['valid']] ?></td>
		    <td style="width:100px;"><a href="<?php echo "/sys/members/edit/".$row['Member']['id']; ?>">編集</a>&nbsp;&nbsp;&nbsp;
			<a href="<?php echo "/sys/members/delete/".$row['Member']['id']; ?>">削除</a></td>
		  </tr>
<?php 
}
?>

		</table>
	</div>
<!-- /main --> 
