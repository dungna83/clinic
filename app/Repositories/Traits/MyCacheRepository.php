<?php
namespace App\Repositories\Traits;

use Prettus\Repository\Helpers\CacheKeys;
use Prettus\Repository\Traits\CacheableRepository;

trait MyCacheRepository
{
    // use CacheableRepository;

    // public function getCacheKey($method, $args = null)
    // {
    //     $args = strtolower(serialize($args));
    //     $criteria = $this->serializeCriteria();
    //     $key = sprintf('%s@%s-%s', get_called_class(), $method, md5($args . $criteria));
    //     CacheKeys::putKey(get_called_class(), $key);
    //     return $key;
    // }
}
