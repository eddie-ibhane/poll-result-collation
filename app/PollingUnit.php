<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollingUnit extends Model
{
    protected $table = 'polling_unit';
    protected $primaryKey = 'uniqueid';

    public function puResults()
    {
        return $this->hasMany('App\AnnouncedPUResult', 'polling_unit_uniqueid', 'uniqueid');
    }


}
