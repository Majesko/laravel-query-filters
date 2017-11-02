<?php

namespace Apins\QueryFilters;

use Illuminate\Support\Contracts\ArrayableInterface;

abstract class BaseFilter implements ArrayableInterface {
	const TYPE_DATE = 'date';
	const TYPE_SELECT = 'select';
	const TYPE_MULTI_SELECT = 'multiselect';
	const TYPE_STRING = 'string';

	protected $type;
	protected $name;
	protected $value;
	protected $rules;

	public function __construct($type, $name, $value, array $rules = []) {
		$this->type = $type;
		$this->name = $name;
		$this->value = $value;
		$this->rules = $rules;
	}

	public function getName() {
		return $this->name;
	}

	public function getValue() {
		return $this->value;
	}

	public function getRules() {
		return $this->rules;
	}

	public function __toString() {
		return $this->toJson();
	}

	public function toJson($options = 0) {
		return json_encode($this->toArray(), $options);
	}

	public function toArray() {
		return array_merge([
				'type' => $this->type,
				'name' => $this->name,
				'value' => $this->value,
				'rules' => $this->rules,
			],
			$this->customFieldsToArray()
		);
	}

	abstract protected function customFieldsToArray();

	public function makeRequired() {
		$this->rules = array_merge(['required'], $this->rules);

		return $this;
	}
}
