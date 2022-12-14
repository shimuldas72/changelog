<div class="row">
    <div class="col-md-6">
        <table class="table table-borderless detail-info-table">
            <tbody>
                <tr>
                    <th>Action Type</th>
                    <td>: {{ ucfirst(str_replace('_', ' ', $item->action_type)) }}</td>
                </tr>
                <tr>
                    <th>Table</th>
                    <td>: {{ $item->table_name }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-6">

    </div>
</div>

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
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-md-12">
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