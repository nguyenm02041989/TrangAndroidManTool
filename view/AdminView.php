<?php include_once 'basic/HtmlHeaderView.php'; ?>
<?php include_once 'basic/HtmlHeadStartView.php'; ?>

<link rel="stylesheet" href="/css/admin.css" type="text/css" />
<script type="text/javascript" src="/js/mainview.js" ></script>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>

<?php include_once 'basic/HtmlHeadEndView.php'; ?>
<?php include_once 'basic/HtmlBodyView.php'; ?>

<div id="topmenu">
	<div class="relative">
		<div id="logo">
			<a href="/"><img src="/images/logo.png" alt="" title="" /></a>
		</div>
		<div id="topmenu-links">
			<a href="<?= URI::Get("/login/logout"); ?>">{LOGOUT}</a>
		</div>
	</div>
</div>
<div id="main">
	<div class="col1">
		<div id="leftmenu">
			<span class="title">{NAME_USER}</span>
			<ul>
				<li><div class="line"></div></li>
				<li><span class="title">{MENU}</span></li>
				<li><div class="line"></div></li>
				<li>
					<ul>
						<? foreach(Navigation::GetInstance()->Get("gcm") as $row) {?>
						<li><a href="<?= $row["url"] ?>"
						<?= isset($row["active"]) ? $row["active"] : "" ?>
						><?= $row["name"] ?></a></li>
						<?}?>
					</ul>
				</li>
				<li><span class="title">{SETTINGS}</span></li>
				<li><div class="line"></div></li>
				<li>
					<ul>
						<? foreach(Navigation::GetInstance()->Get("settings") as $row) {?>
						<li><a href="<?= $row["url"] ?>"
						<?= isset($row["active"]) ? $row["active"] : "" ?>
						><?= $row["name"] ?></a></li>
						<?}?>
					</ul>
				</li>
				<li><div class="line"></div></li>
			</ul>
			
		</div>
	
	</div>
	<div class="col2">
		{PAGE_CONTENT}
		<div class="bottommargin">&nbsp;</div>
	</div>
	<div class="clear"></div>
</div>

<?php include_once 'basic/HtmlBottomView.php'; ?>
