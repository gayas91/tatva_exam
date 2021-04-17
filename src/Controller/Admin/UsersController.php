<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersController
 *
 * @author GayasuddinK
 */

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\Routing\Router;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Cake\Collection\Collection;

class UsersController extends AppController {

    public function initialize() {
        parent::initialize();
        $this->Auth->allow(['forgotPassword','resetPassword']);
        $this->Auth->allow('resetForgotPassword');
        $this->loadComponent('Cookie');	
    }
	
	public function login() {
		$this->viewBuilder()->layout('admin_login');
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$user	=	$this->Users->newEntity();
		if ($this->request->is('post')) {
			$user	=	$this->Users->newEntity();
			$user	=	$this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'adminLogin']);
			if (empty($user->errors())) {
					$user	=	$this->Auth->identify();
					if($user['role_id'] == 3) {
						$user['is_login']	=	1;
						$user['last_login']	=	date('Y-m-d H:i:s');
						$subAdmin	=	$this->Users->newEntity();
						$this->Users->patchEntity($subAdmin, $user);
						$this->Users->save($subAdmin);
					}
					if($user) {
						if ($this->request->data['remember'] == 1) {
							$cookie = array();
							$cookie['username'] = $this->request->data['email'];
							$cookie['password'] = $this->request->data['password'];
							$cookie['remember'] = $this->request->data['remember'];
							$this->Cookie->write('rememberMe', $cookie, true, "1 week");
							$cookie = $this->Cookie->read('rememberMe');
						} else {
							$this->Cookie->delete('rememberMe');
						}
						$this->Auth->setUser($user);
						return $this->redirect($this->Auth->redirectUrl());
					}
					// pr(__('Invalid email or password, try again'));die;
					$this->Flash->error(__('Invalid email or password, try again'));
					$cookie = $this->Cookie->read('rememberMe');

			} else {
				$this->Flash->error(__('Please correct errors listed below'));
			}
		}
		$cookie = $this->Cookie->read('rememberMe');
		if (!empty($cookie)) {
			if ($cookie['remember'] == 1) {
				$this->request->data['email'] = $cookie['username'];
				$this->request->data['password'] = $cookie['password'];
				$this->request->data['remember'] = $cookie['remember'];
			} else {
				$this->Cookie->delete('rememberMe');
			}
		}

		$this->set(compact('user'));
	}

    public function dashboard() {
		$this->loadModel('Users');
		$authUser		=	$this->Auth->user();
		$subadminUser	=	$this->Users->find()->where(['role_id'=>3,'is_login'=>1])->toArray();
		$this->set(compact('authUser','subadminUser'));
    }

    public function logout() {
       // print_r($this->Auth->logout());die;
        return $this->redirect($this->Auth->logout());
    }

    public function userList() {

        $this->set('title_for_layout', __('Add User'));
        $user = $this->Users->newEntity();
         $this->set(compact('user'));

        
    }
	
	public function add() {
        $this->set('title_for_layout', __('Add User'));
        $user = $this->Users->newEntity();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'AddEditUser']);
            if (empty($user->errors())) {
                $user->role_id = '2';
                $user->status = 1;
                $user->refer_id = $this->Users->getReferId();
                $user->otp = $this->Users->getOTP();
				$user->created	=	date("Y-m-d H:i:s");
				$user->modified	=	date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    
                    $this->Flash->success(__('User has been added'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('User could not be added. Please, try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('user'));

    }

    public function index() {
        if (!empty($this->request->query)) {
            $this->request->session()->write('sorting_query', $this->request->query);
        }
        $this->set('title_for_layout', __('List Users'));
        $this->request->session()->delete('sorting_query');
        if (!empty($this->request->query)) {
			$this->request->session()->write('sorting_query', $this->request->query);
        }
        $role_id=	Configure::read('ROLES.User');
        $limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
        if ($this->request->is('Ajax')) {
            $this->viewBuilder()->layout(false);
        }
		
        $query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'Users.status !='=>2]);
		if(isset($this->request->query) && $this->request->query('unverified') != '') {
			$unverified	=	$this->request->query('unverified');
		} else {
			$unverified	=	'';
		}
        if(isset($this->request->params['?']['status'])){
            $pc = $this->request->params['?']['status'];
            if($pc=='deactive'){
            	$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'Users.status' => '0']);
            }
            if($pc=='active'){
            	$date    =   date('Y-m-d');
            	$this->loadModel('PlayerTeamContests');
            	//$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'Users.status' => '1']);

            	$query = $this->Users->find()
                    ->join([
                        'player_team_contests' => [
                            'table' => 'player_team_contests',
                            'type' => 'LEFT',
                            'conditions' => '(Users.id = player_team_contests.user_id)'
                        ]
                    ])
					->order(['Users.id'=>'DESC'])
					->WHERE(['Users.role_id' => $role_id,'DATE(player_team_contests.created)' => $date])->GROUP('player_team_contests.user_id');
            }
            if($pc=='new'){
            	$date    =   date('Y-m-d');
            	$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'DATE(Users.created)' => $date]);
            }
            if($pc=='unverified'){
            	$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
            }
        }
		if(isset($this->request->query['unverified']) && ($this->request->query['unverified']=='checked')){
        	$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' => $role_id,'OR'=>['PenAadharCard.is_verified'=>INACTIVE,'BankDetails.is_verified'=>INACTIVE]])->contain(['PenAadharCard','BankDetails']);
        }
        $users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Users.id'=>'DESC'],
			'group'		=>	['Users.id'],
			'contain'	=>	['PenAadharCard','BankDetails']
		]);
		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
        $this->set(compact('users','page','unverified','pc'));
    }

    public function status($id = NULL) {
        $user = $this->Users->get($id);
        $status = ($user->status == 0) ? 1 : 0;
        $user->status = $status;
        //pr($user);die;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('User status has been changed'));
            return $this->redirect($this->referer());
        }
        $this->Flash->error(__('User status could not be changed, please try again.'));
    }

    public function edit($id = NULL) {
        $this->set('title_for_layout', __('Edit User'));
        try {
            $user = $this->Users->get($id);
        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'AddEditUser']);
            if (empty($user->errors())) {
				$user->modified	=	date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('User has been updated'));
                    return $this->redirect(['action' => 'index', '?' => $this->request->session()->read('sorting_query')]);
                }
                $this->Flash->error(__('User could not be added, please try again.'));
            } else {
                $this->Flash->error(__('Please correct errors listed as below.'));
            }
        }
        $this->set(compact('user'));
    }
	
	public function detail($id = NULL) {
        $this->set('title_for_layout', __('User Detail'));
        try {
            $user = $this->Users->get($id);

        } catch (\Throwable $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        } catch (\Exception $e) {
            $this->Flash->error(__('Invaild attempt.'));
            return $this->redirect(['action' => 'index']);
        }
        $history	=	array();
        $kabbadi	=	array();
        // pr($kabbadi);die;
		$this->loadModel('ReferalCodeDetails');
        $friends = $this->ReferalCodeDetails->find()->where(['refered_by'=>$id])->count();
        
        $this->set(compact('user','history','friends','kabbadi'));
    }

    public function delete($id = null) {
        if (isset($id) && !empty($id))
        {
            $entity = $this->Users->get($id);
            if($this->Users->delete($entity)) {
                $this->Flash->success(__('User has been successfully deleted.'));
                return $this->redirect(['action' => 'index']);
            }else
            {
                $this->Flash->error(__('Unable to delete User, please try again.'));
                return $this->redirect(['action' => 'index']);
            }
        }else
        {
            $this->Flash->error(__('Unable to delete User, please try again.'));
            return $this->redirect(['action' => 'index']);
        }
    }
	
	public function forgotPassword() {
		$this->viewBuilder()->layout('admin_login');
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$this->loadModel('EmailTemplates');
		if($this->request->is(['post'])) {
			$email		=	$this->request->getData('email');
			if(!empty($email)) {
				$userData	=	$this->Users->find()->where(['email LIKE'=>$email,'role_id IN'=>[1,3],'status'=>ACTIVE])->first();
				if(!empty($userData)) {
					$template	=	$this->EmailTemplates->find()->where(['subject'=>'forgot_password'])->first();
					
					$to			=	$email;
					$from		=	Configure::read('Admin.setting.admin_email');
					$subject	=	$template->email_name;
					$validString=	time().base64_encode($email);
					$resetUrl	=	SITE_URL.'admin/users/reset-password/'.$validString;
					$resetLink	=	'<a href="'.$resetUrl.'">Click Here</a>';
					$message	=	str_replace(['{{user}}','{{link}}'],[$userData->first_name,$resetLink],$template->template);
					$this->sendMail($to, $subject, $message, $from);
					// if($this->sendMail($to, $subject, $message, $from)) {
						$userData->verify_string	=	$validString;
						$this->Users->save($userData);
						$this->Flash->success(__('Reset link has been sent on your email, please check your mail to reset password.',true));
					// } else {
						// $this->Flash->error(__('Could not send email.',true));
					// }
					$this->redirect(['controller'=>'users','action'=>'login']);
				} else {
					$this->Flash->error(__('Email does not exists.',true));
				}
			} else {
				$this->Flash->error(__('Email is required.',true));
			}
			
		}
	}
	
	public function resetPassword($validString = null) {
		$this->viewBuilder()->layout('admin_login');
		if(empty($validString)) {
			$this->Flash->error(__('Invalid reset url',true));
			$this->redirect(['controller'=>'users','action'=>'login']);
		}
		$user	=	$this->Users->find()->where(['verify_string LIKE'=>$validString,'role_id IN'=>[1,3],'status'=>ACTIVE])->select(['verify_string'])->first();
		if(empty($user)) {
			$this->Flash->error(__('Invalid reset url',true));
			$this->redirect(['controller'=>'users','action'=>'login']);
		}
		if($this->request->session()->check('Auth.Admin')) {
			return $this->redirect($this->Auth->redirectUrl());
		}
		$userData = '';
		$this->loadModel('EmailTemplates');
		if($this->request->is(['post','put','patch']))  {
			// $email		=	$validString;
			$userData	=	$this->Users->find()->where(['verify_string LIKE'=>$validString,'role_id IN'=>[1,3],'status'=>ACTIVE])->first();
			if(!empty($userData)) {
				$this->Users->patchEntity($userData,$this->request->getData(), ['validate'=>'AdminResetPassword']);
				if(!$userData->getErrors())  {
					$userData->verify_string	=	'';
					if($this->Users->save($userData))  {
						$this->Flash->success(__('Password has been reset successfully.',true));
						$this->redirect(['controller'=>'users','action'=>'login']);
					}
				} else {
					$this->Flash->error(__('Correct below detail.',true));
				}
				
			}
		}
		$this->set(compact('userData'));
	}
	
	public function changePassword() {
		$this->set('title_for_layput','Change Password');
		$session	=	$this->request->session();
		$authUserId	=	$session->read('Auth.Admin.id');
		$user		=	$this->Users->get($authUserId);
		if($this->request->is(['post','put','patch'])) {
			$user		=	$this->Users->get($authUserId);
			$this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'adminChangePassword']);
			if(!$user->getErrors()) {
				if($this->Users->save($user)) {
					$this->Flash->success(__('Password has been changed successfully.',true));
					$this->redirect(['controller'=>'users','action'=>'changePassword']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('authUser','user'));
	}
	
	public function profile() {
		$this->set('title_for_layput','Change Password');
		$session	=	$this->request->session();
		$authUser	=	$session->read('Auth.Admin.id');
		$user		=	$this->Users->get($authUser);
		if($this->request->is(['post','put'])) {
			$this->Users->patchEntity($user, $this->request->getData(), ['validate'=>'updateProfile']);
			if(!$user->getErrors()) {
				if($this->Users->save($user)) {
					$this->Flash->success(__('Profile has been updated successfully.',true));
					$this->redirect(['controller'=>'users','action'=>'profile']);
				}
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('user'));
	}
	
	public function verifyAccountEmail($verifyStr = null) {
		$this->loadModel('Users');
		$user	=	$this->Users->find()->where(['verify_string'=>$verifyStr,'status'=>ACTIVE])->first();
		if(!empty($user)) {
			$user->verify_string	=	'';
			$user->email_verified	=	true;
			$this->Users->save($user);
			$this->Flash->success('Your account verified successfully.');
			$this->redirect(['controller'=>'Users','action'=>'login']);
		} else {
			$this->Flash->error('Verification link is not valid.');
			$this->redirect(['controller'=>'Users','action'=>'login']);
		}
	}
	
	public function verifyPan($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('PenAadharCard');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->PenAadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	true;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->PenAadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Verified Pan Detail';
					$notification = 'Your  pan detail has been verified.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Pan detail updated successfully.',true));
				} else {
					$this->Flash->error(__('Pan card detail could not update.',true));
				}
			} else {
				$this->Flash->error(__('Pan card detail already verified.',true));
			}
		} else {
			$this->Flash->error(__('Pan card detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		// $this->Flash->error(__('Pan card detail does not exists',true));
		exit;
	}
	
	public function verifyBank($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('BankDetails');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->BankDetails->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	true;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->BankDetails->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Verified Bank Detail';
					$notification = 'Your bank detail has been verified.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Bank Detail updated successfully.',true));
				} else {
					$this->Flash->error(__('Bank detail could not update.',true));
				}
			} else {
				$this->Flash->error(__('Bank detail already verified.',true));
			}
		} else {
			$this->Flash->error(__('Bank detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
	public function panDetail() {
		$this->viewBuilder()->layout(false);
		$userId	=	$this->request->data['uset_id'];
		$type	=	$this->request->data['type'];
		$page	=	$this->request->data['page'];
		$this->set(compact('userId','type','page'));
	}
	
	public function cancelPan($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('PenAadharCard');
		
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->PenAadharCard->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	2;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->PenAadharCard->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Cancel Pan Detail';
					$notification = 'Your pan detail has been cancelled, please update again.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Pan detail cancelled successfully.',true));
				} else {
					$this->Flash->error(__('Pan card detail could not cancel.',true));
				}
			} else {
				$this->Flash->error(__('Pan card detail already cancelled.',true));
			}
		} else {
			$this->Flash->error(__('Pan card detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
	public function cancelBank($userId = null,$page=null) {
		$this->viewBuilder()->layout();
		$this->loadModel('BankDetails');
		$userId	=	$this->request->data['user_id'];
		$user	=	$this->BankDetails->find()->where(['user_id'=>$userId])->first();
		if(!empty($user)) {
			if($user->is_verified == false) {
				$user->is_verified	=	2;
				$this->loadModel('Users');
				$usersData	=	$this->Users->find()->where(['id'=>$userId])->first();
				if($this->BankDetails->save($user)) {
					$user_id     	=   $usersData->id;
					$deviceType     =   $usersData->device_type;
					$deviceToken    =   $usersData->device_id;
					$notiType       =   '10';
					
					$title = 'Cancel Bank Detail';
					$notification = 'Your bank detail has been cancelled, please update again.';
					if(($deviceType=='Android') && ($deviceToken!='')){
						$this->sendNotificationFCM($user_id,$notiType,$deviceToken,$title,$notification,'');
					} 
					if(($deviceType=='iphone') && ($deviceToken!='') && ($deviceToken!='device_id')){
						$this->sendNotificationAPNS($user_id,$notiType,$deviceToken,$title,$notification,'');
					}
					$this->Flash->success(__('Bank detail cancelled successfully.',true));
				} else {
					$this->Flash->error(__('Bank detail could not cancel.',true));
				}
			} else {
				$this->Flash->error(__('Bank detail already cancelled.',true));
			}
		} else {
			$this->Flash->error(__('Bank detail does not exists',true));
		}
		// $this->redirect(['controller'=>'users','action'=>'index?page='.$page.'']);
		exit;
	}
	
	public function referalDetail($userId = null) {
		// referal_detail
		$this->loadModel('ReferalCodeDetails');
		$result	=	$this->ReferalCodeDetails->find()->where(['refered_by'=>$userId])->contain(['Users'=>['fields'=>['id','first_name','last_name','email','phone','image']]])->toArray();
		$this->set(compact('result'));
	}
	
    public function createTeamName($userName = null) {
		$userName	=	explode('@',$userName);
		$name		=	str_replace(' ','',$userName[0]);
		$nameStr	=	substr($name,0,4);
		
		$string		=	'0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ9876543210';
		$strShuffled=	str_shuffle($string);
		$shuffleCode=	substr($strShuffled, 1, 6);
		$teamName	=	strtoupper($nameStr.$shuffleCode);
		return $teamName;
		exit;
	}
	
	public function createName($length) {
		$string		=	'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		$strShuffled=	str_shuffle($string);
		$referCode	=	substr($strShuffled, 1, $length);
		return $referCode;
		exit;
	}
	
    public function botuser() {
    	$status		=	false;
    	$parms    	=	$this->request->data;
    	if(!empty($parms['botValue'])) {
			$this->loadModel('UserAvetars');
	    	$botNum	=	$parms['botValue'];
	    	for($x = 1; $x <= $botNum; $x++) {
	    		$user		=	$this->Users->newEntity();
		    	$emailString=	$this->createUserReferal(8);
		    	$email		=	$emailString.'@byBot.com';		    	
				$avetarsImg	=	$this->UserAvetars->find()->order('rand()')->first();
				if(!empty($avetarsImg)) {
					$rootPath	=	WWW_ROOT.'uploads'. DS. 'avetars'. DS;
					$userPath	=	WWW_ROOT.'uploads'. DS .'users'. DS;
					if(file_exists($rootPath.$avetarsImg->avetars)) {
						$imgArr	=	explode(".", $avetarsImg->avetars);
						$ext	=	end($imgArr);
						$imageName		=	'user_'.time().'.'.$ext;
						$originalImage	=	$rootPath.$avetarsImg->avetars;
						$newImage		=	$userPath.$imageName;
						if(copy($originalImage, $newImage)) {
							$user->image	=	$imageName;
						}
					}
				}
		    	$user->first_name		=	$this->createName(6);
				$user->last_name		=	$this->createName(6);
				$user->email			=	$email;
				$user->phone			=	$this->generateOPT(9);
				$user->team_name		=	$this->createTeamName($email);
				$user->bonus_amount		=	Configure::read('Admin.setting.referral_bouns_amount');
				$user->refer_id			=	$this->Users->getReferId();
				$user->role_id 			= 	'4';
				$user->status 			= 	'1';
				$user->created			=	date("Y-m-d H:i:s");
				$user->modified			=	date("Y-m-d H:i:s");
				$this->Users->save($user);
				unset($this->Users->id);
			}
			$status	=	true;
		}

		$response_data	=	array('status'=>$status);
		echo json_encode(array('status' => $status));
		die;
    }
	
	public function botUserList() {
		$this->set('title_for_layout', __('Bot Users List'));
		
		$limit	=	Configure::read('ADMIN_PAGE_LIMIT');
		
		$query = $this->Users->find('search', ['search' => $this->request->query])->where(['Users.role_id' =>4,'Users.status !='=>2]);
		$users = $this->paginate($query, [
			'limit'		=>	$limit,
			'order'		=>	['Users.id'=>'DESC'],
			'group'		=>	['Users.id'],
		]);
		if(isset($this->request->params['?']['page'])){
			$page = $this->request->params['?']['page'];
		}else{
			$page = '';
		}
		
		$this->set(compact('users','page','unverified','pc'));
	}
	
	public function editBot($id=null) {
		$this->set('title_for_layout', __('Edit Bot User'));
		try {
			$user = $this->Users->get($id);
		} catch (\Throwable $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'botUserList']);
		} catch (\Exception $e) {
			$this->Flash->error(__('Invaild attempt.'));
			return $this->redirect(['action' => 'botUserList']);
		}
		if ($this->request->is(['patch', 'post', 'put'])) {
			$user = $this->Users->patchEntity($user, $this->request->getData(), ['validate' => 'AddEditUser']);
			
			if (empty($user->errors())) {
				$user->modified	=	date("Y-m-d H:i:s");
				if ($this->Users->save($user)) {
					$this->Flash->success(__('Bot User has been updated'));
					return $this->redirect(['action' => 'botUserList']);
				}
				$this->Flash->error(__('Bot User could not be added, please try again.'));
			} else {
				$this->Flash->error(__('Please correct errors listed as below.'));
			}
		}
		$this->set(compact('user'));
	}
	
	
}
