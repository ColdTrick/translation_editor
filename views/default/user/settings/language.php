<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

global $CONFIG;
$user = page_owner_entity();

translation_editor_unregister_translations();

$installed_languages = get_installed_translations();

if (!empty($user) && ($user instanceof ElggUser)){
	$value = $CONFIG->language;
	if (!empty($user->language)) {
		$value = $user->language;
	}
	
	if(count($installed_languages) > 1) {

	?>
	<h3><?php echo elgg_echo('user:set:language'); ?></h3>
	<p>
	
		<?php 
			echo elgg_echo('user:language:label');
			echo ": ";
			echo elgg_view("input/pulldown", array('internalname' => 'language', 
													'value' => $value,
													'options_values' => $installed_languages));	
		?>
	</p>
	
	<?php
	} else {
		echo elgg_view("input/hidden", array("internalname" => "language", "value" => $value));
	}
}