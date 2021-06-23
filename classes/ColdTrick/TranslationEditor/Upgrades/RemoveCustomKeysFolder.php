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
	public function getVersion(): int {
		return 2020051801;
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
		return !is_dir($this->getPath());
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
	protected function getPath(): string {
		return elgg_get_data_path() . 'translation_editor' . DIRECTORY_SEPARATOR . 'custom_keys' . DIRECTORY_SEPARATOR;
	}
}
