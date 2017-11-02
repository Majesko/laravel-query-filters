<?php

namespace Apins\QueryFilters\Entities;

class StringFilter extends BaseFilter {
	public function __construct($name, $value) {
		parent::__construct(BaseFilter::TYPE_STRING, $name, $value);
	}

	protected function customFieldsToArray() {
		return [];
	}
}
