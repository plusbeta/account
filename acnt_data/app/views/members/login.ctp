	<div id="main">
		<div id="login_window">
		<fieldset id="login_field">
			<?php echo $form->create('Member', array('action' => 'login')); ?>
			<table>
				<tr>
					<td>ログインID&nbsp;&nbsp;</td>
					<td><?php echo $form->input('username',array('label'=>''));?></td>
				</tr>
				<tr>
					<td>パスワード&nbsp;&nbsp;</td>
					<td><?php echo $form->input('password',array('label'=>''));?></td>
				</tr>
			</table>
		    <?php if  ($session->check('Message.auth')) $session->flash('auth');  ?>
			<div class="login_btn">
			<input type="submit" value="ログイン" />
			</div>
			</form>
		</fieldset>
		</div>
	
	</div>

