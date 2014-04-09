<?php
/**
 * give a user the right to modify the translations
 */

$result = false;

$user = get_input("user");
$role = "translation_editor";

$user = get_user($user);
if (!empty($user)) {
	if (create_metadata($user->getGUID(), $role, true, "integer", $user->getGUID(), ACCESS_PUBLIC)) {
		$result = true;
	}
}

if (!$result) {
	register_error(elgg_echo("translation_editor:action:make_translation_editor:error"));
} else {
	system_message(elgg_echo("translation_editor:action:make_translation_editor:success"));
}

forward(REFERER);
