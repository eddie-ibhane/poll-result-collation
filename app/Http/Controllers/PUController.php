<?php

namespace App\Http\Controllers;

use App\PollingUnit;
use Illuminate\Http\Request;

class PUController extends Controller
{
    public function pollingUnits()
    {
        $puUniqueId = request()->pollingUnitUniqueId;

        $pollingUnits = PollingUnit::pluck('polling_unit_name', 'uniqueid');

        $pollingUnitResult = false;

        if ($puUniqueId) {
            $pollingUnitResult = PollingUnit::with('puResults')
                ->where('uniqueid', $puUniqueId)->first();
        }

        return view('individual_polling_unit', compact('pollingUnits', 'pollingUnitResult'));
    }
}
