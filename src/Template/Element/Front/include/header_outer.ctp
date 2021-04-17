<?php 
	use Cake\Routing\Router; 
	$path = Router::url('/', true); 
?>
<header class="header-top">
  <div class="container"> <a href="javascript:;" class="logo"> <img src="<?php echo $this->request->webroot; ?>css/images/logo.jpg" alt="" /> </a>
    <nav class="navbar navbar-expand-lg navbar-light float-right">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse " id="navbarNavDropdown">
        <ul class="navbar-nav">
          <li class=" active"> <a class="nav-link" href="javascript:;"><?php echo __('Features'); ?> <span class="sr-only">(current)</span></a> </li>
          <li class=""> <a class="nav-link" href="javascript:;"><?php echo __("Faq's"); ?></a> </li>
          <!--<li class=" dropdown"> <a class="nav-link dropdown-toggle" href="javascript:;" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <?php //echo __('Language'); ?> </a>-->
            <div class="select-style language outer_lan">
				<!--<a class="dropdown-item" href="javascript:;"><?php echo __('English'); ?></a> 
				<a class="dropdown-item" href="javascript:;"><?php echo __('Dutch'); ?></a> 
				<a class="dropdown-item" href="javascript:;"><?php echo __('Arabic'); ?></a> 
				<a class="dropdown-item" href="javascript:;"><?php echo __('Turkish'); ?></a>-->
				<select>
					<option value="volvo"><?php echo __('Language'); ?> </option>
					<option value="1" <?php if($currentLanguage == 1) { echo "selected='selected'"; } ?>><?php echo __('English'); ?></option>
					<option value="2" <?php if($currentLanguage == 2) { echo "selected='selected'"; } ?>><?php echo __('Dutch'); ?></option>
					<option value="3" <?php if($currentLanguage == 3) { echo "selected='selected'"; } ?>><?php echo __('Arabic'); ?></option>
					<option value="4" <?php if($currentLanguage == 4) { echo "selected='selected'"; } ?>><?php echo __('Turkish'); ?></option>
				</select>
			</div>
          </li>
		  <?php
			$session = $this->request->session();
			if($session->check('storeUser')) {
				echo '<li class=""> <a class="nav-link" href="'.Router::url(array('controller'=>'Users','action'=>'dashboard')).'">'.__('My Account').'</a> </li>';
			} else {
				echo '<li class=""> <a class="nav-link" href="javascript:;" data-toggle="modal" data-target="#loginModal">'.__('Login').'</a> </li>';
			}
		  ?>
          
        </ul>
      </div>
    </nav>
  </div>
</header>