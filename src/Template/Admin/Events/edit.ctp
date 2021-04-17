<?php
	use Cake\Core\Configure;	
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
                    <h1>Edit Event</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/events">Event</a></li>
                        <li class="breadcrumb-item active">Edit Event</li>
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
                    	<?php echo $this->Form->create($event, ['type' => 'file', 'novalidate' => true,'id'=>'userForm']); ?>
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
									<input type="radio" name="recurrence" value="first_repeat" <?php if($event->recurrence=='first_repeat') { echo 'checked'; } ?> >Repeat
									<select name="select_one">
				                        <option value="1" <?php if($event->select_one=='1') { echo 'selected';} ?>>Every</option>
				                        <option value="2" <?php if($event->select_one=='2') { echo 'selected';} ?>>Every Other</option>
				                        <option value="3" <?php if($event->select_one=='3') { echo 'selected';} ?>>Every Third</option>
				                        <option value="4" <?php if($event->select_one=='4') { echo 'selected';} ?>>Every Fourth</option>
				                    </select>
				                    <select name="select_two">
				                        <option value="1" <?php if($event->select_two=='1') { echo 'selected';} ?>>Day</option>
				                        <option value="2" <?php if($event->select_two=='2') { echo 'selected';} ?>>Week</option>
				                        <option value="3" <?php if($event->select_two=='3') { echo 'selected';} ?>>Month</option>
				                        <option value="4" <?php if($event->select_two=='4') { echo 'selected';} ?>>Year</option>
				                    </select></br>


									<input type="radio" name="recurrence" value="second_repeat"  <?php if($event->recurrence=='second_repeat') { echo 'checked'; } ?>>Repeat on the
									<select name="select_three">
				                        <option value="1" <?php if($event->select_three=='1') { echo 'selected';} ?>>First</option>
								        <option value="2" <?php if($event->select_three=='2') { echo 'selected';} ?>>Second</option>
								        <option value="3" <?php if($event->select_three=='3') { echo 'selected';} ?>>Third</option>
								        <option value="4" <?php if($event->select_three=='4') { echo 'selected';} ?>>Fourth</option>
				                    </select>
				                    <select  name="select_four">
				                        <option value="0" <?php if($event->select_four=='0') { echo 'selected';} ?>>Sun</option>
									    <option value="1" <?php if($event->select_four=='1') { echo 'selected';} ?>>Mon</option>
									    <option value="2" <?php if($event->select_four=='2') { echo 'selected';} ?>>Tue</option>
									    <option value="3" <?php if($event->select_four=='3') { echo 'selected';} ?>>Wed</option>
									    <option value="4" <?php if($event->select_four=='4') { echo 'selected';} ?>>Thu</option>
									    <option value="5" <?php if($event->select_four=='5') { echo 'selected';} ?>>Fri</option>
									    <option value="6" <?php if($event->select_four=='6') { echo 'selected';} ?>>Sat</option>
				                    </select>
				                    of the

				                    <select name="select_five">
				                        <option value="1" <?php if($event->select_five=='1') { echo 'selected';} ?>>Month</option>
				                        <option value="3" <?php if($event->select_five=='3') { echo 'selected';} ?>>3 Months</option>
				                        <option value="4" <?php if($event->select_five=='4') { echo 'selected';} ?>>4 Months</option>
				                        <option value="6" <?php if($event->select_five=='6') { echo 'selected';} ?>>6 Months</option>
				                        <option value="12" <?php if($event->select_five=='12') { echo 'selected';} ?>>Year</option>
				                    </select>
									
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary submit">Submit</button>
						<?php echo($this->Html->link('<i class="fa fa-arrow-left"></i> Back', array('controller' => 'users','action' => 'index','?'=>$this->request->session()->read('sorting_query')), array('escape' => false, 'class' => 'btn btn-primary '))); ?>	

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#userForm').submit();
	});
</script>
