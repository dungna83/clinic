<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\Doctor;

/**
 * Class UserRepositoryEloquent
 * @package namespace App\Repositories;
 */
class DoctorRepositoryEloquent extends MyRepository implements DoctorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Doctor::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
