	<?php
session_start();
$uname = "";
$pword = "";
$pwordConf = "";
$errorMessage = "";
$num_rows = 0;

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

    //====================================================================
    //	GET THE CHOSEN U AND P, AND CHECK IT FOR DANGEROUS CHARCTERS
    //====================================================================
    $uname = $_POST['username'];
    $pword = $_POST['password'];
    $pwordConf = $_POST['passwordCont'];

    $uname = htmlspecialchars($uname);
    $pword = htmlspecialchars($pword);

    //====================================================================
    //	CHECK TO SEE IF U AND P ARE OF THE CORRECT LENGTH
    //	A MALICIOUS USER MIGHT TRY TO PASS A STRING THAT IS TOO LONG
    //	if no errors occur, then $errorMessage will be blank
    //====================================================================

    $uLength = strlen($uname);
    $pLength = strlen($pword);
    $pConfLength = strlen($pwordConf);
    if (!($uLength >= 10 && $uLength <= 20)) {
        $errorMessage = $errorMessage . "Username must be between 10 and 20 characters" . "<BR>";
    }

    if (!($pLength >= 8 && $pLength <= 16)) {
        $errorMessage = $errorMessage . "Password must be between 8 and 16 characters" . "<BR>";
    }

    if(!($pConfLength == $pLength)){
        $errorMessage = $errorMessage . "Passwords must match" . "<BR>";
    }


//test to see if $errorMessage is blank
//if it is, then we can go ahead with the rest of the code
//if it's not, we can display the error

    //====================================================================
    //	Write to the database
    //====================================================================
    if ($errorMessage == "") {

        $user_name = "createAccount";
        $pass_word = "createAccount";
        $database = "Users";
        $server = "localhost";

        $db_handle = mysql_connect($server, $user_name, $pass_word)  or die ("<html><script language='JavaScript'>alert('Unable to connect to database! Please try again later.'),history.go(-1)</script></html>");
        $db_found = mysql_select_db($database, $db_handle);

        if ($db_found) {

            $uname = quote_smart($uname, $db_handle);
            $pword = quote_smart($pword, $db_handle);

            //====================================================================
            //	CHECK THAT THE USERNAME IS NOT TAKEN
            //====================================================================

            $SQL = "SELECT * FROM login WHERE L1 = $uname";
            $result = mysql_query($SQL);
            $num_rows = mysql_num_rows($result);

            if ($num_rows > 0) {
                $errorMessage = "Username is already in use";
            }

            else {

                $SQL = "INSERT INTO login (L1, L2) VALUES ($uname, md5($pword))";

                $result = mysql_query($SQL);

                mysql_close($db_handle);

                //=================================================================================
                //	START THE SESSION AND PUT SOMETHING INTO THE SESSION VARIABLE CALLED login
                //	SEND USER TO A DIFFERENT PAGE AFTER SIGN UP
                //=================================================================================

                session_start();
                $_SESSION['login'] = "1";
                $_SESSION['username'] = $uname;
                header ("Location: page1.php");

            }

        }
        else {
            $errorMessage = "Database Not Found";
        }




    }

}
?>
    <html>
    <head>
        <link type="text/css" rel="stylesheet" href="WristTrainerNewAccount.css" />
        <title>Wrist Trainer</title>
    </head>
     
    <body>
    <div>
  
  		<div id="whiteBox">
        <h1 id="creatAccount">Create your Wrist Trainer Account</h1>
    
            <form name="login" action="WristTrainerNewAccount.php" method="get" accept-charset="utf-8">
     
     			<email>
                <p><label for="usermail">Email</label>
                    <input style="width: 200px; height: 30px;" type="email" name="username" placeholder="yourname@email.com" required></p>
                </email>
     
     			<password>
                <p><label for="password">Password</label>
                    <input style="width: 200px; height: 30px;" type="password" name="password" placeholder="password" required></p>
     			</password>
     			
     			<confirmPassword>
                <p><label for="passwordConf">Confirm Password</label>
                    <input style="width: 200px; height: 30px;" type="password" name="passwordConf" placeholder="password" required></p>
     			</confirmPassword>
     
     			<submit>
                <p><input type="submit" value="Submit"></p>
                </submit>
                
                <img id="heart" src="Heart.jpg" alt="" />
     
            </form>
        </div>
     
        <div id="background" style='position:absolute;z-index:-1;left:0;top:0;width:100%;height:100%'>
          <img src='NewAccount.jpg' style='width:100%;height:100%' alt='[]' />
        </div>
     
    </div>
    </body>
    </html>

