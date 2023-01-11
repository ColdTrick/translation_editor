<?php

namespace ColdTrick\TranslationEditor;

use ColdTrick\TranslationEditor\Rest\GetTranslations;
use Elgg\WebServices\ElggApiClient;

class Rest {
	
	/**
	 * Expose the translation editor API functions
	 *
	 * @param \Elgg\Hook $hook 'rest', 'init'
	 *
	 * @return void
	 */
	public static function exposeFunctions(\Elgg\Hook $hook) {
		elgg_ws_expose_function(
			'translation_editor.get_translations',
			GetTranslations::class,
			[
				'language' => [
					'type' => 'string',
					'required' => true,
				],
				'plugins' => [
					'type' => 'array',
					'required' => true,
				],
			],
			elgg_echo('translation_editor:api:get_translations:description'),
			'GET',
			true,
		);
	}
	
	public static function getClient(string $method, array $params): ?ElggApiClient {
		if (!elgg_is_active_plugin('web_services')) {
			return null;
		}
		
		$plugin = elgg_get_plugin_from_id('translation_editor');
		$host = $plugin->remote_host;
		$public_key = $plugin->remote_public_key;
		$private_key = $plugin->remote_private_key;
		
		if (empty($host) || empty($public_key)) {
			return null;
		}
		
		$host = rtrim($host, '/');
		$host .= '/services/api/rest/json/';
		
		$params['method'] = $method;
		
		$client = new ElggApiClient($host, $params);
		$client->setApiKeys($public_key, $private_key ?: '');
		
		return $client;
	}
}
