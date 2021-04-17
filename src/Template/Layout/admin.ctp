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
use Cake\Core\Configure;
?>
<!DOCTYPE html>
<?php
	use Cake\Routing\Router;
	$path = Router::url('/', true);
?>

<html>
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	  <?php /* <title><?php echo $title_for_layout; ?></title> */ ?>
	  <title><?php echo __('TatvaTest'); ?></title>
	  <!-- Tell the browser to be responsive to screen width -->
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <!--  -->
	  <?php
			echo $this->Html->css([
				'../plugins/font-awesome/css/font-awesome.min.css',	//Font Awesome
				'../dist/css/adminlte.min.css',	//Theme style
				'../plugins/datepicker/datepicker3.css',	//Date Picker
				
				'admin/bootstrap.min.css',
				'custom.css',
				// 'bootstrap-multiselect.css',
			]);
		?>
		<!-- Google Font: Source Sans Pro -->
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
		<link rel="shortcut icon" href="<?= SITE_URL.'webroot/dist/img/favicon.ico' ?>" type="image/x-icon">
		<link rel="icon" href="<?= SITE_URL.'webroot/dist/img/favicon.ico' ?>" type="image/x-icon">
		<script src="<?= SITE_URL; ?>webroot/plugins/jquery/jquery.min.js"></script>
	</head>
	<body class="hold-transition sidebar-mini">
		<div class="wrapper">
			<div class="loader" style="display:none;">
				<?php echo $this->Html->image('loader.gif'); ?>
			</div>
			<?php echo $this->element('Admin/header'); ?>
			<div class="col-md-12"> <?php echo $this->Flash->render() ?></div>
			<div class="clearfix"> </div>
			<?php echo $this->element('Admin/sidebar'); ?>
			
			<?php echo $this->fetch('content'); ?>
			
		</div>
		<?php echo $this->element('Admin/footer'); ?>
	</body>
</html>