<?php

namespace ColdTrick\TranslationEditor;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * Helper class for plugin translations
 */
class PluginTranslation {
	
	protected string $plugin_id;
	
	protected string $language;
	
	/**
	 * Constructor
	 *
	 * @param string $plugin_id plugin id
	 * @param string $language  language for the translations
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct(string $plugin_id, string $language = 'en') {
		if (empty($plugin_id)) {
			throw new InvalidArgumentException('A plugin id must be set');
		}
		
		if (empty($language)) {
			throw new InvalidArgumentException('A language must be set');
		}
		
		if (!in_array($language, translation_editor_get_available_languages())) {
			throw new InvalidArgumentException("Language {$language} isn't supported by the system");
		}
		
		$this->plugin_id = $plugin_id;
		$this->language = $language;
	}

	/**
	 * Checks and creates folder structure if needed
	 *
	 * @return void
	 */
	protected function createFolderStructure(): void {
		$base_dir = elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR;
		if (!file_exists($base_dir)) {
			mkdir($base_dir, 0755, true);
		}
		
		if (!file_exists($base_dir . $this->language . DIRECTORY_SEPARATOR)) {
			mkdir($base_dir . $this->language . DIRECTORY_SEPARATOR, 0755, true);
		}
	}
	
	/**
	 * Returns filename of the plugin translation file
	 *
	 * @return string
	 */
	protected function getFilename(): string {
		return elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR . $this->language . DIRECTORY_SEPARATOR . $this->plugin_id . '.json';
	}
	
	/**
	 * Write the custom translation for a plugin to disk
	 *
	 * @param array $translations custom translations
	 *
	 * @return false|int
	 */
	public function saveTranslations($translations = []) {
		$this->createFolderStructure();
				
		$contents = json_encode($translations);
	
		$bytes = file_put_contents($this->getFilename(), $contents);
		if (empty($bytes)) {
			return false;
		}
		
		$this->removeStatisticsCache();
		
		return $bytes;
	}
	
	/**
	 * Read the custom translations for this plugin
	 *
	 * @return null|array
	 */
	public function readTranslations(): ?array {
		$file_name = $this->getFilename();
		if (!file_exists($file_name)) {
			return null;
		}
		
		$contents = file_get_contents($file_name);
		if (empty($contents)) {
			return [];
		}
		
		return json_decode($contents, true);
	}
	
	/**
	 * Removes translation file
	 *
	 * @return bool
	 */
	public function removeTranslations(): bool {
		$filename = $this->getFilename();
		
		$this->removeStatisticsCache();
		
		if (file_exists($filename)) {
			return unlink($filename);
		}
		
		return true;
	}
	
	/**
	 * Removes statistics cache
	 *
	 * @return void
	 */
	protected function removeStatisticsCache(): void {
		elgg_delete_system_cache("{$this->plugin_id}_{$this->language}_translation_stats");
	}
}
