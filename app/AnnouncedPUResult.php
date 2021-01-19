<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnnouncedPUResult extends Model
{
    protected $table = 'announced_pu_results';
    public $timestamps = false;

    protected $primaryKey = 'result_id';

    protected $fillable = [
        'polling_unit_uniqueid', 'party_abbreviation', 'party_score', 'entered_by_user', 'date_entered', 'user_ip_address'
    ];
}
