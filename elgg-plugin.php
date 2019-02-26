<?php

use Elgg\Router\Middleware\AdminGatekeeper;
use ColdTrick\TranslationEditor\EditorGatekeeper;
use ColdTrick\TranslationEditor\Bootstrap;

define('TRANSLATION_EDITOR_DISABLED_LANGUAGE', 'disabled_languages');

require_once(__DIR__ . '/lib/functions.php');

return [
	'bootstrap' => Bootstrap::class,
	'actions' => [
		'translation_editor/admin/toggle_translation_editor' => [
			'access' => 'admin',
		],
		'translation_editor/admin/delete' => [
			'access' => 'admin',
		],
		'translation_editor/admin/disable_languages' => [
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
];
