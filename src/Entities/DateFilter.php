<?php

namespace Apins\QueryFilters\Entities;

class DateFilter extends BaseFilter {
	protected $time;
	protected $fast_date_with;

	public function __construct($name, $value, $time = '00:00', $rules = ['date', 'date_format:d.m.Y']) {
		parent::__construct(BaseFilter::TYPE_DATE, $name, $value, $rules);
		$this->time = $time;
	}

	protected function customFieldsToArray() {
		return [
			'time' => $this->time,
			'fast_date_with' => $this->fast_date_with,
		];
	}

	public function bindFastDate($name) {
		$this->fast_date_with = $name;

		return $this;
	}
}
