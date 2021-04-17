<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Behavior;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;

/**
 * Description of CommonBehavior
 *
 * @author vijayj
 */
class CommonBehavior extends Behavior{
    //put your code here
    
    public function saveD(Entity $entity){
        pr($entity);die;
    }
}
