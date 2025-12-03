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
    <div class="table-block mt-5">
        <div class="table-responsive">
            <table id="slip" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="col-4">Name</th>
                        <th class="col-2">Mobile No</th>
                        <th class="col-2">Total Spend</th>
                        <th class="col-2">User type</th>
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
    <script src="{{ asset('assets/js/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        oTable = '';
        $(document).ready(function() {
            oTable = $('#slip').DataTable({
                ajax: {
                    url: `{{ route('admin.member.list') }}`,
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
                        data: 'fullname',
                        name: 'members.firstname',
                    },
                    {
                        data: 'phone',
                        name: 'members.phone',
                    },
                    {
                        data: 'point',
                        name: 'member_points.point',
                        className: "dt-center",
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: null,
                        render: function(data) {
                            var toolsstr =
                                `<a href="${data.view}" class="btn btn-verify">View Detail</a>`;
                            return toolsstr;
                        }
                    }
                ],
                columnDefs: [{
                    orderable: false,
                    targets: [3, 4]
                }],
                order: [
                    [0, 'asc']
                ]
            });
        });

        const download = () => {
            $.ajax({
                url: '{{ route('admin.member.export') }}',
                type: 'POST',
                success: function(data) {
                    /*
                     * Make CSV downloadable
                     */
                    var downloadLink = document.createElement("a");
                    var fileData = ['\ufeff' + data];

                    var blobObject = new Blob(fileData, {
                        type: "text/csv;charset=utf-8;"
                    });

                    var url = URL.createObjectURL(blobObject);
                    downloadLink.href = url;
                    downloadLink.download = `members.csv`;

                    /*
                     * Actually download CSV
                     */
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                }
            });
        }
    </script>
@endsection
