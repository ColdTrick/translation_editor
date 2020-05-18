<?php

namespace ColdTrick\TranslationEditor\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 *
 * @author Jerome Bakker
 *
 */
class RemoveCustomKeysFolder implements AsynchronousUpgrade {

	/**
	 * {@inheritDoc}
	 */
	public function getVersion() {
		return 2020051801;
	}

	/**
	 * {@inheritDoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function shouldBeSkipped() {
		return !is_dir($this->getPath());
	}

	/**
	 * {@inheritDoc}
	 */
	public function countItems() {
		return (int) !$this->shouldBeSkipped();
	}

	/**
	 * {@inheritDoc}
	 */
	public function run(Result $result, $offset) {
		
		if (elgg_delete_directory($this->getPath())) {
			$result->addSuccesses();
		} else {
			$result->addFailures();
		}
		
		return $result;
	}
	
	/**
	 * Get custom keys path in dataroot
	 *
	 * @return string
	 */
	protected function getPath() {
		return elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR . 'custom_keys' . DIRECTORY_SEPARATOR;
	}
}
