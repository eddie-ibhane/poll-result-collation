@extends('layouts.app')

@section('content')
    <br><br>
    <form action="{{ url('/summed-lga-result') }}">
        <select name="lga" class="custom-select">
            <option value="">Select a lga</option>
            @foreach($lgas as $lgaId => $lgaName)
                <option value="{{$lgaId}}">{{$lgaName}}</option>
            @endforeach
        </select>
        <button type="submit" class="mt-4 btn btn-primary">Get Summed LGA Result</button>
    </form>

    @if($summedResult)
        <div>
            <br><br><br>
            <h2><center>Summed result for {{ $lg }}</center></h2>
            <br><br>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Party</th>
                    <th scope="col">Party Score</th>
                </tr>
                </thead>
                <tbody>
                @foreach($summedResult as $party => $result)
                    <tr>
                        <td>{{$party}}</td>
                        <td>{{$result}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
