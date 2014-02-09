<div id="searchbox">
{SNIPPET_SEARCH}
</div>
<h2>{PUSH_MESSAGES}</h2>
{SNIPPET_PAGINATION}
<div class="rowheader">
	<div class="rowcol1 headerlabel">{ID}</div>
	<div class="rowcol2 headerlabel">{MESSAGE}</div>
	<div class="rowcol3 headerlabel">{APP}</div>
	<div class="rowcoldate headerlabel">{DATE_SENT}</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<? 
$allowRead = Acl::GetInstance()->AllowReadAccess();
$allowWrite = Acl::GetInstance()->AllowWriteAccess();

foreach(ViewController::$TemplateVars["list_rows"] as $obj) {?>
<div class="row">
	<div class="rowcol1"><? echo $obj->msg_id; ?></div>
	<div class="rowcol2"><? echo $obj->message; ?></div>
	<div class="rowcol3">
		<? echo $obj->app_name; ?>
	</div>
	<div class="rowcoldate"><? echo $obj->date_create; ?></div>
	<? if($allowRead) {?>
	<div class="rowcolaction"><a href="<?= ViewController::GetUrl("View") . "/" . $obj->msg_id ?>">{VIEW}</a></div>
	<?}?>
</div>
<div class="clear"></div>
<div class="line"></div>
<?}?>
