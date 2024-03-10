<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;

abstract class Model extends EloquentModel
{
    use HasFactory;

    /**
     * Disable incrementing for models
     * @var bool
     */
    public $incrementing = false;

    /**
     * Key type UUID for primary keys
     * @param string
     */
    protected $keyType = 'uuid';

    /**
     * Change boot method for use UUID
     */
    protected static function boot()
    {
      parent::boot();
      static::creating(function ($model) {
          if (empty($model->{$model->getKeyName()})) {
              $model->{$model->getKeyName()} = (string) Str::uuid();
          }
      });
    }
}
