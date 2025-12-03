@extends('layouts.admin')
@section('title')
    : Receipt
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
                        <th class="col-1">Receipt ID</th>
                        <th class="col-2">Sender</th>
                        <th class="col-2">Uploaded date</th>
                        <th class="col-1">Price</th>
                        <th class="col-1">Edit By</th>
                        <th class="col-2">Last update</th>
                        <th class="col-2">Status</th>
                        <th class="col-1"></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        oTable = '';
        $(document).ready(function() {
            oTable = $('#slip').DataTable({
                ajax: {
                    url: `{{ route('admin.slip.list') }}?status={{ $status }}`,
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
                        data: 'receiptno',
                        name: 'slips.receiptno'
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'created_at',
                        name: 'slips.created_at'
                    },
                    {
                        data: 'total',
                        className: "dt-center"
                    },
                    {
                        data: 'editby'
                    },
                    {
                        data: 'updated_at',
                        name: 'slips.updated_at'
                    },
                    {
                        data: 'statustext'
                    },
                    {
                        data: null,
                        render: function(data) {
                            let toolsstr = ``;
                            if (data.btn == 'verify') {
                                toolsstr =
                                    `<a href="${data.edit}" class="btn btn-verify">Verify</a>`;
                            } else {
                                toolsstr = `<a href="${data.edit}" class="btn btn-view">View</a>`;
                            }
                            return toolsstr;
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [1, 3, 4, 5, 6, 7]
                }],
                order: [
                    [2, 'asc']
                ]
            });
        });
    </script>
@endsection
