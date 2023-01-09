<?php

use ColdTrick\TranslationEditor\DI\Snapshot;

$snapshot = (int) get_input('snapshot');
if (empty($snapshot)) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!Snapshot::instance()->delete($snapshot)) {
	return elgg_error_response(elgg_echo('translation_editor:action:snapshot:delete:error'));
}

return elgg_ok_response('', elgg_echo('translation_editor:action:snapshot:delete:success'));
