<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>TatvaTest </title>
		<?php
			echo $this->Html->css([
				'../plugins/font-awesome/css/font-awesome.min.css',
			]);
		?>
		<!-- Theme style -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>webroot/dist/css/adminlte.min.css">
		<!-- iCheck -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>webroot/plugins/iCheck/flat/blue.css">
		<!-- Morris chart -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>webroot/plugins/morris/morris.css">
		<!-- jvectormap -->
		<link rel="stylesheet" href="<?php echo SITE_URL; ?>webroot/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
		
		<!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
		<link rel="shortcut icon" href="<?= SITE_URL.'webroot/dist/img/favicon.ico' ?>" type="image/x-icon">
		<link rel="icon" href="<?= SITE_URL.'webroot/dist/img/favicon.ico' ?>" type="image/x-icon">
		<script src="<?= SITE_URL; ?>webroot/js/front_home/jquery.min.js"></script>
		<script>
		$(document).on('click','.close', function(){
            $('.site_flash_msg').hide();
        });
		</script>
	</head>
	<body style="background-image: url('<?php echo SITE_URL; ?>/uploads/settings/<?php echo $settingData->admin_background; ?>')">
		<div class="login">
			<div class="login-bottom">
				<?php echo $this->fetch('content'); ?>
				<style>
					.site_flash_msg .alert.alert-danger, .site_flash_msg .alert.alert-success {
						opacity: 1;
						margin: 5px;
					}
					.alert-danger, .alert-error, .bg-danger, .label-danger, .alert-success, .bg-success, .label-success {
						padding: 3px 5px;
						border-radius: 4px;
					}
					.error-message {
						color: red;
					}
				</style>
			</div>
		</div>   
	</body>
</html>
