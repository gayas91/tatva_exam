<?php
	use Cake\Core\Configure; 
	use Cake\Routing\Router;
	$path = Router::url('/', true); 
	?>
<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Add Event</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add Event</li>
					</ol>
				</div>
			</div>
		</div>
	</section>
	<section class="content admins_add">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Fill Form</h3>
						</div>
						<?php echo $this->Form->create($event, ['type' => 'file', 'novalidate' => true,'id'=>'userForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="row">
									<?php
										echo $this->Form->input('title', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Event Title <span class="required">*</span>', 'placeholder' => __('Event Title'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);

										echo $this->Form->input('start_date', ["type"=>"text",'readonly'=>'readonly','class' => 'form-control datepicker-input start_date','label'=>'Start Date', 'placeholder' => __('Start Date'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);

										echo $this->Form->input('end_date', ["type"=>"text",'readonly'=>'readonly','class' => 'form-control datepicker-input end_date','label'=>'End Date', 'placeholder' => __('End Date'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
									?>
										<div class="col-sm-6 col-md-6">
											<div class="form-group">
												<label for="title">Recurrence </label></br>
												<input type="radio" name="recurrence" value="first_repeat" checked="checked">Repeat
												<select name="select_one">
							                        <option value="1">Every</option>
							                        <option value="2">Every Other</option>
							                        <option value="3">Every Third</option>
							                        <option value="4">Every Fourth</option>
							                    </select>
							                    <select name="select_two">
							                        <option value="1">Day</option>
							                        <option value="2">Week</option>
							                        <option value="3">Month</option>
							                        <option value="4">Year</option>
							                    </select></br>


												<input type="radio" name="recurrence" value="second_repeat">Repeat on the
												<select name="select_three">
							                        <option value="1">First</option>
											        <option value="2">Second</option>
											        <option value="3">Third</option>
											        <option value="4">Fourth</option>
							                    </select>
							                    <select  name="select_four">
							                        <option value="0">Sun</option>
												    <option value="1">Mon</option>
												    <option value="2">Tue</option>
												    <option value="3">Wed</option>
												    <option value="4">Thu</option>
												    <option value="5">Fri</option>
												    <option value="6">Sat</option>
							                    </select>
							                    of the

							                    <select name="select_five">
							                        <option value="1">Month</option>
							                        <option value="3">3 Months</option>
							                        <option value="4">4 Months</option>
							                        <option value="6">6 Months</option>
							                        <option value="12">Year</option>
							                    </select>
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer">
							<button type="submit" class="btn btn-primary submit">Submit</button>
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php
	echo $this->Html->css(['admin/bootstrap-datepicker.min']);
	echo $this->Html->css(['admin/bootstrap-datepicker.min','admin/jquery-3.2.1']);
?>
<script>
	$(function() {
		$(".start_date").datepicker({
			format : "yyyy-mm-dd",
			autoclose : true,
			//endDate : '+0d',
		}).on('changeDate', function (selected) {
			$('.end_date').val('');
			var minDate = new Date(selected.date.valueOf());
			$('.end_date').datepicker('setStartDate', minDate);
		});
		$(".end_date").datepicker({
			format : "yyyy-mm-dd",
			//endDate : '+0d',
			autoclose : true,
		}).on('changeDate', function (selected) {
			var maxDate = new Date(selected.date.valueOf());
			$('.start_date').datepicker('setEndDate', maxDate);
		});
	});
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#userForm').submit();
	});
</script>