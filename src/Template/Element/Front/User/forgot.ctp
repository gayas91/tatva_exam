<?php echo $this->Form->create($user, array('id' => 'forgotForm', 'url' => 'javascript:;'));    ?>

	<div class="form-group">
		<span class="icons_input"><img src="<?php echo $this->request->webroot; ?>css/images/mail_icon.png" alt="" /> </span>
		
		<?php
            echo $this->Form->input('email', ['label' => false, 'id' =>  'forgotInputEmail1', 'class' => 'form-control', 'required'=>false,'type' => 'email',  'placeholder' => 'Enter Email',
                'templates' => [
                    'inputContainer' => '<div class="formField">{{content}}</div>',
                    'inputContainerError' => '<div class="formField">{{content}}</div>{{error}}'
                ]
            ]);
		?>
	</div>

	<div class="submit_btns">
		<?php
			echo $this->Form->input(__('Submit'), [
										'type'=>'submit',
										'label'=>false,
										'class'=>'btn btn-primary',
										'id' => 'forgotSubmit',
										'templates' => ['submitContainer' => '{{content}}']
										]);
		?>
		<div class="clearfix"></div>
	</div>

<?php echo $this->Form->end(); ?>