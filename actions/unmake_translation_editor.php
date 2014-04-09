<?php
/**
 * Remove the translation role form the provided user
 */

$result = false;

$user_guid = (int) get_input("user");
$user = get_user($user_guid);

if (!empty($user)) {
	unset($user->translation_editor);
	$result = true;
}

if (!$result) {
	register_error(elgg_echo("translation_editor:action:unmake_translation_editor:error"));
} else {
	system_message(elgg_echo("translation_editor:action:unmake_translation_editor:success"));
}

forward(REFERER);
