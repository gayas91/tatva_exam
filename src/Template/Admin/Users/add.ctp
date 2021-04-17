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
					<h1>Add User</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Home</a></li>
						<li class="breadcrumb-item active">Add User</li>
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
						<?php echo $this->Form->create($user, ['type' => 'file', 'novalidate' => true,'id'=>'userForm']); ?>
						<div class="row">
							<div class="col-md-12">
								<div class="card-body">
									<div class="row">
									<?php
										echo $this->Form->input('first_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
										echo $this->Form->input('email', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Address'), 'label' => __('Email <span class="required">*</span>'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]); 
										
										echo $this->Form->input('password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('Password'), 'label' => __('Password <span class="required">*</span>'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
										echo $this->Form->input('gender', ['label'=>false,'class' => 'form-control', 'options' => Configure::read('GENDER_LIST'),'empty'=>'Select Gender',
										  'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label>  {{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label> <span class="required">*</span>{{content}}{{error}}</div></div>'
										  ]
										]);
									?>
							<!-- 	</div>
							</div>
							<div class="col-md-6">
								<div class="card-body"> -->
									<?php
										echo $this->Form->input('last_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name '),
										  'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
										echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number'),        
										  'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
										echo $this->Form->input('confirm_password', ['escape'=>false,'type' => 'password', 'class' => 'form-control', 'placeholder' => __('Confirm Password'), 'label' => __('Confirm Password <span class="required">*</span>'),
										    'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										    ]
										]);
										
										echo $this->Form->input('address', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Address'), 'label' => __('Address'),
										  'templates' => [
										  'inputContainer' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
										  'inputContainerError' => '<div class="col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
										  ]
										]);
									?>
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
<script>
	$(document).on('click', '.submit', function() {
		$(this).attr('disabled',true);
		$('#userForm').submit();
	});
</script>