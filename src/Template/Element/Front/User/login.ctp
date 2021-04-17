<?php echo $this->Form->create($user, array('id' => 'loginForm', 'url' => 'javascript:;'));    ?>
	<div class="form-group">
		<span class="icons_input"><img src="<?php echo $this->request->webroot; ?>css/images/mail_icon.png" alt="" /> </span>
		
		<?php
            echo $this->Form->input('email', ['label' => false, 'id' =>  'exampleInputEmail1', 'class' => 'form-control', 'required'=>false,'type' => 'email',  'placeholder' => 'Enter Email',
                'templates' => [
                    'inputContainer' => '<div class="formField">{{content}}</div>',
                    'inputContainerError' => '<div class="formField">{{content}}</div>{{error}}'
                ]
            ]);
		?>
	</div>
	
	<div class="form-group">
		<span class="icons_input"><img src="<?php echo $this->request->webroot; ?>css/images/password.png" alt="" /> </span>
		<?php
            echo $this->Form->input('password', ['label' => false, 'id' =>  'exampleInputPassword1', 'class' => 'form-control','required'=>false,'placeholder' => 'EnterPassword',
                'templates' => [
                    'inputContainer' => '<div class="formField">{{content}}</div>',
                    'inputContainerError' => '<div class="formField">{{content}}</div>{{error}}'
                ]
            ]);
			
        ?>
	</div>

	<div class="form-check">						
		<?php echo $this->Form->input('remember', ['type'=>'checkbox', 'id' => 'remember', 'label' => false,'templates'=>['inputContainer' => '<label class="checkboxcoustom">{{content}}<span class="checkmark"></span>Remember me</label>']]); ?>		
	</div>

	<div class="submit_btns">
		<?php
			echo $this->Form->input(__('Submit'), [
										'type'=>'submit',
										'label'=>false,
										'class'=>'btn btn-primary login_store',
										'templates' => ['submitContainer' => '{{content}}']  //important part
										]);
		?>
		<?php
			echo $this->Html->link(
									'Forgot Password?',
									'javascript:;',
									['class' => 'forgot forgotPassword']
								);
		?>
		<div class="clearfix"></div>
	</div>

<?php echo $this->Form->end(); ?>