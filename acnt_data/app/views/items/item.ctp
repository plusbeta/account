<?php
/* */

 echo $form->create('Item');
 echo $form->input('account_id',array('empty' => '選択'));
 echo $form->hidden('id');
 echo $form->input('name');
 echo $form->input('content');
 echo $form->input('unit_price');
 echo $form->input('number');
 echo $form->input('amount');
 echo $form->input('remark');
 echo $form->input('created');
 echo $form->input('modified');
 echo $form->end('登録');


?>