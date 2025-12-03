@extends('layouts.admin')
@section('title')
    : Members
@endsection
@section('meta')
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="">
    <meta property="twitter:image" content="">
    <meta property="og:url" content="">
@endsection
@section('stylesheet')
    <style>

    </style>
@endsection
@section('content')
    <div class="col-12 mt-5">
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="round-0" role="tabpanel" aria-labelledby="round-0-tab">
                <div class="table-responsive">
                    <table id="slip" class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th class="col-6">Name</th>
                                <th class="col-3">Phone</th>
                                <th class="col-3">Total Spend</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($topspenders as $topspender)
                                <tr>
                                    <td>{{ $topspender->firstname }} {{ $topspender->lastname }}</td>
                                    <td>{{ $topspender->phone }}</td>
                                    <td>{{ $topspender->point }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}" type="text/javascript"></script>
@endsection
