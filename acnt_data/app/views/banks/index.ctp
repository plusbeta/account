<!-- main -->  
	<div id="main">
<?php
	echo $form->create('Bank',array('action' => 'index','name'=>'bank'));
	echo $form->hidden('id',array('value'=>$bank_reg['Bank']['id'],'id'=>'reg_id'));
	echo $form->hidden('mode',array('value'=>$bank_list['mode'],'id'=>'reg_mode'));
	$session->flash();
?>
		<h2>現在登録済みの振込先</h2>
		<table id="bank_list">
		  <tr>
		    <th class="name">銀行名</th>
		    <th class="kind">口座種別</th>
		    <th class="number">口座番号</th>
		    <th></th>
		  </tr>
<?php 
for($i=0;$i<count($bank_list)-1;$i++){
?>
		  <tr class="a_row">
		    <td><?php echo $bank_list[$i]['Bank']['name'] ?></td>
		    <td><?php echo $bank_list[$i]['Bank']['kind_t'] ?></td>
		    <td><?php echo $bank_list[$i]['Bank']['number'] ?></td>
		    <td><input type="button" value="情報の確認・変更" onclick="submitBank('edit',<?php echo $bank_list[$i]['Bank']['id']; ?>);" /></td>
		  </tr>
<?php
}
?>

		</table>
		<h2>振込先情報の追加・変更</h2>
		<table id="bank_list">
		  <tr>
		    <th class="name">銀行名</th>
		    <th class="kind">口座種別</th>
		    <th class="number_w">口座番号</th>
		  </tr>
		  <tr class="a_row">
		    <td><?php echo $form->hidden('setting_id',array('label'=>'','value'=>$bank_reg['Bank']['setting_id']));
		    echo $form->input('name',array('label'=>'','value'=>$bank_reg['Bank']['name'],'size'=>'30')); ?></td>
		    <td><?php echo $form->select('kind',array('1'=>'普通','2'=>'当座','3'=>'貯蓄'),$bank_reg['Bank']['kind'],array(),null); ?></td>
		    <td><?php echo $form->input('number',array('label'=>'','value'=>$bank_reg['Bank']['number'],'size'=>'30')); ?></td>
		  </tr>
		  <tr class="a_row">
		    <td colspan="3"><input id="submitBtn" type="button" value="登録する" onclick="submitBank('reg');" /></td>
		  </tr>
		</table></form>
	</div>
<!-- /main --> 
