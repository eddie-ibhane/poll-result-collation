<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollingUnit extends Model
{
    protected $table = 'polling_unit';
    protected $primaryKey = 'uniqueid';
    protected $dates = [
        'date_entered'
    ];

    public function puResults()
    {
        return $this->hasMany('App\AnnouncedPUResult', 'polling_unit_uniqueid', 'uniqueid');
    }

    public function lgas()
    {
        return $this->belongsTo(LGA::class, 'lga_id', 'uniqueid');
    }


}
