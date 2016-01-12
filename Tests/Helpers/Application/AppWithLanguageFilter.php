<?php
/**
 * @package     FOF
 * @copyright   2010-2016 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license     GNU GPL version 2 or later
 */

namespace FOF30\Tests\Helpers\Application;

class AppWithLanguageFilter
{
    protected $languageFilter;

    public function __construct($filter)
    {
        $this->languageFilter = $filter;
    }

    public function getLanguageFilter()
    {
        return $this->languageFilter;
    }
}
