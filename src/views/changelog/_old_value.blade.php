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