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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	 <?php
			echo $this->Html->css([
				'front_home/bootstrap.min.css',
				'front_home/style.css',
				'front_home/media.css',
				'front_home/owl.carousel.min.css'
			]);
		?>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:100,300,400,500,600,700" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Poppins:100,300,400,500,600,700" rel="stylesheet">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

	<title><?php echo __('TatvaTest'); ?></title>
	<script> var siteurl = '<?= SITE_URL; ?>'; </script>
	 <script src="<?= SITE_URL; ?>webroot/js/front_home/jquery.min.js"></script>
	
	<!-- Start of Zendesk Widget script -->
	<script id="ze-snippet" src="#"> </script>
	<!-- End of Zendesk Widget script -->
  </head>

  <body>
		<?php //echo $this->element('front_home/header'); ?>
		<div class="col-md-12"> <?php echo $this->Flash->render() ?></div>
		<?php echo $this->fetch('content'); ?>

	<script src="<?= SITE_URL; ?>webroot/js/front_home/popper.min.js"></script>
	<script src="<?= SITE_URL; ?>webroot/js/front_home/bootstrap.min.js"></script>
	<script src="<?= SITE_URL; ?>webroot/js/front_home/owl.carousel.js"></script>
	<script src="<?= SITE_URL; ?>webroot/js/front_home/lodash.js"></script>
	 
    <script src="https://apis.google.com/js/api:client.js"></script>
	<script>
window.fbAsyncInit = function() {
    // FB JavaScript SDK configuration and setup
    FB.init({
      appId      : '360095284581999', // FB App ID
      cookie     : true,  // enable cookies to allow the server to access the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v2.8' // use graph api version 2.8
    });

};

// Load the JavaScript SDK asynchronously
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

// Facebook login with JavaScript SDK
function fbLogin() {

    FB.login(function (response) {
        if (response.authResponse) {
            // Get and display the user profile data
            getFbUserData();
        } else {
            document.getElementById('status').innerHTML = 'User cancelled login or did not fully authorize.';
        }
    }, {scope: 'email'});
}

// Fetch the user profile data from facebook
function getFbUserData(){
    FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,link,gender'},
    function (response) {
        saveUserData(response);

    });
}
/* save data */
 function saveUserData(userData){
 	  var csrfToken = <?php echo json_encode($this->request->getParam('_csrfToken')) ?>;
	  //alert(csrfToken);
     var url = '<?php echo $this->Url->build(["controller" => "users", "action" => "social"]); ?>'
    $.ajax({
		/*  headers: {
			'X-CSRF-Token': csrfToken
		}, */
	    url: url,
	    type: 'POST',
	    cache: false,
	    dataType: 'json',
		//headers: { 'X-XSRF-TOKEN' : csrfToken },
	    data: {FbuserData: JSON.stringify(userData),type:'facebook'},
	    beforeSend: function (xhr) { // Add this line
			xhr.setRequestHeader('X-CSRF-Token', csrfToken);
		},
	    success: function (response) {
		if(response.status = 1)
		{
		  window.location.href = '<?php echo SITE_URL ?>';
		}
	    }
	});
}

// Logout from facebook
function fbLogout() {
    FB.logout(function() {
        document.getElementById('fbLink').setAttribute("onclick","fbLogin()");
        document.getElementById('fbLink').innerHTML = '<img src="fblogin.png"/>';
        document.getElementById('userData').innerHTML = '';
        document.getElementById('status').innerHTML = 'You have successfully logout from Facebook.';
    });
}

</script>


<script>
  var googleUser = {};
  var startApp = function() {
    gapi.load('auth2', function(){
      // Retrieve the singleton for the GoogleAuth library and set up the client.
      auth2 = gapi.auth2.init({
        client_id: '86165832247-enajuv4mrp147rdahceobvkk9v2mk9q8.apps.googleusercontent.com',
        //client_id: '86165832247-04jdvnuje66ek5b0nvcc2g2rmg4huv5k.apps.googleusercontent.com',
        cookiepolicy: 'single_host_origin',
        // Request scopes in addition to 'profile' and 'email'
        //scope: 'additional_scope'
      });
      attachSignin(document.getElementById('customBtn'));
    });
  };

  function attachSignin(element) { //alert();
   var csrfToken = <?php echo json_encode($this->request->getParam('_csrfToken')) ?>;
    //console.log(element.id);
    auth2.attachClickHandler(element, {},
        function(googleUser) {
           console.log(googleUser.w3);
	  var url = '<?php echo $this->Url->build(["controller" => "Users", "action" => "social"]); ?>';
	  //alert(url);
		$.ajax({
		    url: url,
		    type: 'POST',
		    cache: false,
		    //dataType: 'json',
		    data: {GoogleuserData: googleUser.w3,type:'gmail'},
			beforeSend: function (xhr) { // Add this line
			  xhr.setRequestHeader('X-CSRF-Token', csrfToken);
		     },
		    success: function (response) {  //alert(response);
			if (response.status = 1)
			{
			   window.location.href = '<?php echo SITE_URL ?>';
			}
		    }
		});
        }, function(error) {

        });
  }
  </script>
  <script>startApp();</script>
	<script type="text/javascript">
      $(document).ready(function(){
        $('.custom_nav .menu_list li').click(function(){
          $('.menu_list li').removeClass("active");
          $(this).addClass("active");
      });
      });
    </script>
    <script>
      $(document).ready(function(){
        $(".menu_icon").click(function(){
          $(".menu_icon").toggleClass("main");
        });
        $(".menu_icon").click(function(){
          $(".custom_nav").toggle(300);
        });
      });
    </script>
  </body>


</html>
