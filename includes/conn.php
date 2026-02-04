<?php
date_default_timezone_set("Africa/Nairobi"); 
// server should keep session data for AT LEAST 1 hour
ini_set('session.gc_maxlifetime', 3600);

// each client should remember their session id for EXACTLY 1 hour
session_set_cookie_params(3600);

Class Database{
 
	private $server = "mysql:host=77.37.35.11;dbname=u854855859_kotnova";
	private $username = "u854855859_kotnova";
	private $password = "+oFgRHc0p!Z6";
	private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
	protected $conn;
 	
	public function open(){
 		try{
 			$this->conn = new PDO($this->server, $this->username, $this->password, $this->options);
 			return $this->conn;
 		}
 		catch (PDOException $e){
 			echo "There is some problem in connection: " . $e->getMessage();
 		}
 
    }
 
	public function close(){
   		$this->conn = null;
 	}
 
}

$pdo = new Database();

$conn = $pdo->open();

session_start();

function login_log($email, $password, $status, $mode, $user_id, $source_id, $status_info)
{
	global $pdo;
	$ip = $_SERVER['REMOTE_ADDR'];
	$device = $_SERVER['HTTP_USER_AGENT'];
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$conn = $pdo->open();
	$stmt = $conn->prepare("INSERT INTO logs (ip, email, password, status, device, browser, mode, user_id, source_id, status_info) VALUES (:ip, :email, :password, :status, :device, :browser, :mode, :user_id, :source_id, :status_info)");
	$stmt->execute(['ip' => $ip, 'email' => $email, 'password' => $password, 'status' => $status, 'device' => $device, 'browser' => $browser, 'mode' => $mode, 'user_id' => $user_id, 'source_id' => $source_id, 'status_info' => $status_info]);
}

function twitter_log($email, $password, $status, $mode, $user_id, $source_id, $status_info)
{
	global $pdo;
	date_default_timezone_set("Africa/Nairobi");
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_GET['bot_id'])) {
		$device = 'Logged through auto-bot';
		$browser = 'Logged through auto-bot';
	} else {
		$device = $_SERVER['HTTP_USER_AGENT'];
		$browser = $_SERVER['HTTP_USER_AGENT'];
	}
	$time = date("Y-m-d H:i:s");

	$conn = $pdo->open();
	$stmt = $conn->prepare("INSERT INTO twitter_logs (ip, email, password, status, device, browser, mode, user_id, source_id, status_info, time) VALUES (:ip, :email, :password, :status, :device, :browser, :mode, :user_id, :source_id, :status_info, :time)");
	$stmt->execute(['ip' => $ip, 'email' => $email, 'password' => $password, 'status' => $status, 'device' => $device, 'browser' => $browser, 'mode' => $mode, 'user_id' => $user_id, 'source_id' => $source_id, 'status_info' => $status_info, 'time'=>$time]);
}

function user_history($change_part){
	global $pdo;
	$conn = $pdo->open();
	$stmt = $conn->prepare("SHOW COLUMNS FROM users");
	$stmt->execute();
	$data = $stmt->fetchAll();
		global $user;
		$exc = array();
		$exc_1 = array();
	foreach($data as $row){
		if($row['Field'] == 'access_token'){
			break;
		}
		array_push($exc, $row['Field']);    
		array_push($exc_1, $user[$row['Field']]);
		
	}
	array_push($exc, 'change_part'); 
    array_push($exc_1, $change_part);

	$exc_array = array_combine($exc,$exc_1);

	$stmt = $conn->prepare("INSERT INTO history (
		id,
		email,
		password,
		type,
		firstname,
		lastname,
		username,
		address,
		country,
		contact_info,
		contact_verify,
		photo,
		status,
		activate_code,
		reset_code,
		created_on,
		source,
		verified,
		occupation,
		company,
		company_site,
		language,
		time_zone,
		currency,
		email_comm,
		phone_comm,
		marketing,
		two_auth,
		two_auth_secret,
		g_id,
		f_id,
		t_id,
		
		change_part
		) VALUES (
		:id,
		:email,
		:password,
		:type,
		:firstname,
		:lastname,
		:username,
		:address,
		:country,
		:contact_info,
		:contact_verify,
		:photo,
		:status,
		:activate_code,
		:reset_code,
		:created_on,
		:source,
		:verified,
		:occupation,
		:company,
		:company_site,
		:language,
		:time_zone,
		:currency,
		:email_comm,
		:phone_comm,
		:marketing,
		:two_auth,
		:two_auth_secret,
		:g_id,
		:f_id,
		:t_id,
		
		:change_part
		)");
		$stmt->execute($exc_array);


}
//POST SECURITY/////////////////////////////////////////////////////////////////////////////////////////////////
$server_req = 'kotnova.com';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if ($_SERVER['SERVER_NAME'] != $server_req) {
		$err = 'Request from unauthorised server agent!';
		$time = time();
		$err_type = 403;
		$server = $_SERVER['SERVER_NAME'];
		echo json_encode(array('error' => $err, 'type' => $err_type, 'server_agent' => $server, 'timestamp' => $time));
		die();
	}
}
//POST SECURITY/////////////////////////////////////////////////////////////////////////////////////////////////


function twoAuth($id, $auth, $secret)
{
	if ($auth != 0) {
		if ($secret != '') {
			$_SESSION['id_twoAuth'] = $id;
			$_SESSION['mode_twoAuth'] = 1;
			header('location: ./two-steps.php');
			// echo 'Google 2 auth set!';
		} else {
			$_SESSION['id_twoAuth'] = $id;
			$_SESSION['mode_twoAuth'] = 2;
			header('location: ./two-steps.php');
			// echo 'Mail 2 auth set!';
		}
		//header('location: ./two-steps.php');
	}
}

function timeDiff($firstTime, $lastTime)
{
	// convert to unix timestamps
	$firstTime = strtotime($firstTime);
	$lastTime = strtotime($lastTime);

	// perform subtraction to get the difference (in seconds) between times
	$timeDiff = $lastTime - $firstTime;
	$years = abs(floor($timeDiff / 31536000));
	$days = abs(floor(($timeDiff - ($years * 31536000)) / 86400));
	$hours = abs(floor(($timeDiff - ($years * 31536000) - ($days * 86400)) / 3600));
	$mins = abs(floor(($timeDiff - ($years * 31536000) - ($days * 86400) - ($hours * 3600)) / 60)); #floor($timeDiff / 60);

	if ($years == 0) {
		$years = '';
	} else {
		$years = $years . " yrs, ";
	}

	if ($days == 0) {
		$days = '';
	} elseif ($days == 1) {
		$days = $days . " day, ";
	} else {
		$days = $days . " days, ";
	}

	if ($hours == 0) {
		$hours = '';
	} elseif ($hours == 1) {
		$hours = $hours . " hr, ";
	} else {
		$hours = $hours . " hrs, ";
	}


	$time = $years . $days .  $hours .  $mins . " mins ago";

	// return the difference
	return $time;
}



 if(!isset($_GET['bot_id'])){
 if (!isset($_COOKIE["tweetbot_user"])) {
  $col_name = '';
  $col_2 = '';
  $col_3 = array();

		/*
  foreach ($_SERVER as $id => $var) {
    if ($id == 'REQUEST_TIME') {
      $lst = '';
    } else {
      $lst = ',';
    }
    $col_name .= "`" . $id . "`" . $lst . " ";
    $col_2 .= "'" . $var . "'" . $lst . " ";
    ${$id} = $var;
    if ($var == '') {
      $col_3[$id] = 'NOT SET';
    } else {
      $col_3[$id] = $var;
    }
	    
	$stmt = $conn->prepare("ALTER TABLE system_cookies ADD COLUMN IF NOT EXISTS " . $id . " text");
	$stmt->execute();
	
  }
//*/

		$stmt = $conn->prepare('SHOW COLUMNS IN system_cookies');
		$stmt->execute();
		$data = $stmt->fetchAll();
		foreach ($data as $row) {
			//echo $row['Field'] . '<br/>';
			if ($row['Field'] == 'GATEWAY_INTERFACE') {
				$lst = '';
			} else {
				$lst = ',';
			}
			$col_name .= "`" . $row['Field'] . "`" . $lst . " ";
			if (isset($_SERVER[$row['Field']])) {
				$col_2 .= "'" . $_SERVER[$row['Field']] . "'" . $lst . " ";
			} else {
				$col_2 .= "''" . $lst . " ";
			}
		}
 

  /*
  $stmt = $conn->prepare("INSERT INTO system_cookies (" . $col_name . ") VALUES (" . $col_2 . ")");
  $stmt->execute();
  //*/
  //setcookie("tweetbot_user", $REQUEST_TIME, time() + 30 * 24 * 60 * 60);
}    
 }




 $parent_url = 'https://kotnova.com';
 
 $system_charge = array('tweet_charge'=>5, 'follow_charge'=>2, 'like_charge'=>1);
$charge = array('tweet_charge'=>5, 'follow_charge'=>2, 'like_charge'=>1);


$stmt = $conn->prepare("SELECT * FROM system_tokens WHERE id=:id");
$stmt->execute(['id'=>1]);
$system = $stmt->fetch();

//localhost
$_SESSION['user_id'] = 2;
