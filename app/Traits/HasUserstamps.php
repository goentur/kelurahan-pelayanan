<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait HasUserstamps
{
     public static function bootHasUserstamps()
     {
          static::creating(function ($model) {
               if (Auth::check()) {
                    $model->created_by = Auth::id();
                    $model->updated_by = Auth::id();
               }
          });

          static::updating(function ($model) {
               if (Auth::check()) {
                    $model->updated_by = Auth::id();
               }
          });

          static::deleting(function ($model) {
               if (Auth::check() && $model->isFillable('deleted_by')) {
                    $model->deleted_by = Auth::id();
                    $model->save();
               }
          });
     }
}
