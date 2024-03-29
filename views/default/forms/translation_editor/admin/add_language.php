<?php

$options = [];
$current_language = elgg_get_current_language();
$keys = elgg()->locale->getLanguageCodes();
foreach ($keys as $lang_key) {
	$trans_key = $lang_key;
	if (elgg_language_key_exists($lang_key, $current_language) || elgg_language_key_exists($lang_key)) {
		$trans_key = elgg_echo($lang_key);
	}
	
	$options[$lang_key] = $trans_key;
}

$installed_languages = elgg()->translator->getInstalledTranslations();
foreach ($installed_languages as $index => $lang) {
	unset($options[$index]);
}

asort($options);

echo elgg_view_field([
	'#type' => 'fieldset',
	'fields' => [
		[
			'#type' => 'select',
			'name' => 'code',
			'options_values' => $options,
		],
		[
			'#type' => 'submit',
			'text' => elgg_echo('save'),
		],
	],
	'align' => 'horizontal',
]);
