<?php

use ColdTrick\TranslationEditor\DI\Snapshot;

return [
	Snapshot::name() => Di\autowire(Snapshot::class),
	
	// map classes to alias to allow autowiring
	Snapshot::class => Di\get(Snapshot::name()),
];
