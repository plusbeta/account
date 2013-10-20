<!-- header -->  
	<div id="header">
		<h1>請求書管理</h1>
<?php	if($this->action=="login"){ ?>
		<div id="user_box">&nbsp;<br />
		</div>

<?php }else{ ?>
		<div id="user_box">ユーザー名：<?php e($username) ?><br />
			<a href="/sys/members/logout">ログアウト</a>
		</div>
<?php } ?>
	</div>
<!-- /header --> 
<!-- menu -->  
	<div id="menu">
		<ul id="globalnav">
		  <li><a href="#" class="here">メニュー</a>
			<ul><li></li></ul>
		  </li>
		  <li><a href="/sys/members">担当者管理</a>
		  </li>
		  <li><a href="/sys/settings/edit">設定管理</a></li>
		</ul>	
	</div>
<!-- /menu -->
<?php	if($this->action!="login"){ ?>
<!-- common head -->
	<div id="common_head">
		<table id="comm_menu">
			<tr>
				<td rowspan="2">受注台帳管理</td>
				<td><a href="/sys/accounts">受注台帳一覧</a></td>
				<td>クライアント管理</td>
				<td><a href="/sys/clients">クライアント情報一覧</a></td>
				<td><a href="/sys/clients/add">クライアント情報を追加</a></td>
				<td>クライアント情報を取得</td>
			</tr>
			<tr>
				<td><a href="/sys/accounts/add">受注台帳基本情報を作成</a></td>
				<td>請求書情報管理</td>
				<td><a href="/sys/remarks">その他備考定型分を追加・変更</a></td>
				<td><a href="/sys/banks">振込先情報を追加・変更</a></td>
				<td>月毎の売り上げ状況</td>
			</tr>
		</table>
	</div>
<!-- /common head -->
<?php } ?>