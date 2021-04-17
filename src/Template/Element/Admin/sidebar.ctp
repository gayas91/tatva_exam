<?php
	$params	=	$this->request->params;
	$cont	=	$params['controller'];
	$actn	=	$params['action'];
	
?>
<script>
	$(document).ready(function() {
		$('.sidebar-dark-primary').mouseover(function() {
			$('.sidebar-dark-primary').addClass('logo_change');
		});
		$('.sidebar-dark-primary').mouseleave(function() {
			$('.sidebar-dark-primary').removeClass('logo_change');
		});
	});
</script>
<style>
	.cric_sub ul{
		margin-left: 30px;
	}
	.cric_sub ul li a,.cric_sub ul li a i{
		font-size:14px !important;
	}
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
	<a href="<?= SITE_URL."admin" ?>" class="brand-link" style="text-align: center;">
		Admin Panel
	</a>
	<!-- Sidebar -->
	<div class="sidebar">
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
				<li class="nav-item">
					<a href="<?= SITE_URL."admin" ?>" class="nav-link <?php if(($cont=='Users') && ($actn=='dashboard')){ echo 'active'; }?>">
						<i class="nav-icon fa fa-dashboard"></i> 
						<p> Dashboard </p>
					</a>
				</li>

				<li class="nav-item has-treeview <?php if(($cont=='Events') && (($actn=='index') || ($actn=='add'))){ echo 'menu-open'; }?>">
					<a href="javascript:void(0)" class="nav-link <?php if(($cont=='Events') && (($actn=='index') || ($actn=='add'))){ echo 'active'; }?>">
						<i class="nav-icon fa fa-users"></i>
						<p> Events <i class="right fa fa-angle-left"></i> </p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/events"?>" class="nav-link <?php if(($cont=='Events') && ($actn=='index')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>List</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?=SITE_URL."admin/events/add"?>" class="nav-link <?php if(($cont=='Events') && ($actn=='add')){ echo 'active'; }?>">
								<i class="fa fa-circle-o nav-icon"></i> 
								<p>Add</p>
							</a>
						</li>
					</ul>
				</li>
				
				
			</ul>
		</nav>
	</div>
</aside>