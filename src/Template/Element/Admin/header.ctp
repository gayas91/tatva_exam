<!-- Navbar -->
<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
	<ul class="navbar-nav">
		<li class="nav-item">
			<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
		</li>
	</ul>
	<!-- Right navbar links -->
	<ul class="navbar-nav ml-auto">
		<li class="nav-item d-none d-sm-inline-block">
			<?php $name = $this->request->session()->read('Auth.Admin.first_name') . ' ' . $this->request->session()->read('Auth.Admin.last_name'); ?>
			<?php
				echo $this->Html->link('<i class="fa fa-sign-out-alt"></i>Logout', ['prefix' => 'admin', 'controller' => 'Users', 'action' => 'logout'], ['escape' => false, 'class' => 'nav-link','onclick'=>"return confirm('Are you sure you want to logout?')"]);
				?>
		</li>
	</ul>
</nav>