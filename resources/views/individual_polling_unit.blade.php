@extends('layouts.app')

@section('content')
    <br><br>
    <form action="{{ url('/polling-unit-result') }}">
        <select name="pollingUnitUniqueId" class="custom-select">
            <option selected>Select a polling unit</option>
            @foreach($pollingUnits as $uniqueid => $pollingUnitName)
                <option value="{{$uniqueid}}">{{$pollingUnitName}}</option>
            @endforeach
        </select>
        <button type="submit" class="mt-4 btn btn-primary">Fetch Result</button>
    </form>

    @if($pollingUnitResult)
        <div>
            <br><br><br>
            <h2><center>Polling Unit Result for {{$pollingUnitResult->polling_unit_name}}</center></h2>
            <br><br>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Result ID</th>
                    <th scope="col">Party</th>
                    <th scope="col">Party Score</th>
                    <th scope="col">Entered By User</th>
                    <th scope="col">Date Entered</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pollingUnitResult->puResults as $result)
                    <tr>
                        <th scope="row">{{$result->result_id}}</th>
                        <td>{{$result->party_abbreviation}}</td>
                        <td>{{$result->party_score}}</td>
                        <td>{{$result->entered_by_user}}</td>
                        <td>{{$result->date_entered}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
