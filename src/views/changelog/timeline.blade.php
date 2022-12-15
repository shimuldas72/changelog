<?php
	use Illuminate\Support\Facades\Config;
	$user_name_column = Config::get('changelog')['name_column'];
?>
<div class="container mt-2 mb-2">
	<div class="row">
		<div class="col-md-12">
			<ul class="timeline">
				<?php
					if($items->count() > 0) {
						foreach ($items as $key => $item) {
				?>
							<li>
								<h5 class="mb-0"><span class="action-type">{{ ucfirst($item->action_type) }}d</span> by {{ ($item->user)?$item->user->$user_name_column.' ('.$item->created_by.')':$item->created_by }}
								</h5>
								<p><small>on {{ date('F j, Y h:i:s A', strtotime($item->created_at)) }}</small></p>
								@if($item->old_value)
									<?php
										$str = '<strong>Old Data: </strong><br/>';
										$i = 0;
										if(count($item->old_value) > 0) {
											foreach ($item->old_value as $akey => $aval) {
												$i++;

												if($i > 1){
													$str .= ', ';
												}
												$str .= '<strong>'.$akey.'</strong>'.': '.$aval;
											}
										} 
									?>
									<p style="border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;"><?= $str; ?></p>
								@endif

								@if($item->new_value)
									<?php
										$str = '<strong>New Data: </strong><br/>';
										$i = 0;
										if(count($item->new_value) > 0) {
											foreach ($item->new_value as $akey => $aval) {
												$i++;

												if($i > 1){
													$str .= ', ';
												}
												$str .= '<strong>'.$akey.'</strong>'.': '.$aval;
											}
										} 
									?>
									<p style="border: 1px solid #ccc; border-radius: 5px; padding: 5px 10px;"><?= $str; ?></p>
								@endif

							</li>
				<?php
						}
					}
				?>
			</ul>
		</div>
	</div>
</div>