<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Events\RepositoryEntityDeleted;
use Prettus\Repository\Events\RepositoryEntityUpdated;

class MyRepository extends BaseRepository
{
    use RepositoryReplaceIgnore;

    public function model()
    {
        // TODO: Implement model() method.
    }

    /**
     * Delete multiple entities by given criteria.
     * hàm deleteWhere của l5-repository bị lỗi
     * @see https://github.com/andersao/l5-repository/pull/249/commits/8448d76edc518b118594afcdee71bcf5f893dfd4
     * @param array $where
     * @return int
     */
    public function deleteWhere(array $where)
    {
        $this->applyScope();

        $temporarySkipPresenter = $this->skipPresenter;
        $this->skipPresenter(true);

        $this->applyConditions($where);

        $deleted = $this->model->delete();

        event(new RepositoryEntityDeleted($this, $this->model->getModel()));

        $this->skipPresenter($temporarySkipPresenter);
        $this->resetModel();

        return $deleted;
    }


    /**
     * get deleted records
     * @see SoftDeletes
     * @return $this
     */
    public function withTrashed()
    {
        $this->model = $this->model->withTrashed();
        return $this;
    }

    public function restore($model)
    {
        $model->restore();

        //clear cache
        event(new RepositoryEntityUpdated($this, $model));
    }

    /**
     * Performs a 'replace' query with the data
     * @param  array $attributes
     * @return bool   t/f for success/failure
     */
    public function replaceORM(array $attributes = [])
    {
        return $this->replace($attributes);
    }

    /**
     * performs an 'insert ignore' query with the data
     * @param  array $attributes
     * @return bool   t/f for success/failure
     */
    public function insertIgnoreORM(array $attributes = [])
    {
        return $this->insertIgnore($attributes);
    }

    /**
     * @return mixed
     */
    public function truncate()
    {
        return $this->model->truncate();
    }
}
