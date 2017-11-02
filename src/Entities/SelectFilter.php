<?php

namespace Apins\QueryFilters;

class SelectFilter extends BaseFilter {
	protected $choices;
	protected $dependant;

	public function __construct($name, $value, array $choices = [], $dependant = null) {
		// If we have only one variant — select it automatically
		// todo breaks filters sometimes, refactor
//		if (count($choices) === 1) {
//			$value = array_keys($choices)[0];
//		}

		parent::__construct(static::getType(), $name, $value);
		$this->choices = $choices;
		$this->dependant = $dependant;
	}

	public function getChoices() {
		return $this->choices;
	}

	protected static function getType() {
		return BaseFilter::TYPE_SELECT;
	}

	protected function customFieldsToArray() {
		return [
			'choices' => $this->choices,
			'dependant' => $this->dependant,
		];
	}
}
