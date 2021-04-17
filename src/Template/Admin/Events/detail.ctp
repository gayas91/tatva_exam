<?php
$select_one = ['1'=>'Every','2'=>'Every Other','3'=>'Every Third','4'=>'Every Fourth'];
$select_two = ['1'=>'Day','2'=>'Week','3'=>'Month','4'=>'Year'];
$select_three = ['1'=>'First','2'=>'Second','3'=>'Third','4'=>'Fourth'];
$select_four = ['0'=>'Sun','1'=>'Mon','2'=>'Tue','3'=>'Wed','4'=>'Thu','5'=>'Fri','6'=>'Sat'];
$select_five = ['1'=>'Month','3'=>'3 Months','4'=>'4 Months','6'=>'6 Months','12'=>'Year'];
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Event detail</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/events">Event</a></li>
                        <li class="breadcrumb-item active">Event detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered">
								<tbody>
									<tr>
										<td><label>Title : </label> <?php echo $event->title; ?></td>
										<td><label>Start Date : </label> <?php echo $event->start_date; ?></td>
									</tr>
                                    <tr>
                                        <td><label>End Date : </label> <?php echo $event->end_date; ?></td>
                                        <td><label>Occurrence : </label> 
                                            <?php
                                            if($event->recurrence=='first_repeat') {
                                                echo $select_one[$event->select_one].' '.$select_two[$event->select_two];
                                            } else {
                                                echo $select_three[$event->select_three].' '.$select_four[$event->select_four].' of the '.$select_five[$event->select_five];
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><label>Total Recurrence Event : </label> <?php echo $this->Custom->totalRecurrence($event); ?></td>
                                        
                                    </tr>
									
									
								</tbody>
							</table>
						</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
</div>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/jquery.dataTables.js"></script>
<script src="<?= SITE_URL; ?>webroot/plugins/datatables/dataTables.bootstrap4.js"></script>
<script>
    $(function () {
        $("#example1").DataTable();
        $("#example2").DataTable();
    });
</script>
<style>
	div#example2_filter {
		margin-left: 280px;
	}
</style>