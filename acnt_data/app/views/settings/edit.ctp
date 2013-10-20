<!-- main -->  
	<div id="main">
	<?php $session->flash(); ?>
<?php
	echo $form->create('Setting',array('action' => 'edit','name'=>'setting'));
	echo $form->hidden('id');
	echo $form->hidden('mode',array('value'=>'conf'));
?>
	<div id="page_title">設定管理</div>
	<div id="base">
		<h2>設定情報</h2>
		<table>
			<tr>
				<th style="width:150px">会社名</th>
				<td><?php echo $form->input('company_name',array('label'=>'','size'=>'50')); ?></td>
			</tr>
			<tr>
				<th>郵便番号</th>
				<td style="width:250px"><?php echo $form->input('zip',array('label'=>'')); ?></td>
			</tr>
			<tr>
				<th>住所</th>
				<td style="width:250px"><?php echo $form->input('address',array('label'=>'','size'=>'50')); ?></td>
			</tr>
			<tr>
				<th>電話</th>
				<td style="width:250px"><?php echo $form->input('tel',array('label'=>'')); ?></td>
			</tr>
			<tr>
				<th>FAX</th>
				<td style="width:250px"><?php echo $form->input('fax',array('label'=>'')); ?></td>
			</tr>
			<tr>
				<th>その他備考 初期値（見積書）</th>
				<td style="width:250px"><?php echo $form->textarea('terms1',array('label'=>'','cols'=>'50','rows'=>'4')); ?></td>
			</tr>
			<tr>
				<th>その他備考 初期値（請求書）</th>
				<td style="width:250px"><?php echo $form->textarea('terms2',array('label'=>'','cols'=>'50','rows'=>'4')); ?></td>
			</tr>
			<tr>
				<th>その他備考 初期値検収書）</th>
				<td style="width:250px"><?php echo $form->textarea('terms3',array('label'=>'','cols'=>'50','rows'=>'4')); ?></td>
			</tr>
			<tr>
				<th>その他備考 初期値（納品書）</th>
				<td style="width:250px"><?php echo $form->textarea('terms4',array('label'=>'','cols'=>'50','rows'=>'4')); ?></td>
			</tr>
		</table>
	</div>
	<div id="item"></div>
	<div id="other"></div>
	<!--/other -->
	<div id="local_menu">
		<div class="menu_block">
			<h3>設定変更</h3>
			<ul>
				<li><a href="javascript:void(0);" onclick="document.setting.submit();">保存する</a></li>
			</ul>
		</div>
	</div>
	</form>
	</div>
<!-- /main --> 

