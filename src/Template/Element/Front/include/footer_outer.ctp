<?php 
	use Cake\Routing\Router; 
	$path = Router::url('/', true); 
?>
 <!-- Login Modal -->
<div class="modal fade login_modal" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo __('Login'); ?></h5>
				<button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="alert alert-danger loginError" style="display:none;">
				<?php echo __('Invalid email or password or your account in not activated, try again.'); ?>
			</div>
			<div class="modal-body loginArea">	
				<?php echo $this->Element('Front/User/login'); ?>
			</div>

		</div>
	</div>
</div>
<!-- End Login Modal -->

<!-- Forgot Password Modal -->
<div class="modal fade login_modal" id="forgotPasswordModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel"><?php echo __('Forgot Password'); ?></h5>
				<button type="button" class="close closeLogin" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="alert alert-danger loginError forgotErrorMsg" style="display:none;"></div>
			<div class="alert alert-success loginError forgotSuccessMsg" style="display:none;"></div>
			<div class="modal-body forgotData">	
				<?php echo $this->Element('Front/User/forgot'); ?>
			</div>

		</div>
	</div>
</div>

<div class="loader" style="display: none;">
	<img src="img/loading_halali.gif">
</div>

<!-- End Forgot Password Modal -->

<script type="text/javascript">
	$(document).ready(function() {
		bindLoginForm();
		forgotPassword();
	});
	function bindLoginForm() {
		$('#loginForm').submit(function(event) {
			event.preventDefault();
			var url = "<?php echo Router::url(array('controller'=>'Users','action'=>'login')); ?>";
			jQuery.ajax({
				type        : 'POST',
				url         : url,
				data        : $('#loginForm').serialize(),
				dataType    : 'json',
				encode		: true,
				cache		: false,
				beforeSend: function( xhr ) {
			    	$('.loader').show();
			  	},
			  	complete: function( xhr ) {
			    	$('.loader').hide();
			  	},
				success: function (response) {					
					if(response.error == 1) {
						$('.loginArea').html(response.message);
					}
					bindLoginForm();
					if(response.result == 1) {
						$('.loginError').hide();
						if(response.user == 2) {
							window.location.href = "<?php echo Router::url(array('controller'=>'Users','action'=>'dashboard')); ?>";
						}
					} else {
						$('.loginError').show();
					}
				}
			});
		});	
	}
	
	$('.forgotPassword').click(function() {
		jQuery.noConflict();
		$('#loginModal').modal('hide');
		$('#forgotPasswordModal').modal('show');
	});
	
	function forgotPassword() {
		$('#forgotForm').submit(function(event) {
			event.preventDefault();
			var url = "<?php echo Router::url(array('controller'=>'Users','action'=>'forgot')); ?>";
			jQuery.ajax({
				type        : 'POST',
				url         : url,
				data        : $('#forgotForm').serialize(),
				dataType    : 'json',
				encode		: true,
				cache		: false,
				success: function (response) {	
					if(response.result == 1) {
						$('.forgotSuccessMsg').text(response.message);
						$('.forgotSuccessMsg').show();
						$('.forgotErrorMsg').text('');
						$('.forgotErrorMsg').hide();
						$('.forgotData').html(response.data);
						$('#forgotInputEmail1').val('');
					} else {
						$('.forgotData').html(response.data);
						$('.forgotSuccessMsg').text('');
						$('.forgotSuccessMsg').hide();
						$('.forgotErrorMsg').text(response.message);
						$('.forgotErrorMsg').show();
					}	
					forgotPassword();
				}
			});
		});
	}
</script>
 
<footer>
  <div class="container">
    <ul>
      <li><a href="javascript:;"><?php echo __('Features'); ?></a> </li>
      <li><a href="javascript:;"><?php echo __('FAQs'); ?></a> </li>
    </ul>
    <p>&#169; <?php echo date('Y'); ?> <?php echo __('halali.com All rights reserved.'); ?></p>
  </div>
</footer>