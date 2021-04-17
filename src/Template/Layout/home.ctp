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
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="<?= SITE_URL; ?>webroot/img/image/favi.png" type="image/gif" sizes="16x16">
	 <?php
			echo $this->Html->css([
				'front_home/web/vendor.bundle.css',
				'front_home/web/style.css'
			]);
		?>

	<title><?php echo __('TatvaTest: Fantasy Sports Game'); ?></title>
</head>

<body>

	<?php
		echo $this->element('front_home/header');
	?>
	<div class="col-md-12"> <?php echo $this->Flash->render() ?></div>
	<?php echo $this->fetch('content'); ?>
	<?php
		echo $this->element('front_home/footer');
	?>

    <script src="<?= SITE_URL; ?>webroot/js/vendor.bundle.js"></script>
	<script src="<?= SITE_URL; ?>webroot/js/script.js"></script>



  
</body>


</html>
