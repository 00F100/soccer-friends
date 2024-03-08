<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Support\Str;

abstract class Model extends EloquentModel
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'uuid';

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
