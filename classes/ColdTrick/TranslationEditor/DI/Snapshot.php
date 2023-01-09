<?php

namespace ColdTrick\TranslationEditor\DI;

use Elgg\EntityDirLocator;
use Elgg\Includer;
use Elgg\Project\Paths;
use Elgg\Traits\Di\ServiceFacade;

class Snapshot {
	
	use ServiceFacade;
	
	/**
	 * Returns registered service name
	 *
	 * @return string
	 */
	public static function name() {
		return 'translation-editor-snapshot';
	}
	
	/**
	 * Create a new translation snapshot
	 *
	 * @return void
	 */
	public function create(): void {
		$this->deleteOverflowSnapshots();
		
		$plugin = elgg_get_plugin_from_id('translation_editor');
		$time = time();
		
		$file = new \ElggFile();
		$file->owner_guid = $plugin->guid;
		$file->setFilename("snapshots/{$time}/info.json");
		$file->open('write');
		$file->write(json_encode([
			'creator' => elgg_get_logged_in_user_entity()->username,
		]));
		$file->close();
		
		$destination_base = Paths::sanitize($this->getBaseDir() . $time);
		
		// copy core
		$this->copyLanguageFiles(Paths::elgg() . 'languages/', $destination_base . 'core/');
		
		// copy plugins
		$plugins = elgg_get_plugins('any');
		foreach ($plugins as $plugin) {
			if (!is_dir($plugin->getPath() . 'languages/')) {
				continue;
			}
			
			$this->copyLanguageFiles($plugin->getPath() . 'languages/', $destination_base . $plugin->getID());
		}
	}
	
	/**
	 * Get all snapshots
	 *
	 * @return array
	 */
	public function getAll(): array {
		$result = [];
		$base_dir = $this->getBaseDir();
		if (!is_dir($base_dir)) {
			return [];
		}
		
		$dh = new \DirectoryIterator($base_dir);
		/* @var $file_info \DirectoryIterator */
		foreach ($dh as $file_info) {
			if ($file_info->isFile() || $file_info->isDot()) {
				continue;
			}
			
			$info = $file_info->getPathname() . '/info.json';
			if (file_exists($info)) {
				$info = file_get_contents($info);
				$info = json_decode($info, true);
			} else {
				$info = [];
			}
			
			$result[$file_info->getFilename()] = $info;
		}
		
		krsort($result);
		
		return $result;
	}
	
	/**
	 * Delete a snapshot
	 *
	 * @param int $snapshot the snapshot to remove
	 *
	 * @return bool
	 */
	public function delete(int $snapshot): bool {
		return elgg_delete_directory($this->getBaseDir() . $snapshot);
	}
	
	/**
	 * Compare a snapshot to the current translations
	 *
	 * @param int    $snapshot the snapshot
	 * @param string $language the language to compare
	 *
	 * @return array
	 */
	public function compare(int $snapshot, string $language): array {
		$result = [];
		$snapshot_translations = $this->get($snapshot, $language);
		
		$build_result = function($plugin) use ($language, &$result, $snapshot_translations) {
			$translations = translation_editor_get_plugin($language, $plugin);
			if (empty($translations['total'])) {
				// no language files in plugin
				return;
			}
			
			if (!isset($snapshot_translations[$plugin])) {
				// newly installed plugin
				$new_keys = $translations['en'];
			} else {
				$new_keys = array_diff_key($translations['en'], $snapshot_translations[$plugin]['en']);
			}
			if (!empty($new_keys)) {
				foreach ($new_keys as $key => $value) {
					$result[$plugin]['en'][$key] = $translations['en'][$key];
					
					if (array_key_exists($key, $translations['current_language'])) {
						$result[$plugin]['current_language'][$key] = $translations['current_language'][$key];
					}
					
					if (isset($snapshot_translations[$plugin])) {
						$result[$plugin]['snapshot_language'][$key] = elgg_extract($key, $snapshot_translations[$plugin]['combined']);
					}
				}
			}
			
			if (!isset($snapshot_translations[$plugin])) {
				return;
			}
			
			// changed translations
			$merged_current = array_merge($translations['en'], $translations['original_language']);
			$changed_keys = array_diff($merged_current, $snapshot_translations[$plugin]['combined']);
			if (!empty($changed_keys)) {
				foreach ($changed_keys as $key => $value) {
					if (array_key_exists($key, $translations['current_language'])) {
						if ($value === elgg_extract($key, $translations['custom'])) {
							// current value (including custom) equals value in current translation files
							continue;
						}
						
						$result[$plugin]['current_language'][$key] = $translations['current_language'][$key];
					}
					
					$result[$plugin]['en'][$key] = $translations['en'][$key];
					$result[$plugin]['snapshot_language'][$key] = elgg_extract($key, $snapshot_translations[$plugin]['combined']);
				}
			}
		};
		
		// add core
		$build_result('core');
		
		// add plugins
		$plugins = elgg_get_plugins();
		foreach ($plugins as $plugin) {
			$build_result($plugin->getID());
		}
		
		return $result;
	}
	
	/**
	 * Get all the translations of a snapshot
	 *
	 * @param int    $snapshot the snapshot
	 * @param string $language the language to load
	 *
	 * @return array
	 */
	public function get(int $snapshot, string $language): array {
		$snapshot_directory = $this->getBaseDir() . $snapshot;
		if (!is_dir($snapshot_directory)) {
			return [];
		}
		
		$result = [];
		$dh = new \DirectoryIterator($snapshot_directory);
		/* @var $plugin \DirectoryIterator */
		foreach ($dh as $plugin) {
			if ($plugin->isFile() || $plugin->isDot()) {
				continue;
			}
			
			$plugin_name = $plugin->getFilename();
			
			$result[$plugin_name] = [];
			
			// load English
			if (file_exists($plugin->getPathname() . '/en.php')) {
				$result[$plugin_name]['en'] = Includer::includeFile($plugin->getPathname() . '/en.php');
			}
			
			// load language
			if (file_exists("{$plugin->getPathname()}/{$language}.php")) {
				$result[$plugin_name][$language] = Includer::includeFile("{$plugin->getPathname()}/{$language}.php");
			}
			
			if (isset($result[$plugin_name]['en']) && isset($result[$plugin_name][$language])) {
				$result[$plugin_name]['combined'] = array_merge($result[$plugin_name]['en'], $result[$plugin_name][$language]);
			} elseif (isset($result[$plugin_name]['en'])) {
				$result[$plugin_name]['combined'] = $result[$plugin_name]['en'];
			} elseif (isset($result[$plugin_name][$language])) {
				$result[$plugin_name]['combined'] = $result[$plugin_name][$language];
			} else {
				$result[$plugin_name]['combined'] = [];
			}
		}
		
		return $result;
	}
	
	/**
	 * Copy all translation files
	 *
	 * @param string $source_directory      translation source directory
	 * @param string $destination_directory translation destination directory
	 *
	 * @return bool
	 */
	protected function copyLanguageFiles(string $source_directory, string $destination_directory): bool {
		if (!is_dir($source_directory)) {
			return false;
		}
		
		if (!is_dir($destination_directory)) {
			mkdir($destination_directory, 0755, true);
		}
		
		$source_directory = Paths::sanitize($source_directory);
		$destination_directory = Paths::sanitize($destination_directory);
		
		$dh = new \DirectoryIterator($source_directory);
		
		/* @var $file_info \DirectoryIterator */
		foreach ($dh as $file_info) {
			if (!$file_info->isFile() || $file_info->getExtension() !== 'php') {
				continue;
			}
			
			copy($file_info->getPathname(), $destination_directory . $file_info->getFilename());
		}
		
		return true;
	}
	
	/**
	 * Remove snapshots if there are more than 10
	 *
	 * @return void
	 */
	protected function deleteOverflowSnapshots(): void {
		$snapshots = $this->getAll();
		if (count($snapshots) < 10) {
			return;
		}
		
		$keys = array_keys($snapshots);
		$overflow = array_slice($keys, 9);
		
		foreach ($overflow as $snapshot) {
			$this->delete($snapshot);
		}
	}
	
	/**
	 * Get the base directory of all snapshots
	 *
	 * @return string
	 */
	protected function getBaseDir(): string {
		$plugin = elgg_get_plugin_from_id('translation_editor');
		$dir = new EntityDirLocator($plugin->guid);
		
		return Paths::sanitize(elgg_get_data_path() . $dir->getPath() . 'snapshots/');
	}
}
