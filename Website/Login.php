<?php
$uname = "";
$pword = "";
$errorMessage = "";
//==========================================
//	ESCAPE DANGEROUS SQL CHARACTERS
//==========================================
function quote_smart($value, $handle) {

    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }

    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value, $handle) . "'";
    }
    return $value;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $uname = $_POST['username'];
    $pword = $_POST['password'];

    $uname = htmlspecialchars($uname);
    $pword = htmlspecialchars($pword);

    //==========================================
    //	CONNECT TO THE LOCAL DATABASE
    //==========================================
    $user_name = "login";
    $pass_word = "login";
    $database = "Users";
    $server = "localhost";

    $db_handle = mysql_connect($server, $user_name, $pass_word) or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
    $db_found = mysql_select_db($database, $db_handle);

    if ($db_found) {

        $uname = quote_smart($uname, $db_handle);
        $pword = quote_smart($pword, $db_handle);

        $SQL = "SELECT * FROM login WHERE L1 = $uname AND L2 = md5($pword)";
        $result = mysql_query($SQL);
        $num_rows = mysql_num_rows($result);

        //====================================================
        //	CHECK TO SEE IF THE $result VARIABLE IS TRUE
        //====================================================

        if ($result) {
            if ($num_rows > 0) {
                session_start();
                $_SESSION['login'] = "1";
                $_SESSION['username'] = $uname;
                header ("Location: page1.php");
            }
            else {
                session_start();
                $_SESSION['login'] = "";
                header ("Location: signup.php");
            }
        }
        else {
            $errorMessage = "Error logging on";
        }

        mysql_close($db_handle);

    }

    else {
        $errorMessage = "Error logging on";
    }

}
?>
?>
<html>
<head>
	<link type="text/css" rel="stylesheet" href="Login.css" />
	<title>Wrist Trainer</title>
</head>

<body>
	
		<h1 id="wristTrainer">Wrist Trainer</h1>
		
		<div id="whiteBox">
		<login>
		<h1>Login</h1>
		</login>
		
		<email>
		<p><label for="usermail">Email</label>
		<input style="width: 200px; height: 30px;" type="email" name="usermail" placeholder="yourname@email.com" required></p>
		</email>
					
		<password>
		<p id="password"><label for="password">Password</label>
		<input style="width: 200px; height: 30px;" type="password" name="password" placeholder="password" required></p>
		</password>
					
		<submit>
		<p><input type="submit" value="Login"></p>
		</submit>
		
		<a href="https://www.google.com">
			<h5 id="CreateAccount">Create an account</h5>
		</a>
		<a href="https://www.google.com">
			<h5 id="ForgotPassword">Forgot password?</h5>
		</a>
		</div>
		
		<div id="background" style='position:absolute;z-index:-1;left:0;top:0;width:100%;height:100%'>
		  <img src='WristTrainer.jpg' style='width:100%;height:100%' alt='[]' />
		</div>
		
	</div>
</body>
</html>