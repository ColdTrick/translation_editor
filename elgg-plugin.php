<?php

use Elgg\Router\Middleware\AdminGatekeeper;
use ColdTrick\TranslationEditor\EditorGatekeeper;
use ColdTrick\TranslationEditor\Bootstrap;
use ColdTrick\TranslationEditor\Upgrades\MigrateDisabledLanguages;
use ColdTrick\TranslationEditor\Upgrades\RemoveCustomKeysFolder;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'version' => '11.0.1',
	],
	'bootstrap' => Bootstrap::class,
	'actions' => [
		'translation_editor/admin/toggle_translation_editor' => [
			'access' => 'admin',
		],
		'translation_editor/admin/delete' => [
			'access' => 'admin',
		],
		'translation_editor/admin/add_language' => [
			'access' => 'admin',
		],
		'translation_editor/admin/delete_language' => [
			'access' => 'admin',
		],
		'translation_editor/admin/import' => [
			'access' => 'admin',
		],
		'translation_editor/admin/export' => [
			'access' => 'admin',
		],
		'translation_editor/translate' => [],
		'translation_editor/merge' => [],
		'translation_editor/cleanup/remove' => [],
		'translation_editor/cleanup/download' => [],
	],
	'routes' => [
		'default:translation_editor:import' => [
			'path' => '/translation_editor/import/{current_language?}',
			'resource' => 'translation_editor/import',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'default:translation_editor:export' => [
			'path' => '/translation_editor/export/{current_language?}',
			'resource' => 'translation_editor/export',
			'middleware' => [
				AdminGatekeeper::class,
			],
		],
		'default:translation_editor:search' => [
			'path' => '/translation_editor/search',
			'resource' => 'translation_editor/search',
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'default:translation_editor:plugin' => [
			'path' => '/translation_editor/{current_language}/{plugin_id}',
			'resource' => 'translation_editor/plugin',
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'default:translation_editor' => [
			'path' => '/translation_editor/{current_language?}',
			'resource' => 'translation_editor/index',
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
	],
	'upgrades' => [
		MigrateDisabledLanguages::class,
		RemoveCustomKeysFolder::class,
	],
	'hooks' => [
		'languages' => [
			'translations' => [
				'\ColdTrick\TranslationEditor\Languages::registerCustomLanguages' => [],
			],
		],
		'register' => [
			'menu:page' => [
				'\ColdTrick\TranslationEditor\Menus\Page::register' => [],
			],
			'menu:site' => [
				'\ColdTrick\TranslationEditor\Menus\Site::register' => [],
			],
			'menu:title' => [
				'\ColdTrick\TranslationEditor\Menus\Title::register' => [],
			],
			'menu:user_hover' => [
				'\ColdTrick\TranslationEditor\Menus\UserHover::register' => [],
			],
		],
	],
	'view_extensions' => [
		'css/elgg' => [
			'translation_editor/site.css' => [],
		],
	],
];
