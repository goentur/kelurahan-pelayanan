<?php

namespace App\Models\Ref;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefPenyampaianKeterangan extends Model
{
    use HasUuids, SoftDeletes;
    protected $fillable = ['nama', 'keterangan'];
}
