<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UsersTable
 *
 * @author vijayj
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Auth\DefaultPasswordHasher;

class EventsTable extends Table {
	/*
	 * Login Validation Rules
	 */

	public function initialize(array $config) {
		$this->setTable('events');
		$this->addBehavior('Search.Search');
	}

	/*
	 * Update Mobile Validation
	 */
	public function searchManager() {
		$searchManager = $this->behaviors()->Search->searchManager();
		$searchManager
			// ->like('name', ['before' => true, 'after' => true, 'field' => 'Users.first_name'])
			->value('id', ['before' => true, 'after' => true, 'field' => 'Users.id'])
			->value('team_name', ['before' => true, 'after' => true, 'field' => 'Users.team_name'])
			->add('name', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    $args	=	explode(' ',$args['name']);
					$filterArr	=	[];
					foreach($args as $name) {
						$filterArr[]['OR']	=	['first_name LIKE'=>'%'.$name.'%','last_name LIKE'=>'%'.$name.'%'];
					}
					$query->where([$filterArr]);
                }]
            )
			->like('team_name', ['before' => true, 'after' => true, 'field' => 'Users.team_name'])
			->like('email', ['before' => true, 'after' => true, 'field' => 'Users.email'])
			->like('phone', ['before' => true, 'after' => true, 'field' => 'Users.phone'])
			->add('start_date', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    $args	=	$args['start_date'].' 00:00:00';
					$query->where(['Users.created >='=>$args]);
                }]
            )
			->add('end_date', 'Search.Callback', [
                'callback' => function ($query, $args, $filter) {
                    $args	=	$args['end_date'].' 23:59:59';
					$query->where(['Users.created <='=>$args]);
                }]
            );
			// ->add('start_date', 'Search.Compare', ['after' => true, 'operator' => '>=', 'field' => ['created']])
			// ->add('end_date', 'Search.Compare', ['after' => true, 'operator' => '<=', 'field' => ['created']]);
		return $searchManager;
	}

	public function getReferId() {
		$seed = str_split('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ9630125478abcdefghijklmnopqrstuvwxyz9876543210'); 
		shuffle($seed);
		$rand = '';
		foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];

		return $rand;
	}

	public function getOTP()
	{
		$seed = str_split('0123456789'); 
		shuffle($seed);
		$rand = '';
		foreach (array_rand($seed, 6) as $k) $rand .= $seed[$k];

		return $rand;
	}
	
	public function validationAdminLogin(Validator $validator) {

		$validator
				->requirePresence('email')
				->notEmpty('email', __('Email is required'))
				->add('email', 'validFormat', ['rule' => 'email', 'message' => __('Valid email is required')])
				->requirePresence('password')
				->notEmpty('password', __('Password is required'));

		return $validator;
	}
	
	public function validationLogin(Validator $validator) {
		$validator
			->notEmpty('email', 'Field cannot be empty')
			;
		return $validator;
    }
	
	public function validationAddEditEvent(Validator $validator) {
		$validator
				->notEmpty('title', __('Event title is required'));
		$validator
				->notEmpty('start_date', __('Start Date is required'));
		$validator
				->notEmpty('end_date', __('End Date is required'));
		

		return $validator;
	}
	
	public function validationSignup(Validator $validator) {
		$validator
				->requirePresence('email')
				->notEmpty('email', __('Email is required'))
				->add('email', 'validFormat', ['rule' => 'email', 'message' => __('E-mail must be valid')])
				->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table', 'message' => __('E-mail already exist')]);
		$validator
				->notEmpty('phone', __('Phone number is required'))
				->add('phone', [
					'numeric' => [
						'rule' => ['numeric'],
						'message' => __('Phone number should be numeric')
					],
					'unique'=> [
						'rule'		=> 'validateUnique',
						'provider'	=> 'table',
						'message'	=> __('Phone number already exist')
					],
					'minLength' => [
						'rule' => ['minLength', 10],
						'last' => true,
						'message' => __('Phone number must be at least 10 digits long.')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('Phone Number must not be longer than 15 digits')
					]
		]);
		$validator
				->notEmpty('password', __('Password is required'))
				->add('password', [
					'minLength' => [
						'rule' => ['minLength', 6],
						'last' => true,
						'message' => __('Password must be at least 6 characters long')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('Password must not be greater than 15 characters')
					]
				])
				->add('password', [
					'alphaNumeric' => [
						'rule'		=>	array('custom', '/^(?=.*[0-9])(?=.*[A-Za-z]).{6,20}$/'),
						'message'	=>	__('Password must be contain atleast 1 character and 1 number.')
					]
				]);

		return $validator;
	}
	
	public function validationAddSubAdmin(Validator $validator) {
		$validator
				->notEmpty('first_name', __('First name is required.'));
		$validator
				->notEmpty('last_name', __('Last name is required.'));
		$validator
			->requirePresence('email')
			->notEmpty('email', __('Email is required.'))
			->add('email', 'validFormat', [
				'rule'		=>	'email',
				'message'	=>	__('E-mail must be valid.')
			])
			->add('email', 'unique', [
				'rule'		=>	'validateUnique',
				'provider'	=>	'table',
				'message'	=>	__('E-mail already exists.')
			]);
		$validator
			->notEmpty('phone', __('Phone number is required.'))
			->add('phone', 'unique', [
				'rule'		=>	'validateUnique',
				'provider'	=>	'table',
				'message'	=>	__('Mobile number already exists.')
			])
			->add('phone', [
				'numeric'	=>	[
					'rule'		=>	['numeric'],
					'message'	=>	__('Phone number should be numeric.')
				],
				'minLength'	=>	[
					'rule'		=>	['minLength', 10],
					'last'		=>	true,
					'message'	=>	__('Phone number must be at least 10 digits long.')
				],
				'maxLength'	=>	[
					'rule'		=>	['maxLength', 15],
					'message'	=>	__('Phone number must not be longer than 15 digits.')
				]
			]);
		$validator
			->notEmpty('password', __('Password is required.'))
			->add('password', [
				'minLength'	=>	[
					'rule'		=>	['minLength', 6],
					'last'		=>	true,
					'message'	=>	__('Password must be at least 6 characters long')
				],
				'maxLength'	=>	[
					'rule'		=>	['maxLength', 15],
					'message'	=>	__('Password must not be greater than 15 characters')
				]
			]);
		$validator
			->allowEmpty('image')
			->add('image',[
				'extension'	=>		[
					'rule'		=>	['extension',['jpeg', 'png', 'jpg']],
					'message'	=>	__('Please upload valid extension(jpg, jpeg, png) image.')
				],
				'fileSize' => [
					'rule' => array('fileSize', '<=', '5MB'),
					'message' => 'Image size could not be greater than 5MB.',
					'allowEmpty' => TRUE,
				],
				'mimeType' => [
					'rule' => array('mimeType', array('image/gif', 'image/png', 'image/jpg', 'image/jpeg')),
					'message' => 'Please upload valid image with extension (gif, png, jpg).',
					'allowEmpty' => TRUE,
				],
			]);
		return $validator;
	}
	
	public function validationEditSubAdmin(Validator $validator) {
		$validator
				->notEmpty('first_name', __('First name is required'));
		$validator
				->notEmpty('last_name', __('Last name is required'));
		$validator
			->requirePresence('email')
			->notEmpty('email', __('Email is required'))
			->add('email', 'validFormat', [
				'rule'		=>	'email',
				'message'	=>	__('E-mail must be valid')
			])
			->add('email', 'unique', [
				'rule'		=>	'validateUnique',
				'provider'	=>	'table',
				'message'	=>	__('E-mail already exists')
			]);
		$validator
			->notEmpty('phone', __('Phone number is required'))
			->add('phone', 'unique', [
				'rule'		=>	'validateUnique',
				'provider'	=>	'table',
				'message'	=>	__('Mobile number already exists')
			])
			->add('phone', [
				'numeric'	=>	[
					'rule'		=>	['numeric'],
					'message'	=>	__('Phone number should be numeric.')
				],
				'minLength'	=>	[
					'rule'		=>	['minLength', 10],
					'last'		=>	true,
					'message'	=>	__('Phone number must be at least 10 digits long.')
				],
				'maxLength'	=>	[
					'rule'		=>	['maxLength', 15],
					'message'	=>	__('Phone number must not be longer than 15 digits.')
				]
			]);
		$validator
			->allowEmpty('password')
			->add('password', [
				'minLength'	=>	[
					'rule'		=>	['minLength', 6],
					'last'		=>	true,
					'message'	=>	__('Password must be at least 6 characters long')
				],
				'maxLength'	=>	[
					'rule'		=>	['maxLength', 15],
					'message'	=>	__('Password must not be greater than 15 characters')
				]
			]);
		return $validator;
	}
	
	public function validationChangePassword(Validator $validator) {
        $validator
			->add('old_password', 'custom', [
				'rule' => function($value, $context) {
					$user = $this->get($context['data']['user_id']);
					if ($user) {
						if((new DefaultPasswordHasher)->check($value, $user->password)) {
							return true;
						}
					}
					return false;
				},
				'message' => 'Current password does not match',
			]);
		return $validator;
	}
	
	public function validationLoginPassword(Validator $validator) {
        $validator
			->add('password', 'custom', [
				'rule' => function($value, $context) {
					//$user = $this->get($context['data']['user_id']);
					$user = $this->find()->where(['email'=>$context['data']['email'],'status'=>ACTIVE])->first();
					if ($user) {
						if((new DefaultPasswordHasher)->check($value, $user->password)) {
							return true;
						}
					}
					return false;
				},
				'message' => 'Invalid password.',
			]);
		return $validator;
	}


	public function validationLoginPasswordInactive(Validator $validator) {
        $validator
			->add('password', 'custom', [
				'rule' => function($value, $context) {
					//$user = $this->get($context['data']['user_id']);
					$user = $this->find()->where(['email'=>$context['data']['email'],'status'=>2])->first();
					if ($user) {
						if((new DefaultPasswordHasher)->check($value, $user->password)) {
							return true;
						}
					}
					return false;
				},
				'message' => 'Invalid password.',
			]);
		return $validator;
	}

	public function validationAdminChangePassword(Validator $validator) {
        $validator
			->notEmpty('old_password', __('Old password is required'))
			->add('old_password', 'custom', [
				'rule' => function($value, $context) {
					$user = $this->get($context['data']['id']);
					if ($user) {
						if ((new DefaultPasswordHasher)->check($value, $user->password)) {
							return true;
						}
					}
					return false;
				},
				'message' => 'Please enter correct old password',
			]);
		
		$validator
				->notEmpty('password', __('New password is required'))
				->add('password', [
					'minLength' => [
						'rule' => ['minLength', 6],
						'last' => true,
						'message' => __('New password must be at least 6 characters long')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('New password must not be greater than 15 characters')
					]
				]);

		$validator
			->notEmpty('confirm_password', __('Confirm password is required'))
			/* ->add('confirm_password', [
				'minLength' => [
					'rule' => ['minLength', 6],
					'last' => true,
					'message' => __('Confirm password must be at least 6 characters long')
				],
				'maxLength' => [
					'rule' => ['maxLength', 15],
					'message' => __('Confirm password must not be greater than 15 characters')
				]
			]) */
			->add('confirm_password', 'passwordsEqual', [
				'rule' => function ($value, $context) {
					return
							!empty($context['data']['password']) &&
							$context['data']['password'] === $value;
				},
				'message' => __('New password and confirm password does not match')
			]);
		/* $validator
				->notEmpty('password', __('New Password is required'))
				->add('password', [
					'minLength' => [
						'rule' => ['minLength', 6],
						'last' => true,
						'message' => __('Password must be at least 6 characters long')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('Password must not be greater than 15 characters')
					]
				])
				->add('password', 'passwordsEqual', [
					'rule' => function ($value, $context) {

						return
								isset($context['data']['confirm_password']) &&
								$context['data']['confirm_password'] === $value;
					},
					'message' => __('New Password and Confirm password does not match')
		]
		);

		$validator
				->notEmpty('confirm_password', __('Confirm Password is required'))
				->add('confirm_password', [
					'minLength' => [
						'rule' => ['minLength', 6],
						'last' => true,
						'message' => __('Confirm password must be at least 6 characters long')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('Confirm password must not be greater than 15 characters')
					]
		]); */

		return $validator;
	}
	
	public function validationAdminResetPassword(Validator $validator) {
		$validator
				->notEmpty('password', __('Password is required'))
				->add('password', [
					'minLength' => [
						'rule' => ['minLength', 6],
						'last' => true,
						'message' => __('Password must be at least 6 characters long')
					],
					'maxLength' => [
						'rule' => ['maxLength', 15],
						'message' => __('Password must not be greater than 15 characters')
					]
				]);

		return $validator;
	}
	
	public function validationResetPassword(Validator $validator) {
			// let regex: String = "^(?=.*[0-9])(?=.*[A-Za-z]).{6,20}$"
			// let userNameTest = NSPredicate(format: "SELF MATCHES %@", regex)
			// return userNameTest.evaluate(with: password)
		$validator
			->notEmpty('password', __('Password is required'))
			->add('password', [
				'minLength' => [
					'rule' => ['minLength', 6],
					'last' => true,
					'message' => __('Password must be at least 6 characters long')
				],
				'maxLength' => [
					'rule' => ['maxLength', 15],
					'message' => __('Password must not be greater than 15 characters')
				]
			])
			->add('password', [
				'alphaNumeric' => [
					'rule'		=>	array('custom', '/^(?=.*[0-9])(?=.*[A-Za-z]).{6,20}$/'),
					'message'	=>	__('Password must be contain atleast 1 character and 1 number.')
				]
			]);
		$validator
			->notEmpty('confirm_password', __('Confirm password is required'))
			->add('confirm_password', [
				'minLength' => [
					'rule' => ['minLength', 6],
					'last' => true,
					'message' => __('Confirm password must be at least 6 characters long')
				],
				'maxLength' => [
					'rule' => ['maxLength', 15],
					'message' => __('Confirm password must not be greater than 15 characters')
				]
			])
			->add('confirm_password', 'passwordsEqual', [
				'rule' => function ($value, $context) {
					return
							!empty($context['data']['password']) &&
							$context['data']['password'] === $value;
				},
				'message' => __('Password and confirm password does not match')
			]);
		
		return $validator;
	}
	
	public function validationUpdateProfile(Validator $validator) {
        $validator->notEmpty('first_name',__('First name is required'));
		$validator->notEmpty('last_name', __('Last name is required'));
		$validator->notEmpty('team_name', __('Team name is required'));
		
		$validator
			->notEmpty('phone', __('Phone number is required'))
			->add('phone', [
				'numeric' => [
					'rule' => ['numeric'],
					'message' => __('Phone number should be numeric')
				],
				'minLength' => [
					'rule' => ['minLength', 10],
					'last' => true,
					'message' => __('Phone number must be at least 10 digit')
				],
				'maxLength' => [
					'rule' => ['maxLength', 15],
					'message' => __('Phone number must not be greater than 15 digit')
				]
		]);
		
		// $validate->notEmpty('date_of_bith',__('Date of Birth is required',true));
		// $validate->notEmpty('gender',__('Gender is required',true));

		return $validator;
	}
}
