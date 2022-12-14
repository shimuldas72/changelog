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