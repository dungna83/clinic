<?php

namespace App\Criteria;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class MyCriteriaCriteria
 * @package namespace App\Criteria;
 */
class MyCriteria implements CriteriaInterface
{
    protected $params;

    public function __construct($params = array())
    {
        $this->params = $params;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        // TODO: Implement apply() method.
    }
}
