@extends('changelog::layouts.main')

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">

            <form name="search-form" action="">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="term">Search Term</label>
                            <input type="text" name="term" id="term" value="<?= (isset($_GET['term']))?$_GET['term']:''; ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" value="Search" class="btn btn-block btn-md btn-info">
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>Type</th>
                            <th>Table</th>
                            <th>Old Values</th>
                            <th>New values</th>
                            <th>Action Performed By</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                            if($data->count() > 0){
                                foreach($data as $key => $item) {
                        ?>
                                    <tr>
                                        <td style="vertical-align: middle;">{{ $item->action_type }}</td>
                                        <td style="vertical-align: middle;">{{ $item->table_name }}</td>
                                        <td>
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
                                                                        <td>{{ $a_val }}</td>
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
                                        <td>
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
                                                                        <td>{{ $a_val }}</td>
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
                                        <td></td>
                                        <td style="vertical-align: middle;">
                                            <?= $item->created_at; ?>
                                        </td>
                                    </tr>
                        <?php
                                }
                            }
                        ?>

                    </tbody>
                </table>
            </div>
            <span class="pull-left">{{ $data->appends(request()->except('page'))->links() }}</span>

        </div>
    </div>
</div>

<style type="text/css">
    .table-borderless tr {
        background: none !important;
    }
    .table-borderless tr th,
    .table-borderless tr td {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
</style>
@stop