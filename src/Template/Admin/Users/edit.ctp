<?php
	use Cake\Core\Configure;	
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit User</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?=SITE_URL?>admin/users">User</a></li>
                        <li class="breadcrumb-item active">Edit User</li>
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
                    	<?php echo $this->Form->create($user, ['type' => 'file', 'novalidate' => true,'id'=>'userForm']); ?>
                    	<div class="row">
							<?php
							echo $this->Form->input('first_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'First Name <span class="required">*</span>', 'placeholder' => __('First Name'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							echo $this->Form->input('last_name', ['maxlength'=>'30','escape'=>false,'class' => 'form-control','label'=>'Last Name <span class="required">*</span>','placeholder' => __('Last Name '),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							?>
					    </div>
					    <div class="row">
							<?php
							echo $this->Form->input('email', ['escape'=>false,'class' => 'form-control', 'placeholder' => __('E-Mail Address'), 'label' => __('Email <span class="required">*</span>'),
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							echo $this->Form->input('phone', ['escape'=>false,'class' => 'form-control','label'=>__('Phone Number <span class="required">*</span>'), 'placeholder' => __('Phone Number'),
							    
							    'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
							    ]
							]);
							?>
					    </div>
						<div class="row">
							<?php 
							echo $this->Form->input('gender', ['label'=>false,'class' => 'form-control', 'options' => Configure::read('GENDER_LIST'),'empty'=>'Select Gender',
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label>  {{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="gender">Gender</label> <span class="required">*</span>{{content}}{{error}}</div></div>'
								]
							]);

							echo $this->Form->input('address', ['type' => 'text', 'class' => 'form-control', 'placeholder' => __('Address'), 'label' => __('Address'),
								'templates' => [
								'inputContainer' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}</div></div>',
								'inputContainerError' => '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">{{content}}{{error}}</div></div>'
								]
							]);
							?>
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
