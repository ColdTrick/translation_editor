<?php

use ColdTrick\TranslationEditor\Bootstrap;
use ColdTrick\TranslationEditor\EditorGatekeeper;
use ColdTrick\TranslationEditor\Rest\GetTranslations;
use Elgg\Router\Middleware\AdminGatekeeper;

require_once(__DIR__ . '/lib/functions.php');

return [
	'plugin' => [
		'version' => '13.1.1',
	],
	'bootstrap' => Bootstrap::class,
	'actions' => [
		'translation_editor/admin/add_language' => [
			'access' => 'admin',
		],
		'translation_editor/admin/delete' => [
			'access' => 'admin',
		],
		'translation_editor/admin/delete_language' => [
			'access' => 'admin',
		],
		'translation_editor/admin/export' => [
			'access' => 'admin',
		],
		'translation_editor/admin/import' => [
			'access' => 'admin',
		],
		'translation_editor/admin/remote' => [
			'access' => 'admin',
		],
		'translation_editor/admin/toggle_translation_editor' => [
			'access' => 'admin',
		],
		'translation_editor/cleanup/download' => [
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'translation_editor/cleanup/remove' => [
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'translation_editor/merge' => [
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'translation_editor/translate' => [
			'middleware' => [
				EditorGatekeeper::class,
			],
		],
		'translation_editor/snapshots/create' => [
			'access' => 'admin',
		],
		'translation_editor/snapshots/delete' => [
			'access' => 'admin',
		],
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
		'default:translation_editor:compare' => [
			'path' => '/translation_editor/compare/{language}/{snapshot}',
			'resource' => 'translation_editor/compare',
			'middleware' => [
				AdminGatekeeper::class,
			],
			'requirements' => [
				'snapshot' => '\d+',
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
	'events' => [
		'languages' => [
			'translations' => [
				'\ColdTrick\TranslationEditor\Languages::registerCustomLanguages' => [],
			],
		],
		'register' => [
			'menu:admin_header' => [
				'\ColdTrick\TranslationEditor\Menus\AdminHeader::register' => [],
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
		'elgg.css' => [
			'translation_editor/site.css' => [],
		],
	],
	'view_options' => [
		'translation_editor/snapshots' => [
			'ajax' => true,
		],
	],
	'web_services' => [
		'translation_editor.get_translations' => [
			'GET' => [
				'callback' => GetTranslations::class,
				'params' => [
					'language' => [
						'type' => 'string',
						'required' => true,
					],
					'plugins' => [
						'type' => 'array',
						'required' => true,
					],
				],
				'require_api_auth' => true,
			],
		],
	],
];
