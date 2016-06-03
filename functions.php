<?php
$error = array();

function sql_connect(){
	$sql_user = "root";
	$sql_pass = "admin";
	$sql_db = "test";
	$sql_host = "localhost";
	global $link;

	$link = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db) or die("Ühendus andmebaasiga ebaõnnestus");
	mysqli_query($link, "SET CHARACTER SET UTF8") or die("Ei saanud baasi utf-8-sse");
}

function kaup($i) {
	global $link;
	$j = mysqli_real_escape_string($link, $i);
	$query = "SELECT kaup FROM jalas_tooted WHERE id='$j'";
	$result = mysqli_query($link, $query) or die("Query error");

	while ($rida = mysqli_fetch_assoc($result)) {
		echo "{$rida['kaup']}";
	};
}

function hind($i) {
	global $link;
	$j = mysqli_real_escape_string($link, $i);
	$query = "SELECT hind FROM jalas_tooted WHERE id='$j'";
	$result = mysqli_query($link, $query) or die("Query error");

	while ($rida = mysqli_fetch_assoc($result)) {
		echo "{$rida['hind']}€";
	};
}

function laoseis($i) {
	global $link;
	$j = mysqli_real_escape_string($link, $i);
	$query = "SELECT laoseis FROM jalas_tooted WHERE id='$j'";
	$result = mysqli_query($link, $query) or die("Query error");

	while ($rida = mysqli_fetch_assoc($result)) {
		echo "{$rida['laoseis']}";
	};
}

function tootekood($i) {
	global $link;
	$j = mysqli_real_escape_string($link, $i);
	$query = "SELECT tootekood FROM jalas_tooted WHERE id='$j'";
	$result = mysqli_query($link, $query) or die("Query error");

	while ($rida = mysqli_fetch_assoc($result)) {
		echo "{$rida['tootekood']}";
	};
}

function ladu($i) {
	global $link;
	$j = mysqli_real_escape_string($link, $i);
	$query = "SELECT ladu FROM jalas_tootekood WHERE id='$j'";
	$result = mysqli_query($link, $query) or die("Query error");

	while ($rida = mysqli_fetch_assoc($result)) {
		echo "{$rida['kaup']}";
	};
}

function search() {
	global $link;
	if (!empty($_POST)) {
		if (isset ($_POST['kaup']) && $_POST['kaup'] != "") {
			//database_search($_POST['kaup']);
			$kaup = $_POST['kaup'];
			$k = mysqli_real_escape_string($link, $kaup);
			$search_query = "SELECT jalas_tooted.kaup, jalas_tooted.tootekood, jalas_tootekood.ladu FROM jalas_tooted, jalas_tootekood WHERE jalas_tooted.tootekood = jalas_tootekood.tootekood and jalas_tooted.kaup = '$k'";
			$result = mysqli_query($link, $search_query) or die("Query error");
			
			return mysqli_query($link, $search_query);
		} else $error["viga5"] = "Kaup tühi";
	} //include 'view/tootekood.html';
}

function database_search($kaup) {
	global $link;
	
	//$kaup = $_POST['kaup'];
	$k = mysqli_real_escape_string($link, $kaup);
	$search_query = "SELECT jalas_tooted.kaup, jalas_tooted.tootekood, jalas_tootekood.ladu FROM jalas_tooted, jalas_tootekood WHERE jalas_tooted.tootekood = jalas_tootekood.tootekood and jalas_tooted.kaup = '$k'";
	$result = mysqli_query($link, $search_query) or die("Query error");
}

function check_login() {
	if (isset($_SESSION['admin']) || isset($_SESSION['peasant'])) {
		return true;
	}
	return false;
}

function check_power() {
	if (isset($_SESSION['admin'])) {
		return true;
	}
	return false;
}

function login() {
	global $error;
	
	if (!empty($_POST)) {
		
		if (isset ($_POST['kasutajanimi']) && $_POST['kasutajanimi'] != "" && isset ($_POST['parool']) && $_POST['parool'] != "") {  //Kasutajanime ja parooli v2lja kontroll, kas on v22rtus
			check_userinfo($_POST['kasutajanimi'], $_POST['parool']);
		} else if ($_POST['parool'] != "" && $_POST['kasutajanimi'] == "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
		} else if ($_POST['kasutajanimi'] != "" && $_POST['parool'] == "") {
			$error["emptypass"] = "Sisesta Parool!";
		} else if ($_POST['kasutajanimi'] == "" && $_POST['parool'] == "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
			$error["emptypass"] = "Sisesta Parool!";
		}
	} include("view/logisisse.html");
}

function check_userinfo($username, $password) {  //Kontrollib andmete valiidsust
	global $error, $link;
	
	$u = mysqli_real_escape_string($link, $username);
	$p = mysqli_real_escape_string($link, $password);
	$user_query = "SELECT username FROM jalas_users WHERE username='$u'";
	$pass_query = "SELECT password FROM jalas_users WHERE password=SHA1('$p')";
	$user_result = mysqli_query($link, $user_query) or die("Query error");
	$pass_result = mysqli_query($link, $pass_query) or die("Query error");
	$correct_user = mysqli_fetch_assoc($user_result);
	$correct_pass = mysqli_fetch_assoc($pass_result);
	
	if ($_POST['kasutajanimi'] == "{$correct_user['username']}" && sha1($_POST['parool']) == "{$correct_pass['password']}") {
		$admin_query = "SELECT power FROM jalas_users WHERE username='$u'";
		$admin_result = mysqli_query($link, $admin_query) or die("Query error");
		$admin_power = mysqli_fetch_assoc($admin_result);
		
		if("{$admin_power['power']}" === "admin") {
			$_SESSION['admin'] = $u;  //seab Sessionile nime, admini õigustega
			header("Location: kontroller.php?mode=foorum");
			exit(0);
		} else {
			$_SESSION['peasant'] = $u; //seab Sessionile nime, tava õigustega
			header("Location: kontroller.php?mode=tooted");
			exit(0);
		}		
	} else {
		$error["wrongpass"] = "Vale Parool!";
	}
}

function injection($username, $password) {  //sql-injection test ') OR ('' = '
	global $error, $link;
	
	/*$u = mysqli_real_escape_string($link, $username);
	$p = mysqli_real_escape_string($link, $password);*/
	$user_query = "SELECT username FROM jalas_users WHERE username='$username'";
	$pass_query = "SELECT password FROM jalas_users WHERE password='$password'";
	$user_result = mysqli_query($link, $user_query) or die("Query error");
	$pass_result = mysqli_query($link, $pass_query) or die("Query error");
	$correct_user = mysqli_fetch_assoc($user_result);
	$correct_pass = mysqli_fetch_assoc($pass_result);
	
	if ($_POST['kasutajanimi'] == "{$correct_user['username']}" && $_POST['parool'] == "{$correct_pass['password']}") {
		header("Location: kontroller.php?mode=foorum");
		$_SESSION['admin'] = $username;  //seab Sessionile nime, et kasutaja saaks peidetud lehti n2ha
		exit(0);
	} else {
		$error["wrongpass"] = "Vale Parool!";
	}
}

function add_userinfo($username, $password) {
	global $link, $error;

	$u = mysqli_real_escape_string($link, $username);
	$p = mysqli_real_escape_string($link, $password);
	$userexist_query = "SELECT username FROM jalas_users WHERE username='$u'";
	$add_query = "INSERT INTO jalas_users(username, password, power) VALUES('$u', SHA1('$p'), 'peasant')";
	$userexist_result = mysqli_query($link, $userexist_query) or die("Query error");
	$user_match = mysqli_fetch_assoc($userexist_result);

	if ("{$user_match['username']}" === $u) {
		$error["username_exists"] = "Kasutajanimi juba võetud!";
	} else

	$add_result = mysqli_query($link, $add_query) or die("Query error");
	$error["user_created"] = "Kasutaja Loodud!";
	header('Refresh: 2; URL=kontroller.php?mode=login');
}

function register() {
	global $error;
	
	if (!empty($_POST)) {
	
		if (isset ($_POST['kasutajanimi']) && $_POST['kasutajanimi'] != "" && isset ($_POST['parool']) && $_POST['parool'] != "" && isset ($_POST['parool2']) && $_POST['parool2'] != "" && $_POST['parool'] === $_POST['parool2']) {
			add_userinfo($_POST['kasutajanimi'], $_POST['parool']);
		} else if ($_POST['parool'] != "" && $_POST['parool2'] != "" && $_POST['kasutajanimi'] == "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
		} else if ($_POST['kasutajanimi'] != "" && $_POST['parool'] == "" && $_POST['parool2'] != "") {
			$error["emptypass"] = "Sisesta Parool!";
		} else if ($_POST['kasutajanimi'] != "" && $_POST['parool'] != "" && $_POST['parool2'] == "") {
			$error["emptypass2"] = "Sisesta Parool uuesti!";
		} else if ($_POST['kasutajanimi'] == "" && $_POST['parool'] == "" && $_POST['parool2'] != "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
			$error["emptypass"] = "Sisesta Parool!";
		} else if ($_POST['kasutajanimi'] != "" && $_POST['parool'] == "" && $_POST['parool2'] == "") {
			$error["emptypass"] = "Sisesta Parool!";
			$error["emptypass2"] = "Sisesta Parool uuesti!";
		} else if ($_POST['kasutajanimi'] == "" && $_POST['parool'] != "" && $_POST['parool2'] == "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
			$error["emptypass2"] = "Sisesta Parool uuesti!"; 
		} else if ($_POST['kasutajanimi'] == "" && $_POST['parool'] == "" && $_POST['parool2'] == "") {
			$error["emptyuser"] = "Sisesta Kasutajanimi!";
			$error["emptypass"] = "Sisesta Parool!";
			$error["emptypass2"] = "Sisesta Parool uuesti!";
		}
	} include 'view/registreeri.html';
}

function product() {
	global $link, $error;
	
	if (!empty($_POST)) {
		if (isset ($_POST['kaup']) && $_POST['kaup'] != "" && isset ($_POST['hind']) && $_POST['hind'] != "" && isset ($_POST['laoseis']) && $_POST['laoseis'] != "" && isset ($_POST['tootekood']) && $_POST['tootekood'] != "" && isset ($_POST['ladu']) && $_POST['ladu'] != "") {
			//$error["viga2"] = "Saadab POSTi!";
			add_product($_POST['kaup'], $_POST['hind'], $_POST['laoseis'], $_POST['tootekood'], $_POST['ladu']);			
		}
	} include 'view/toote_lisamine.html';
}

function add_product($kaup, $hind, $laoseis, $tootekood, $ladu) {
	global $link, $error;
	
	$k = mysqli_real_escape_string($link, $kaup);
	$h = mysqli_real_escape_string($link, $hind);
	$l = mysqli_real_escape_string($link, $laoseis);
	$t = mysqli_real_escape_string($link, $tootekood);
	$la = mysqli_real_escape_string($link, $ladu);
	$table_query = "INSERT INTO jalas_tooted(kaup, hind, laoseis, tootekood) VALUES('$k', '$h', '$l', '$t')";
	$tootekood_query = "INSERT INTO jalas_tootekood(tootekood, ladu) VALUES('$t', '$la')";
	$table_result = mysqli_query($link, $table_query) or die("Query error");
	$tootekood_result = mysqli_query($link, $tootekood_query) or die("Query error");
	
	/*if ($table_result) {
		$error["viga3"] = "Korras";
	} else $error["viga4"] = "Viga pärast QUERYT";*/
}

function table() { //Abiks tabeli joonistamisel HTMLis.
	global $link;
	
	$count_query = "SELECT COUNT(id) AS num FROM jalas_tooted";
	$count_result = mysqli_query($link, $count_query);
	$rida = mysqli_fetch_assoc($count_result);
	$num_tables = $rida['num'];
	
	return $num_tables;
}

function foorum() {
	if(check_login() && isset ($_SESSION['admin'])) {
		include 'view/foorum.html';
	} else include 'view/logonhoiatus.html';
}

function logout() { //Copy paste
	$_SESSION = array();
	if (isset($_COOKIE[session_name()])) {
		setcookie(session_name(), '', time()-42000, '/');
	}
	session_destroy();
}

function redirect_logout() {
	include 'view/logoutmsg.html';
	header('Refresh: 2; URL=kontroller.php?mode=pealeht');
}

//END
?>
