<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;


class Doctor extends Model implements Transformable
{
    use TransformableTrait;
    use SoftDeletes;

    protected $table = 'doctors';
    protected $connection = 'mysql';
    protected $fillable = ['name', 'email', 'password', 'phone', 'address'];


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

}
