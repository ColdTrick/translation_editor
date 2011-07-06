<?php 

?>
<div>
	<?php echo elgg_echo("translation_editor:settings:show_in_tools"); ?>
	<select name="params[show_in_tools]">
		<option value="yes" <?php if($vars["entity"]->show_in_tools != "no") echo "selected='selected'"; ?>><?php echo elgg_echo("option:yes"); ?></option>
		<option value="no" <?php if($vars["entity"]->show_in_tools == "no") echo "selected='selected'"; ?>><?php echo elgg_echo("option:no"); ?></option>
	</select>
</div>