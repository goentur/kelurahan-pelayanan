<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefJenisBangunan extends Model
{
    use SoftDeletes;
    protected $fillable = ['nama'];
}
