<?php
/**
 *  HomesController
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Http\ServerRequest;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

/**
 * Cron Controller
 *
 */

class  HomesController extends AppController {
	
	public function initialize() {
		parent::initialize();
		$this->Auth->allow();
		$this->loadModel('Contents');
		$this->loadModel('SubscribeUsers');
    }
	
	public function index() 
	{	echo "<h2>Coming Soon</h2>"; die;
	}
	
	public function about_us() { 
		$this->viewBuilder()->layout('home');
	}
	
	/*public function pages($slug = '') 
	{
		if(!empty($slug)) {
			$this->loadModel('SubscribeUsers');
			$subscribe	=	$this->SubscribeUsers->newEntity();
			
			$this->viewBuilder()->layout('home');
			$data	=	$this->Contents->find()->where(['slug'=>$slug])->first();
			
			$success = '';
			if ($this->request->is(['patch', 'post', 'put'])) 
			{	
				$this->SubscribeUsers->patchEntity($subscribe, $this->request->getData(), ['validate'=>'Contact']);
				if (empty($subscribe->errors())) 
				{
					$to			=	Configure::read('support_email');
					$email		=	$this->request->getData('email');
					$from		=	$email;
					$subject	=	$this->request->getData('subject');
					$message	=	$this->request->getData('message') ;
					$message	.=	$this->request->getData('name') ;
					$success = 'Contact request sent successfully.';
					$this->sendMail($to, $subject, $message, $from);
					$this->Flash->success(__('Contact request sent successfully.'));
					$this->redirect(['controller'=>'cmspages','action' => 'contact']);
				} 
				else 
				{
					$this->Flash->error(__('Please correct errors listed as below'));
				}
			}
			$this->set(compact('data','slug','subscribe','success'));
		} else {
			return $this->redirect(['action' => 'index']);
		}
	}*/
	
}
