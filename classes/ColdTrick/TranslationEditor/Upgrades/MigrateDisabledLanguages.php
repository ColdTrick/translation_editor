<?php

namespace ColdTrick\TranslationEditor\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Migrate Translation Editor disabled languages to allowed languages in ELgg core
 */
class MigrateDisabledLanguages implements AsynchronousUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion(): int {
		return 2020042401;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset(): bool {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped(): bool {
		if (!empty(elgg_get_config('allowed_languages'))) {
			// core already has a setting
			return true;
		}
		
		$setting = elgg_get_plugin_setting('disabled_languages', 'translation_editor');
		if (empty($setting)) {
			return true;
		}
		
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems(): int {
		return (int) !$this->shouldBeSkipped();
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset): Result {
		$setting = elgg_get_plugin_setting('disabled_languages', 'translation_editor');
		$setting = explode(',', $setting);
		
		$installed_translations = elgg()->translator->getInstalledTranslations();
		$installed_translations = array_keys($installed_translations);
		
		$allowed = array_diff($installed_translations, $setting);
		if (elgg_save_config('allowed_languages', implode(',', $allowed))) {
			// remove setting from Translation Editor
			elgg_unset_plugin_setting('disabled_languages', 'translation_editor');
			
			$result->addSuccesses();
		} else {
			$result->addFailures();
		}
		
		return $result;
	}
}
