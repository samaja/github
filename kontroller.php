<?php
session_start();
require_once ("functions.php");
sql_connect();

$pildid = array(
  array("img"=>"img/lill.jpg", "thumb"=>"thumb/lill_thumb.jpg", "alt"=>"Lill"),
  array("img"=>"img/cat.JPG", "thumb"=>"thumb/cat_thumb.JPG", "alt"=>"Cat"),
  array("img"=>"img/burger.jpg", "thumb"=>"thumb/burger_thumb.jpg", "alt"=>"Burger"),	
  array("img"=>"img/ukf.jpg", "thumb"=>"thumb/ukf_thumb.jpg", "alt"=>"UKF")	
 );
 
$tekst = array("lorem" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
     magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo 
     consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
     Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
     Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
     magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo 
     consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
     Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
     Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
     magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo 
     consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
     Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.");
 
$active_mode = "niisama"; //ajutine v22rtus

if (isset($_GET['mode'])) {
	$active_mode = htmlspecialchars($_GET['mode']);  //s2tib lehe, koos turvalisusega
}

include 'view/head.html';
switch($active_mode) {
	case "galerii";
		include 'view/galerii.html';
	break;
	case "tooted";
		include 'view/tooted.html';
	break;
	case "foorum";
		foorum();
	break;
	case "login";
		login();
	break;
	case "logout";
		logout();
		redirect_logout();
	break;
	case "register";
		register();
	break;
	case "lisa_toode";
		if (check_power()) {
			product();
		} else if (check_power() == false) {
			include 'view/pealeht.html';
		}
	break;
	case "tootekood";
		if (check_power()) {
			include 'view/tootekood.html';
		} else if (check_power() == false) {
			include 'view/tooted.html';
		}
	break;
	default:
		include 'view/pealeht.html';
	break;
} include 'view/foot.html';
?>