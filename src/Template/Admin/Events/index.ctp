<?php use Cake\Core\Configure; 
$select_one = ['1'=>'Every','2'=>'Every Other','3'=>'Every Third','4'=>'Every Fourth'];
$select_two = ['1'=>'Day','2'=>'Week','3'=>'Month','4'=>'Year'];
$select_three = ['1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth'];
$select_four = ['0'=>'Sun','1'=>'Mon','2'=>'Tue','3'=>'Wed','4'=>'Thu','5'=>'Fri','6'=>'Sat'];
$select_five = ['1'=>'Month','3'=>'3 Months','4'=>'4 Months','6'=>'6 Months','12'=>'Year'];

?>
<style type="text/css">
	.exportBtn {float: right;}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>List Events</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
                        <li class="breadcrumb-item active">List Events</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content admin_users">
        <div class="r o w">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                    
                    <div class="card-body">
						<div class="table-responsive">
							<table  class="table  table-bordered responsive" >
								<thead>
									<tr>
										<th>#</th>
										<th>Title</th>
										<th>Dates</th>
										<th>Occurrence</th>
										<th>Actions</th>
									</tr>
								</thead>
								<tbody>
									<?php
									if (!empty($users->count() > 0)) {
										$start	=	1;
										foreach ($users as $key => $value) { ?>
											<tr>
												<td> <?php echo $start; ?> </td>
												<td><?php echo $value->title; ?></td>
												<td><?php echo $value->start_date; ?> to <?php echo $value->end_date; ?></td>
												<td>
													<?php
													if($value->recurrence=='first_repeat') {
														echo $select_one[$value->select_one].' '.$select_two[$value->select_two];
													} else {
														echo $select_three[$value->select_three].' '.$select_four[$value->select_four].' of the '.$select_five[$value->select_five];
													}
													?>
												</td>
												<td class="center">
													<?php echo $this->Html->link('Edit', ['controller'=>'Events','action'=>'edit',$value->id],['escape'=>false,'class'=>'btn btn-success',]); ?>
													
													<?php echo $this->Html->link('View', ['controller'=>'Events','action'=>'detail',$value->id],['escape'=>false,'class'=>'btn btn-primary',]); ?>
													
													<?php echo $this->Html->link('Delete', ['controller'=>'Events','action'=>'delete',$value->id],['escape'=>false,'class'=>'btn btn-danger',]); ?>
													
												</td>
											</tr>
										<?php $start++;
										}
									} else { ?>
										<tr>
											<td colspan="9" style="text-align: center;"><?php echo __('No Record Found') ?></td>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
						<?php echo $this->element('Admin/pagination'); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal fade" id="myModal" role="dialog"></div>

<?php
	echo $this->Html->css(['admin/bootstrap-datepicker.min']);
	echo $this->Html->script(['admin/bootstrap-datepicker.min','admin/jquery-3.2.1','admin/loadingoverlay.min']);
?>
</div>
<script>
	
</script>




