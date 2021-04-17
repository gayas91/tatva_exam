<?php
    echo $this->Form->control('product.sub_category_id', ['id' => 'product-sub-category', 'class' => 'form-control', 'options' => $subcategory, 'label' => false, 'type' => 'select',
                'templates' => [
                'inputContainer' => '<div class="form-group margin-left">{{content}}</div> '
                ]
        ]);
?>