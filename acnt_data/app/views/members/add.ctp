<!-- main -->  
	<div id="main">
	<h2>担当新規登録</h2>
<?php 
	$session->flash(); 
	echo $form->create('Member',array('action' => 'add'));
?>
		<table id="member_list_v">
		  <tr>
		    <th style="width:150px;">担当者名</th>
		    <td><?php echo $form->input('name',array('label'=>'','value'=>$member['Member']['name'],'size'=>'50')); ?></td>
		  </tr>
		  <tr>
		    <th>ユーザーID</th>
		    <td><?php echo $form->input('username',array('label'=>'','value'=>$member['Member']['username'],'size'=>'50')); ?></td>
		  </tr>
		  <tr>
		    <th>パスワード</th>
		    <td><?php echo $form->input('password',array('type'=>'password','label'=>'','value'=>$member['Member']['password'],'size'=>'50')); ?></td>
		  </tr>
		</table>
<?php
    echo $form->end('登録');
   ?>
 	</div>
<!-- /main --> 
