<?php

namespace ColdTrick\TranslationEditor;

use ColdTrick\TranslationEditor\Rest\GetTranslations;
use Elgg\WebServices\ElggApiClient;

/**
 * Register REST API methods
 */
class Rest {
	
	/**
	 * Get an Elgg API client to talk to another Elgg installation
	 *
	 * @param string $method API method to call
	 * @param array  $params Params for the API call
	 *
	 * @return ElggApiClient|null
	 * @throws \APIException
	 */
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
