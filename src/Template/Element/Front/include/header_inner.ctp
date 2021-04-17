<?php 
	use Cake\Routing\Router; 
	$path = Router::url('/', true); 
?>
<div class="top_nav_bar"> <a href="javascript:void(0);" class="left_menu_open_mob"><i class="icon-right_arrow"></i></a>
	<div class="page_title"></div>
	<div class="right_information">
		<ul>
			<li>     
				<div class="select-style language">
					<select>
						<option value="volvo"><?php echo __('Language'); ?> </option>
						<option value="1" <?php if($currentLanguage == 1) { echo "selected='selected'"; } ?>><?php echo __('English'); ?></option>
						<option value="2" <?php if($currentLanguage == 2) { echo "selected='selected'"; } ?>><?php echo __('Dutch'); ?></option>
						<option value="3" <?php if($currentLanguage == 3) { echo "selected='selected'"; } ?>><?php echo __('Arabic'); ?></option>
						<option value="4" <?php if($currentLanguage == 4) { echo "selected='selected'"; } ?>><?php echo __('Turkish'); ?></option>
					</select>
				</div>
			</li>
			<li><a href="javascript:;"><?php echo __('Welcome Store'); ?> <?php echo $user->first_name; ?> </a></li>
			
			<li class="user"> <a href="<?php echo Router::url(array('controller'=>'Users','action'=>'logout')); ?>"> <span class="name"><?php echo __('Logout'); ?></span> </a> </li>
		</ul>
	</div>
</div>