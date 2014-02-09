<?php include_once 'basic/HtmlHeaderView.php'; ?>
<?php include_once 'basic/HtmlHeadStartView.php'; ?>

<link rel="stylesheet" href="/css/login.css" type="text/css" />

<?php include_once 'basic/HtmlHeadEndView.php'; ?>
<?php include_once 'basic/HtmlBodyView.php'; ?>

<div id="main">
	<div id="logo">
		<a href="/"><img src="/images/logo.png" alt="" title="" /></a>
	</div>
	<div class="topspace">&nbsp;</div>
	<div id="login">
		<div id="title-login">
			<h1>{LOGIN_TITLE}</h1>
		</div>
		<div>
			<h2>{LOGIN}</h2>
		</div>
		<div id="loginform">
			<form action="<?= URI::Get("/login/authenticate"); ?>" method="post">
				<div class="col-label">{USERNAME}</div>
				<div class="col-input">
					<input type="text" name="uname" value="" id="inpUser" class="typeinp" />
				</div>
				<div class="clear"></div>
				<div class="col-label">{PASSWORD}</div>
				<div class="col-input">
					<input type="password" name="upass" value="" class="typeinp" />
				</div>
				<div class="clear"></div>
				<div class="col-label"></div>
				<div class="col-input">
					<input type="submit" value="{SEND}" class="typebtn" />
				</div>
				<div class="clear"></div>
				<input type="hidden" name="task" value="/login/authenticate" />				
				<input type="hidden" name="security_form_id" value="{SECURITY_FORM_ID}" />				
			</form>
			<div class="error">
				{ERROR_TEXT}
			</div>
		</div>
	</div>
	<div id="copyright">{COPYRIGHT}</div>
</div>
<script type="text/javascript">
<!--
document.getElementById("inpUser").focus();
//-->
</script>
<?php include_once 'basic/HtmlBottomView.php'; ?>
