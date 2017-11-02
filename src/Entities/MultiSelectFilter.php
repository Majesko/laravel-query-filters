<?php

namespace Apins\QueryFilters;

class MultiSelectFilter extends SelectFilter {
	protected static function getType() {
		return BaseFilter::TYPE_MULTI_SELECT;
	}
}
