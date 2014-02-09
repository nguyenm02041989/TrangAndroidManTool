<h2>{DEVICE_REGISTRATIONS} - {SIGNED_OUT}</h2>
{SNIPPET_PAGINATION}
<div class="rowheader">
	<div class="rowcol1 headerlabel">{GCM_ID}</div>
	<div class="rowcol2 headerlabel">{GCM_REGID}</div>
	<div class="rowcol3 headerlabel">{SIGNED_OUT}</div>
	<div class="rowcol4 headerlabel">{DATE_EDIT}</div>
</div>
<div class="clear"></div>
<div class="line"></div>
<?php 
$allowRead = Acl::GetInstance()->AllowReadAccess();
foreach(ViewController::$TemplateVars["list_rows"] as $obj) {?>

<div class="row">
	<div class="rowcol1"><?php echo $obj->gcm_id; ?></div>
	<div class="rowcol2"><?php echo $obj->gcm_regid; ?></div>
	<div class="rowcol3">
		<input type="checkbox" disabled="disabled" <?php echo ($obj->is_deleted == 1 ? 'checked="checked"' : ""); ?>/>
	</div>
	<div class="rowcol4"><?php echo $obj->date_edit; ?></div>
	<? if($allowRead) {?>
	<div class="rowcolaction"><a href="<?= ViewController::GetUrl("View") . "/" . $obj->gcm_id ?>">{VIEW}</a></div>
	<?}?>
</div>
<div class="clear"></div>
<div class="line"></div>
<?}?>
