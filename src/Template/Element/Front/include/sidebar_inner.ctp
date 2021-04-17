<?php 
	use Cake\Routing\Router; 
	$path = Router::url('/', true); 
?>
<div class="menu_nav ">
    <ul>
	
      <li class="<?php echo $this->General->activeNav('Users', array('dashboard')); ?>"><a href="<?php echo Router::url(array('controller'=>'Users','action'=>'dashboard')); ?>"><span class="icon"><i class="icon-dashboard"></i></span><span class="name"><?php echo __('Dashboard'); ?></span></a></li>
	  
      <li class="<?php echo $this->General->activeNav('Users', array('myAccount')); ?>"><a href="<?php echo Router::url(array('controller'=>'Users','action'=>'myAccount')); ?>"><span class="icon"><i class="icon-myaccount"></i></span><span class="name"><?php echo __('My Account'); ?></span></a></li>
	  
      <li class="<?php echo $this->General->activeNav('Users', array('changePassword')); ?>"><a href="<?php echo Router::url(array('controller'=>'Users','action'=>'changePassword')); ?>"><span class="icon"><i class="icon-changepassword"></i></span><span class="name"><?php echo __('Change Password'); ?></span></a></li>
	  
      <li class="<?php echo $this->General->activeNav('Products', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'Products','action'=>'index')); ?>"><span class="icon"><i class="icon-manage_inventory"></i></span><span class="name"><?php echo __('Manage Store Inventory'); ?></span></a></li>
	  
      <li class="<?php echo $this->General->activeNav('Orders', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'Orders','action'=>'index')); ?>"><span class="icon"><i class="icon-manageorders"></i></span><span class="name"><?php echo __('Manage Orders'); ?></span></a></li>

      <li class="<?php echo $this->General->activeNav('Payouts', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'Routes','action'=>'index')); ?>"><span class="icon"><i class="icon-manager"></i></span><span class="name"><?php echo __('Route Manager'); ?></span></a></li>
	  
      <li class="<?php echo $this->General->activeNav('DeliveryVans', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'DeliveryVans','action'=>'index')); ?>"><span class="icon"><i class="icon-vanmanage"></i></span><span class="name"><?php echo __('Delivery Van Manage'); ?></span></a></li>
	  
	   <li class="<?php echo $this->General->activeNav('DeliveryTimes', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'DeliveryTimes','action'=>'index')); ?>"><span class="icon"><i class="icon-deliverytime"></i></span><span class="name"><?php echo __('Manage Delivery time'); ?></span></a></li>    
	   
      <li class="<?php echo $this->General->activeNav('Payouts', array('index')); ?>"><a href="<?php echo Router::url(array('controller'=>'Payouts','action'=>'index')); ?>"><span class="icon"><i class="icon-manager"></i></span><span class="name"><?php echo __('Payout Manager'); ?></span></a></li>
	  
	  <li class="<?php echo $this->General->activeNav('Users', array('logout')); ?>"><a href="<?php echo Router::url(array('controller'=>'Users','action'=>'logout')); ?>"><span class="icon"><i class="icon-logout"></i></span><span class="name"><?php echo __('Logout'); ?></span></a></li>
	  
    </ul>
  </div>