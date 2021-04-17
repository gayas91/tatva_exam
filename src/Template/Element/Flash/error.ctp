<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
 
<div class="row site_flash_msg">
    <div class="col-md-12">
        <div class="alert alert-danger fade in">
            <a href="#" class="close" data-dismiss="alert">&times;</a>
            <?php echo $message; ?>
		</div>
	</div>
    <br>
</div>
