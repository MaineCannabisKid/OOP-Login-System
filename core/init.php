<?php

// Create a Config Variable
$GLOBALS['config'] = 
	array(
		'remember' => array(
			'cookie_name' => 'hash',
			'cookie_expiry' => 604800 // 1 Month
		),
		'session' => array(
			'session_name' => 'user',
			'token_name' => 'token'
		),
		'file' => array(
			// This will include all the things such as CSS and jQuery CDN links
			'head_contents' => dirname(__FILE__) . '/includes/head/headContents.php',
			'navbar' => array(
				'default' => dirname(__FILE__) . '/includes/navbar/default.php',
				'admin' => dirname(__FILE__) . '/includes/navbar/admin.php'
			)
		)
	);

// Load Config for Production vs. Development
if($_SERVER['HTTP_HOST'] == 'localhost') { 
	require_once('devConfig.php'); // Require in the Development Configuration File
} else { 	
	require_once('prodConfig.php');// Require in the Development Configuration File
}

//-----------------------------//
// DO NOT EDIT BELOW THIS LINE //
//-----------------------------//

// Start a Session
session_start();

// Autoload Vendor Folder
require_once dirname(__FILE__) . '/vendor/autoload.php';

// Auto Load Classes
spl_autoload_register(function($class) {
	require_once dirname(__FILE__) . "/classes/" . $class . '.php';
});

// Load Functions
require_once dirname(__FILE__) . '/functions/sanitize.php';

// Define Variables
$cookieName = Config::get('remember/cookie_name');
$sessionName = Config::get('session/session_name');
// Check to see if User is Logged in by "remember me" functionality on the Login Page
if(Cookie::exists($cookieName) && !Session::exists($sessionName)) {

	$hash = Cookie::get($cookieName);
	$hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

	// If entry is in the database, user is logged in
	if($hashCheck->count()) {
		$user = new User($hashCheck->first()->user_id);
		$user->login();
	}


}

// Call a new PHPMailer Object
$mail = new PHPMailer;

// Load Kint (Debug Program - kint-php/kint)
require_once('vendor/kint-php/kint/init.php');

?>
<!-- 

	Handle the Javascript appRoot Variable here.
	Pass in the app_root from the config files 

-->
<script type="text/javascript">
	var appRoot = "<?php echo Config::get('links/app_root'); ?>";
</script>