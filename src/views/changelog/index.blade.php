@extends('changelog::layouts.main')

@section('content')

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        table.dataTable thead th, table.dataTable thead td{
            padding: 8px 10px !important
        }

        table.dataTable th, table.dataTable td { white-space: nowrap; }
        div.dataTables_wrapper {
            margin: 0 auto;
        }


    </style>

    <div class="container-fluid">
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @if (Session::has('message'))
                        <div class="col-lg-12">
                            <div class="alert alert-success" id="DeleteSuccess">
                                <button type="button" class="close" id="toggle" data-dismiss="modal" aria-hidden="true">×</button>
                                {{ Session::get('message') }}
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4 class="header-title mb-3 mt-4">All Change Logs </h4>
                                    </div>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped table-borderless table-hover brands-campaign-table" id="log-table">
                                        <thead>
                                        <tr>
                                            <th data-sortable="true" class="">ID</th>
                                            <th data-sortable="true" class="">Action Type</th>
                                            <th data-sortable="true" class="">Table</th>
                                            <th data-sortable="true" class="">Old Value</th>
                                            <th data-sortable="true" class="">New Value</th>
                                            <th data-sortable="false" class="">Created By</th>
                                            <th data-sortable="false" class="">Time</th>
                                            <th data-sortable="false" class=" text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Log Detail</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="timelineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Timeline</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <style type="text/css">
                .card .card-body {
                    padding: 10px;
                }
                .detail-info-table tr th,
                .detail-info-table tr td {
                    padding: 2px;
                    border: none;
                }
                #detailModal .card-body table tr td,
                #detailModal .card-body table tr th {
                    padding: 2px;
                }
                ul.timeline {
                    list-style-type: none;
                    position: relative;
                }
                ul.timeline:before {
                    content: ' ';
                    background: #d4d9df;
                    display: inline-block;
                    position: absolute;
                    left: 29px;
                    width: 2px;
                    height: 100%;
                    z-index: 400;
                }
                ul.timeline > li {
                    margin: 20px 0;
                    padding-left: 20px;
                }
                ul.timeline > li:before {
                    content: ' ';
                    background: white;
                    display: inline-block;
                    position: absolute;
                    border-radius: 50%;
                    border: 3px solid #22c0e8;
                    left: 20px;
                    width: 20px;
                    height: 20px;
                    z-index: 400;
                }
                ul.timeline .action-type {
                    color: #f00;
                }
            </style>
        </div>
    </div>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">

        $(document).ready(function() {
            $(document).delegate('.show_detail', 'click', function(e) {
                e.preventDefault();
                var url = $(this).attr('data-href');

                $.ajax({
                    url: url,
                    type:"GET",
                    cache: false,
                    success:function(data){
                        if(data.status == 'success'){
                          $('#detailModal .modal-body').html(data.data);
                          $('#detailModal').modal('show');
                        }
                    },
                });
            })

            $(document).delegate('.show_timeline', 'click', function(e) {
                e.preventDefault();
                var url = $(this).attr('data-href');

                $.ajax({
                    url: url,
                    type:"GET",
                    cache: false,
                    success:function(data){
                        if(data.status == 'success'){
                          $('#timelineModal .modal-body').html(data.data);
                          $('#timelineModal .modal-title').html(data.heading);
                          $('#timelineModal').modal('show');
                        }
                    },
                });
            })
        })

        var table = $('#log-table').DataTable({
            processing: true,
            serverSide: true,
            order: [],
            lengthMenu: [[5, 10, 20, 50, 75, 100, 200, 500, 1000, -1], [5, 10, 20, 50, 75, 100, 200, 500, 1000, 'All']],
            pageLength: 10,
            ajax: {
                url: "{{ route('changelog.ajaxList') }}",
                type: "GET",
                "data": function ( data ) {
                    data.status = $('#filterByStatus').val();
                }
            },
            globalSearch: true,
            scrollCollapse: true,
            columns: [
                {data: 'id', name: 'id', orderable: true, searchable: true},
                {data: 'action_type', name: 'action_type', orderable: true, searchable: true},
                {data: 'table_name', name: 'table_name', orderable: true, searchable: true},
                {data: 'old_value', name: 'old_value', orderable: false, searchable: true},
                {data: 'new_value', name: 'new_value', orderable: false, searchable: true},
                {data: 'created_by', name: 'created_by', orderable: true, searchable: true},
                {data: 'created_at', name: 'created_at', orderable: true, searchable: true},
                {data: 'action', name: 'action', orderable: false, searchable: false, className: 'flex text-center action'},
            ],
            columnDefs: [{
                targets: 0,
                orderable: false,
                checkboxes: true,
                className: 'select-checkbox',
            }],
            drawCallback:function (x) {
                // $("#selectAll").prop("checked", false);
            }
        });

    </script>

@endsection