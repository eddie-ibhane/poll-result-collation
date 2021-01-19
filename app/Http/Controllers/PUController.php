<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\LGA;
use App\Party;
use App\PollingUnit;
use App\Ward;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

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

    public function showAddPUResultForm()
    {
        $lgas = LGA::pluck('lga_name', 'lga_id');

        $partys = Party::pluck('partyname', 'id');

        return view('add_pu_result', compact('lgas', 'partys'));
    }

    public function getWards($id)
    {

        $wards = Ward::where("lga_id", $id)->pluck("ward_name","uniqueid");

        return json_encode($wards);

    }

    public function storePUResult(Request $request)
    {
        $validatedData = $request->validate([
            'lga' => 'required',
            'ward' => 'required',
            'pu_id' => 'required',
            'pu_number' => 'required',
            'pu_name' => 'required',
            'pu_description' => 'required',
            'party' => 'required|array',
            'party.*' => 'required|string',
        ]);

        $ward = Ward::where('uniqueid', $request->ward )->first();
        $lga = LGA::where('uniqueid', $request->lga)->first();

        $pollingUnit = new PollingUnit();
        $pollingUnit->ward_id = $ward->ward_id;
        $pollingUnit->uniquewardid = $ward->uniqueid;
        $pollingUnit->lga_id = $lga->lga_id;
        $pollingUnit->polling_unit_id = $request->pu_id;
        $pollingUnit->polling_unit_number = $request->pu_number;
        $pollingUnit->polling_unit_name = $request->pu_name;
        $pollingUnit->date_entered = Carbon::now()->toDateTimeString();
        $pollingUnit->polling_unit_description = $request->pu_description;
        $pollingUnit->save();

        $announcedPUResultData = [];

        foreach ($request->party as $partyname => $score) {

            $partyAbbreviation = substr(strtoupper($partyname), 0, 4);

            $temp = [
                'polling_unit_uniqueid' => $pollingUnit->uniqueid,
                'party_abbreviation' => $partyAbbreviation,
                'party_score' => $score,
                'entered_by_user' => Auth::user()->name,
                'date_entered' => Carbon::now()->toDateTimeString(),
                'user_ip_address' => $request->ip(),
            ];

            array_push($announcedPUResultData, $temp);
        }

        $data = $pollingUnit->puResults()->createMany($announcedPUResultData);

        return back()->with('status', 'Polling unit result successfully submitted');
    }


}
