<?php

use \ColdTrick\TranslationEditor\DI\Snapshot;

Snapshot::instance()->create();

return elgg_ok_response('', elgg_echo('translation_editor:action:snapshots:create:success'));
