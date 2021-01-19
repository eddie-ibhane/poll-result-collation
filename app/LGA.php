<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LGA extends Model
{
    protected $table = 'lga';

    public function pollingUnits()
    {
        return $this->hasMany(PollingUnit::class, 'lga_id', 'lga_id');
    }
}
