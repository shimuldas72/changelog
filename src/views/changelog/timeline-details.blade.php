@extends('changelog::layouts.main')

@section('content')

    <div class="container-fluid">
                    
        <div class="row">
            <div class="col-lg-12">
                <div class="card-box">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="header-title mb-3 mt-4"><?= 'Timeline of '.strtoupper($log->table_name).' table Primary Key - '.$log->table_pk.' = '.$log->table_pk_value; ?></h4>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <?php
                    use Illuminate\Support\Facades\Config;
                    $user_name_column = Config::get('changelog')['name_column'];
                ?>
                <ul class="timeline">
                    <?php
                        if($items->count() > 0) {
                            foreach ($items as $key => $item) {
                    ?>
                                <li class="timeline_item <?= ($item->id == $log->id)?'active':''; ?>" data-id="<?= $item->id; ?>">
                                    <div>
                                        <h5 class="mb-0"><span class="action-type">{{ ucfirst($item->action_type) }}d</span> by {{ ($item->user)?$item->user->$user_name_column.' ('.$item->created_by.')':$item->created_by }}
                                        </h5>
                                        <p><small>on {{ date('F j, Y h:i:s A', strtotime($item->created_at)) }}</small></p>
                                    </div>
                                </li>
                    <?php
                            }
                        }
                    ?>
                </ul>
            </div>
            <div class="col-md-6 col-lg-8">
                <?php
                    if($items->count() > 0) {
                        foreach ($items as $key => $item) {
                ?>
                    <div class="card log_detail log_detail_<?= $item->id; ?> <?= ($item->id == $log->id)?'':'hide'; ?>">
                        <div class="card-body">
                            <table class="table table-borderless detail-info-table">
                                <tbody>
                                    <tr>
                                        <th style="width: 200px;">Action Type</th>
                                        <td>: {{ ucfirst(str_replace('_', ' ', $item->action_type)) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Table</th>
                                        <td>: {{ $item->table_name }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <br/>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th style="width: 50%;">Old Values</th>
                                        <th>New Values</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="vertical-align: top !important;">
                                            <?php
                                                if(count($item->old_value) > 0) {
                                            ?> 
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table class="table table-borderless mb-0">
                                                                <?php
                                                                    foreach($item->old_value as $a_key => $a_val) {
                                                                ?> 
                                                                    <tr>
                                                                        <th>{{ $a_key }}</th>
                                                                        <td>{!! $a_val !!}</td>
                                                                    </tr>     
                                                                <?php
                                                                    }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </td>
                                        <td style="vertical-align: top !important;">
                                            <?php
                                                if(count($item->new_value) > 0) {
                                            ?> 
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <table class="table table-borderless mb-0">
                                                                <?php
                                                                    foreach($item->new_value as $a_key => $a_val) {
                                                                ?> 
                                                                    <tr>
                                                                        <th>{{ $a_key }}</th>
                                                                        <td>{!! $a_val !!}</td>
                                                                    </tr>     
                                                                <?php
                                                                    }
                                                                ?>
                                                            </table>
                                                        </div>
                                                    </div>
                                            <?php
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-borderless detail-info-table">
                                <tbody>
                                    <tr>
                                        <th style="min-width: 150px;">Controller</th>
                                        <td>: {{ $item->controller }}</td>
                                    </tr>
                                    <tr>
                                        <th>Route Name</th>
                                        <td>: {{ $item->route_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Url</th>
                                        <td>: {{ $item->req_url }}</td>
                                    </tr>
                                    <tr>
                                        <th>Method</th>
                                        <td>: {{ $item->req_method }}</td>
                                    </tr>
                                    <tr>
                                        <th>IP</th>
                                        <td>: {{ $item->req_ip }}</td>
                                    </tr>
                                    <tr>
                                        <th>User Agent</th>
                                        <td>: {{ $item->req_user_agent }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php
                        }
                    }
                ?>

            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $(document).delegate('.timeline_item', 'click', function() {
                var id = $(this).attr('data-id');

                $('.timeline_item').removeClass('active');
                $(this).addClass('active');

                $('.log_detail').addClass('hide');
                $('.log_detail_'+id).removeClass('hide');
            })
        })
    </script>

    <style type="text/css">
        .hide {
            display: none;
        }
        .timeline_item {
            cursor: pointer;
        }
        .timeline_item.active > div{
            background: #ccc;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .timeline_item p{
            margin-bottom: 0;
        }
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


@endsection