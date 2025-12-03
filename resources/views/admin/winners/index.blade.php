@extends('layouts.admin')
@section('title')
    : Winners
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
    <div class="table-block mt-5">
        <div class="table-responsive">
            <table id="slip" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-6">Name</th>
                        <th class="col-2">Mobile No</th>
                        <th class="col-2">Time</th>
                        <th class="col-2"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('/js/dataTables.bootstrap5.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        oTable = '';
        $(document).ready(function() {
            oTable = $('#slip').DataTable({
                ajax: {
                    url: `{{ route('adminwinnerlist') }}`,
                    dataType: 'json',
                    type: 'GET'
                },
                processing: true,
                serverSide: true,
                pageLength: 25,
                search: {
                    return: true,
                },
                columns: [{
                        data: 'fullname'
                    },
                    {
                        data: 'phone'
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: null
                    }
                ],
                columnDefs: [
                    {
                        orderable: false,
                        targets: [3]
                    },
                    {
                        targets: 3,
                        render: function(data) {
                            var toolsstr = `<a href="${data.view}" class="btn btn-verify">View Detail</a>`;
                        return toolsstr;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });
        });
    </script>
@endsection
