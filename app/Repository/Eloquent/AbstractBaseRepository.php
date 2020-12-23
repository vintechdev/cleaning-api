<?php

namespace App\Repository\Eloquent;

use App\Exceptions\Repository\ModelPersistenceException;
use App\Repository\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AbstractBaseRepository
 * @package App\Repository\Eloquent
 */
abstract class AbstractBaseRepository implements EloquentRepositoryInterface
{
    /**
     * @return string
     */
    abstract protected function getModelClass() : string;

    /**
     * @return Model
     */
    public function getModel() : Model
    {
        $modelClass = $this->getModelClass();
        return new $modelClass;
    }

    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes): Model
    {
        if (
            $this
            ->getModel()
            ->setRawAttributes($attributes)
            ->save()
        ) {
            return $this->getModel();
        }

        throw new ModelPersistenceException('Unable to create entry to db');
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): Model
    {
        return $this->getModelClass()::find($id);
    }
}