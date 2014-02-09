<div class="pagcontainer">
	<form action="<?= ViewController::GetUrl("Search") ?>">
		<input type="text" name="q" maxlength="50" value="<?= ViewController::$TemplateVars["q_search"] ?>" class="fieldsearch" />
		<input type="submit" value="{SEARCH}" class="button" />
	</form>
</div>