<?php echo $this->html->image('404.png'); ?>
<p>&nbsp;</p>
<?php

if (isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
    echo $this->Html->link(__('Go To Home'), ['prefix' => 'admin', 'controller' => 'Users', 'action' => 'dashboard'], ['escape' => false, 'class' => 'hvr-shutter-in-horizontal']);
} else {
    echo $this->Html->link(__('Go To Home'), ['controller' => 'Users', 'action' => 'dashboard'], ['escape' => false, 'class' => 'hvr-shutter-in-horizontal']);
}
?>

