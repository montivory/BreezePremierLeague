@extends('layouts.admin')
@section('title')
    : Prize Management
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
    <link rel="stylesheet" href="{{ asset('assets/css/prize.css') }}" type="text/css">
    <style>

    </style>
@endsection
@section('content')
    <div class="table-block mt-5">
        <div class="table-responsive">
            <table id="slip" class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th class="col-6">Prize</th>
                        <th class="col-3">Remaining</th>
                        <th class="col-3"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rewards as $reward)
                        <tr>
                            <td>
                                {{ $reward->nameen }}
                            </td>
                            <td>{{ $reward->amount }} / {{ $reward->quantity }}</td>
                            <td>
                                <a href="{{ route('admin.prize.view', ['id' => $reward->id]) }}" class="view">View Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    
@endsection
