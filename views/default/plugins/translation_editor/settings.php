<?php

/* @var $entity \ElggPlugin */
$entity = elgg_extract('entity', $vars);

// remote translations
$content = elgg_view('output/longtext', [
	'value' => elgg_echo('translation_editor:settings:remote:description'),
]);

if (!elgg_is_active_plugin('web_services')) {
	$content .= elgg_view_message('error', elgg_echo('translation_editor:settings:remote:error:web_service'));
}

$content .= elgg_view_message('info', elgg_echo('translation_editor:settings:remote:web_service:info'));

$content .= elgg_view_field([
	'#type' => 'url',
	'#label' => elgg_echo('translation_editor:settings:remote:host'),
	'#help' => elgg_echo('translation_editor:settings:remote:host:help'),
	'name' => 'params[remote_host]',
	'value' => $entity->remote_host,
]);

$content .= elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('translation_editor:settings:remote:public_key'),
	'name' => 'params[remote_public_key]',
	'value' => $entity->remote_public_key,
]);

$content .= elgg_view_field([
	'#type' => 'password',
	'#label' => elgg_echo('translation_editor:settings:remote:private_key'),
	'#help' => elgg_echo('translation_editor:settings:remote:private_key:help'),
	'name' => 'params[remote_private_key]',
	'value' => $entity->remote_private_key,
	'always_empty' => false,
]);

echo elgg_view_module('info', elgg_echo('translation_editor:settings:remote:title'), $content);