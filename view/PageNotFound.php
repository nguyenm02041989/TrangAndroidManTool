<?php include_once 'basic/HtmlHeaderView.php'; ?>
<?php include_once 'basic/HtmlHeadStartView.php'; ?>

<link rel="stylesheet" href="/css/login.css" type="text/css" />

<?php include_once 'basic/HtmlHeadEndView.php'; ?>
<?php include_once 'basic/HtmlBodyView.php'; ?>

<div id="main">
	<div id="logo">
		<a href="gcm.php"><img src="/images/logo.jpg" alt="" title="" /></a>
	</div>
	<div class="topspace">&nbsp;</div>
	<div id="login">
		<div id="title-login">
			<h1>{LOGIN_TITLE}</h1>
		</div>
		<div id="loginform">
			<div class="error">
				{PAGE_NOT_FOUND}
			</div>
		</div>
	</div>
	<div id="copyright">{COPYRIGHT}</div>
</div>
<?php include_once 'basic/HtmlBottomView.php'; ?>
