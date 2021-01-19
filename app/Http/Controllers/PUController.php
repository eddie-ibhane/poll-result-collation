<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\LGA;
use App\Party;
use App\PollingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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

    public function summedLGAResult()
    {
        $lgas = LGA::pluck('lga_name', 'lga_id');

        $lga = request()->get('lga');

        $lg = '';
        $summedResult = [];

        if (!empty($lga)) {

            $pollingUnits = LGA::with('pollingUnits')
                ->where('lga_id', $lga)
                ->first();

            $lg = $pollingUnits->lga_name;

            $party = Party::pluck('partyname')->toArray();

            $result = collect($party)->map(function ($value) {
                return [$value => 0];
            })->all();

            $summedResult = Arr::collapse($result);

            foreach ($pollingUnits->pollingUnits as $pollingUnit) {
                if ($pollingUnit->puResults) {
                    foreach ($pollingUnit->puResults as $puResult) {
                        if (!in_array($puResult->party_abbreviation, $party)) {
                            $party[count($party)] = $puResult->party_abbreviation;
                            $summedResult[$puResult->party_abbreviation] = $puResult->party_score;
                        } else {
                            $summedResult[$puResult->party_abbreviation] += $puResult->party_score;
                        }
                    }
                }
            }
        }
        return view('summed_lga_result', compact('lgas', 'summedResult', 'lg'));
    }

}
