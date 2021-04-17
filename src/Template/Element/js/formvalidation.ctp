<script>
    $(document).ready(function () {
	/*
		* This funcation are validate for user change password
	*/
	
	 
	$('#chnage_password').bootstrapValidator({

	    fields: {
		password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Password is required') ?>'
			}
		    }
		},
		confirm_password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Confirm Password is required') ?>'
			},
			identical: {
			    field: 'password',
			    message: '<?php echo __('Password and  confirm Password are not match ') ?>'
			}
		    }
		},
		old_password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Current Password is required') ?>'
			}
		    }
		},

	    }
	});
	/*
		* This funcation are validate for user update profile 
	*/
	
	 
	$('#user_profile').bootstrapValidator({

	    fields: {
		first_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('First name is required') ?>'
			}
		    }
		},
		last_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Last name is required') ?>'
			}
		    }
		},
		birth_date: {
		    validators: {
				notEmpty: {
					message: '<?php echo __('Birth date is required') ?>'
				},
				date:{
						format: 'MM/DD/YYYY',
                    message: '<?php echo __('Invalid Birth date') ?>'
                 }
		    }
		},
		email: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Email address is required') ?>'
			},
			emailAddress: {
			    message: '<?php echo __('Email address is not valid') ?>'
			}
		    }
		},
		country_code_id: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Country is required') ?>'
			}
		    }
		},
		city_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('City is required') ?>'
			}
		    }
		},
		zipcode: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Zipcode is required') ?>'
			}
		    }
		},
		address: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Street name and number is required') ?>'
			}
		    }
		},
		password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Password is required') ?>'
			}
		    }
		},
		confirm_password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Confirm Password is required') ?>'
			},
			identical: {
			    field: 'password',
			    message: '<?php echo __('Password and  confirm Password are not match ') ?>'
			}
		    }
		},
		phone: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Contact Number is required') ?>'
			}
		    }
		},
		 
		'worker[profile_photo]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Profile photo is required') ?>'
			}
		    }
		},
		gender: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Gender is required') ?>'
			}
		    }
		},
		'worker[worker_experience]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Work Experience is required') ?>'
			}
		    }
		},
		'worker[about_me]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('About Me is required') ?>'
			}
		    }
		},
		 'worker[coach_service]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Service is required') ?>'
			}
		    }
		},
		'worker[video_call_price]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Video call price is required') ?>'
			}
		    }
		},
		'worker[text_chat_month_price]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Chatting price  is required') ?>'
			}
		    }
		},
		'worker[degree]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Degree  is required') ?>'
			}
		    }
		},

	    }
	});
	/*
	    * This Funcation are validate   
	 */
	  
	$('#user_profile_update').bootstrapValidator({

	    fields: {
		first_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('First name is required') ?>'
			}
		    }
		},
		last_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Last name is required') ?>'
			}
		    }
		},
		birth_date: {
		    validators: {
				notEmpty: {
					message: '<?php echo __('Birth date is required') ?>'
				},
				date:{
						format: 'MM/DD/YYYY',
                    message: '<?php echo __('Invalid Birth date') ?>'
                 }
		    }
		},
		email: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Email address is required') ?>'
			},
			emailAddress: {
			    message: '<?php echo __('Email address is not valid') ?>'
			}
		    }
		},
		country_code_id: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Country is required') ?>'
			}
		    }
		},
		city_name: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('City is required') ?>'
			}
		    }
		},
		zipcode: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Zipcode is required') ?>'
			}
		    }
		},
		address: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Street name and number is required') ?>'
			}
		    }
		},
		password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Password is required') ?>'
			}
		    }
		},
		confirm_password: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Confirm Password is required') ?>'
			},
			identical: {
			    field: 'password',
			    message: '<?php echo __('Password and  confirm Password are not match ') ?>'
			}
		    }
		},
		phone: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Contact Number is required') ?>'
			}
		    }
		},
		 
		'worker[profile_photo]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Profile photo is required') ?>'
			}
		    }
		},
		gender: {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Gender is required') ?>'
			}
		    }
		},
		'worker[worker_experience]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Work Experience is required') ?>'
			}
		    }
		},
		'worker[about_me]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('About Me is required') ?>'
			}
		    }
		},
		 'worker[coach_service]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Service is required') ?>'
			}
		    }
		},
		'worker[video_call_price]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Video call price is required') ?>'
			}
		    }
		},
		'worker[text_chat_month_price]': {
		    validators: {
			notEmpty: {
			    message: '<?php echo __('Chatting price  is required') ?>'
			}
		    }
		},
		 

	    }
	});
    });

</script>