<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefKonstruksi extends Model
{
    use SoftDeletes;
    protected $fillable = ['nama'];
}
