<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

class LeadingZero implements CastsInboundAttributes
{
    /**
     *
     * @var integer
     */
    protected $length;

    /**
     * LeadingZero constructor.
     *
     * @param int $length
     */
    public function __construct(int $length = 0)
    {
        $this->length = $length;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        return str_pad($value, $this->length, '0', STR_PAD_LEFT);
    }
}
