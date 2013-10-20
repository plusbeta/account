<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Remark',array('action' => 'index','name'=>'bank'));
	echo $form->hidden('id',array('value'=>$remark_reg['Remark']['id'],'id'=>'reg_id'));
	echo $form->hidden('mode',array('value'=>$remark_list['mode'],'id'=>'reg_mode'));
	$session->flash();
?>
		<h2>現在登録済みの定型文</h2>
		<table id="bank_list">
		  <tr>
		    <th>文章</th>
		    <th></th>
		  </tr>
<?php 
for($i=0;$i<count($remark_list)-1;$i++){
?>
		  <tr class="a_row">
		    <td><?php echo $remark_list[$i]['Remark']['sentence'] ?></td>
		    <td><input type="button" value="情報の確認・変更" onclick="submitBank('edit',<?php echo $remark_list[$i]['Remark']['id']; ?>);" /></td>
		  </tr>
<?php
}
?>

		</table>
		<h2>定型文の追加・変更</h2>
		<table id="bank_list">
		  <tr>
		    <th>文章</th>
		  </tr>
		  <tr class="a_row">
		    <td><?php echo $form->hidden('setting_id',array('label'=>'','value'=>$remark_reg['Remark']['setting_id']));
		    echo $form->input('sentence',array('label'=>'','value'=>$remark_reg['Remark']['sentence'],'size'=>'60')); ?></td>
		  </tr>
		  <tr class="a_row">
		    <td colspan="3"><input id="submitBtn" type="button" value="登録する" onclick="submitBank('reg');" /></td>
		  </tr>
		</table></form>
	</div>
<!-- /main --> 
