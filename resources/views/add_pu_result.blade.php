@extends('layouts.app')

@section('content')
    <br><br>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row m-auto">
        <div class=" col-md-offset-2">
            @if(session()->has('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h2>Submit polling unit result</h2>
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="/add-pu-result">
                        {{ csrf_field() }}
                        <div class="form-group-sm">

                            <div class="col-md-4">
                                <select name="lga" class="form-control">
                                    <option value="">--Select LGA--</option>
                                    @foreach ($lgas as $lga_id => $lga)
                                        <option value="{{ $lga_id }}"> {{ $lga }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <br>
                            <div class="col-md-4">
                                <select name="ward" class="form-control">
                                    <option>--Ward--</option>

                                </select>
                            </div>

                            <br>
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="pollingUnitId">Polling Unit Id</label>
                                    <input type="number" name="pu_id" class="form-control" id="pollingUnitId" placeholder="polling unit id">
                                </div>
                                <div class="col-md-3">
                                    <label for="pollingUnitNumber">Polling Unit Number</label>
                                    <input type="text" name="pu_number" class="form-control" id="pollingUnitNumber" placeholder="Polling Unit Number">
                                </div>
                                <div class="col-md-3">
                                    <label for="pollingUnitName">Polling Unit Name</label>
                                    <input name="pu_name" class="form-control" id="pollingUnitName" placeholder="Polling Unit Name">
                                </div>
                                <div class="col-md-3">
                                    <label for="pollingUnitName">Polling Unit Description</label>
                                    <input name="pu_description" class="form-control" id="pollingUnitName" placeholder="Polling Unit Description">
                                </div>
                            </div>

                            <br>

                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">Party Name</th>
                                    <th scope="col">Party Score</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($partys as $id => $party)
                                    <tr>
                                        <td><input  value="{{$party}}" disabled class="form-control col-md-4" id="pollingUnitName" placeholder="Enter Party Name"></td>
                                        <td><input name="party[{{strtolower($party)}}]" class="form-control col-md-4" id="pollingUnitName" placeholder="Enter Party Score"></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="col-md-2"><span id="loader"><i class="fa fa-spinner fa-3x fa-spin"></i></span>
                            </div>
                        </div>
                        <button class="btn btn-primary">Add Result</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            $('select[name="lga"]').on('change', function () {

                const lgaId = $(this).val();

                if (lgaId) {
                    $.ajax({
                        url: '/ward/get/' + lgaId,
                        type: "GET",
                        dataType: "json",
                        beforeSend: function () {
                            $('#loader').css("visibility", "visible");
                        },

                        success: function (data) {

                            $('select[name="ward"]').empty();

                            $.each(data, function (key, value) {

                                $('select[name="ward"]').append('<option value="' + key + '">' + value + '</option>');

                            });
                        },
                        complete: function () {
                            $('#loader').css("visibility", "hidden");
                        }
                    });
                } else {
                    $('select[name="ward"]').empty();
                }

            });

        });
    </script>
@endsection
