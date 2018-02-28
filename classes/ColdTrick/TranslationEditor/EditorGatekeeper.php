<?php

namespace ColdTrick\TranslationEditor;

use Elgg\Request;
use Elgg\HttpException;

class EditorGatekeeper {
	
	/**
	 * Editor gatekeeper
	 *
	 * @param Request $request the page request
	 *
	 * @return void
	 * @throws HttpException
	 */
	public function __invoke(Request $request) {
		
		$request->elgg()->gatekeeper->assertAuthenticatedUser();
		
		$user_guid = $request->elgg()->session->getLoggedInUserGuid();
		if (translation_editor_is_translation_editor($user_guid)) {
			return;
		}
		
		throw new HttpException(elgg_echo('translation_editor:gatekeeper'), ELGG_HTTP_FORBIDDEN);
	}
}
