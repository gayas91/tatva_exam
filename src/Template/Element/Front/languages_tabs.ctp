<?php /*
use Cake\Core\Configure;
if(isset($languages) && !empty($languages)){ ?>
<div class="form-group">
    <?php $current_language = Configure::read('DefaultLanguage'); foreach($languages as $language_id => $language_name){  ?>
	<div class="col-sm-4" id="<?php echo $language_id; ?>_tab" class="language_tab <?php if($current_language == $language_id){ ?> active <?php } ?>" >
		<label for="inputEmail3" class="control-label">
		   <?php
				$error_tab = ''; if(!isset($key)){ $key = '';}  if(isset($errors[$key]) && !empty($errors[$key]) && array_key_exists($language_name,$errors[$key])){ $error_tab = 'error_tab'; }
				echo $this->Html->link(
					ucfirst($language_name),'javascript:void(0)',['id'=>$language_name,'class'=>$error_tab,'escape' => false]
				);
		    ?>
		</label> 
	</div>
	<?php } ?>
</div>	
<?php } */ ?>
<style>.error_tab {background-color: #dc9191!important;}</style>


<?php use Cake\Core\Configure; 
if(isset($languages) && !empty($languages)){ ?>
<ul class="nav nav-tabs">
   <?php $current_language = Configure::read('DefaultLanguage'); foreach($languages as $language_id => $language_name){  ?>
	<li id="<?php echo $language_id; ?>_tab" class="language_tab <?php if($current_language == $language_id){ ?> active <?php } ?>"> 
		<?php
		$error_tab = ''; if(!isset($key)){ $key = '';}  if(isset($errors[$key]) && !empty($errors[$key]) && array_key_exists($language_name,$errors[$key])){ $error_tab = 'error_tab'; }
		echo $this->Html->link(
			ucfirst($language_name),'javascript:void(0)',['id'=>$language_name,'class'=>$error_tab,'escape' => false]
		);
		?>
	</li>
<?php } ?>	
</ul>
<?php }  ?>
