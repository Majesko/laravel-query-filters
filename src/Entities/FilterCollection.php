<?php

namespace Apins\QueryFilters;

use App\Exceptions\NonExistentFilterException;
use Illuminate\Support\Collection;
use LaravelBook\Ardent\Ardent;
use Illuminate\Support\Facades\Session;
use ValidationException;
use Illuminate\Support\Facades\Validator;

class FilterCollection extends Collection {
	/** @var Ardent */
	protected $model;

	public function __construct(Ardent $model, array $items = []) {
		$this->model = $model;
		$keyed_items = [];
		/** @var BaseFilter $filter */
		foreach ($items as $filter) {
			$keyed_items[$filter->getName()] = $filter;
		}
		parent::__construct($keyed_items);
	}

	public function getValues($take_empty = true) {
		$values = [];

		/** @var BaseFilter $filter */
		foreach ($this->items as $name => $filter) {
			$value = $filter->getValue();
			if ($take_empty || ( ! is_null($value) && $value != '')) {
				$values[$name] = $value;
			}
		}

		return $values;
	}

	public function getKeys() {
		return array_keys($this->items);
	}

	public function getValue($name) {
		if ( ! isset($this->items[$name])) {
			throw new NonExistentFilterException('Trying to get non-existent filter '.$name);
		}
		/** @var BaseFilter $filter */
		$filter = $this->items[$name];

		return $filter->getValue();
	}

	public function getChoices($name) {
		if ( ! isset($this->items[$name])) {
			throw new NonExistentFilterException('Trying to get choices for non-existent filter '.$name);
		}
		/** @var SelectFilter $filter */
		$filter = $this->items[$name];

		return $filter->getChoices();
	}

	public function validate($minimum_non_empty_values = 0) {
		$current_non_empty_values = 0;

		$filter_values = [];
		$validator_rules = [];

		/** @var BaseFilter $filter */
		foreach ($this->items as $name => $filter) {
			$filter_value = $filter->getValue();
			$filter_values[$name] = $filter_value;

			// only values that are null or '' are considered empty, 0 is a valid value
			if ( ! is_null($filter_value) && $filter_value != '') {
				$current_non_empty_values++;
			}

			// check that value is actually present in choices
			// skip empty values, they might not be required
// disabled for now - todo better solution to reduce filters to only actual values
//			if (isset($filter['choices']) && $filter['value'] != '') {
//				$values = (array) $filter_value;
//				foreach ($values as $id) {
//					if ( ! isset($filter['choices'][$id])) {
//						$this->model->errors()->add($name, trans('validation.belongs_to', ['attribute' => trans('field.'.$name)]));
//						throw new ValidationException($this->model);
//					}
//				}
//			}

			// todo add date validators

			// add rules to validator if there are any
			if ( ! empty($rules = $filter->getRules())) {
				$validator_rules[$name] = $rules;
			}
		}

		// we need a minimum applied filters to proceed
		if ($current_non_empty_values < $minimum_non_empty_values) {
			$this->model->errors()->add('minimum_non_empty_values', trans('validation.minimum_non_empty_values', [
				'count' => $minimum_non_empty_values,
			]));
			throw new ValidationException($this->model);
		}

		// create validator if there is anything to validate
		if ( ! empty($validator_rules)) {
			$validator = Validator::make($filter_values, $validator_rules);
			if ($validator->fails()) {
				$this->model->errors()->merge($validator->errors());
				throw new ValidationException($this->model);
			}
		}
	}

	public function store($prefix) {
		$this->validate();
		Session::set('filters.'.$prefix, $this->getValues());
	}
}
