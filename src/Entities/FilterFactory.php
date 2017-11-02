<?php

namespace Apins\QueryFilters;

use Carbon\Carbon;

class FilterFactory {
	protected $values;

	public function __construct(array $values) {
		$this->values = $values;
	}

	public function makeString($name, $default = null) {
		$value = array_get($this->values, $name, $default);

		return new StringFilter($name, $value);
	}

	public function makeSelect($name, array $choices, $dependant = null, $default = null) {
		$value = array_get($this->values, $name, $default);

		return new SelectFilter($name, $value, $choices, $dependant);
	}

	public function makeMultiSelect($name, array $choices, $dependant = null, $default = null) {
		$value = array_get($this->values, $name, $default);

		return new MultiSelectFilter($name, $value, $choices, $dependant);
	}

	public function makeBoolean($name, $dependant = null, $default = null) {
		return $this->makeSelect($name, trans('select.bool'), $dependant, $default);
	}

	public function makeDate($name, $default = null) {
		$value = array_get($this->values, $name, $default);

		return new DateFilter($name, $value);
	}

	public function makeDateFrom($name) {
		$default = Carbon::now()->firstOfMonth()->format('d.m.Y');

		return $this->makeDate($name, $default);
	}

	public function makeDateTo($name) {
		$default = Carbon::tomorrow()->format('d.m.Y');

		return $this->makeDate($name, $default);
	}
}
