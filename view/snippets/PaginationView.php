<div class="pagcontainer">
	<div class="pageno">
	<? if(ViewController::$TemplateVars["total_records"] == 1) { 
		echo ViewController::$TemplateVars["total_records"];?> {RECORD}<?
	} else {
	echo ViewController::$TemplateVars["total_records"];?> {RECORDS}<?
	} ?>&nbsp;&nbsp;|&nbsp;&nbsp;
	</div>
	<? if(strlen(ViewController::$TemplateVars["pag_prev"]) > 0) {?>
	<div class="pageno">
		 <a href="<?= ViewController::$TemplateVars["pag_prev"]; ?>">&lsaquo; {PREVIOUS}</a>
	</div>		
	<? }?>
	
	<? foreach(ViewController::$TemplateVars["pagination"] as $obj) {?>
	<div class="pageno">
		<? if(strlen($obj["page_number"]) > 0) { ?>
		<a href="<?= $obj["url"]; ?>" class="<?= $obj["active"]; ?>"><? echo $obj["page_number"]; ?></a>
		<?} 
		else {?>
		&nbsp;&nbsp;...&nbsp;&nbsp;
		<?}?>
	</div>
	<? } ?>
	
	<? if(strlen(ViewController::$TemplateVars["pag_next"]) > 0) {?>
	<div class="pageno">
		<a href="<?= ViewController::$TemplateVars["pag_next"]; ?>">{NEXT} &rsaquo;</a> 
	</div>		
	<? }?>
	<div class="clear"></div>
</div>
<div class="clear"></div>