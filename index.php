<?php
session_start();
if($_SESSION["loggedin"] === true){
   header("location: /student");
   exit();
}
$auth_flag=0;
if (isset($_POST['login']))
{
  require $_SERVER['DOCUMENT_ROOT'].'/db_connect.php';
  $_SESSION['message']='';
  $mysqli = OpenCon();
  $username = $mysqli->real_escape_string($_POST['username']);
  $password = $_POST['password'];

  $sql = "SELECT id, password FROM students WHERE username = ?";

          if($stmt = $mysqli->prepare($sql)){
              $stmt->bind_param("s", $param_username);
              $param_username = $username;
              if($stmt->execute())
              {
                  $stmt->store_result();
                  if($stmt->num_rows == 1)
                  {
                      $stmt->bind_result($id, $username, $hashed_password);
                      if($stmt->fetch()){
                          if(password_verify($password, $hashed_password)){
                              session_start();
                              $_SESSION["loggedin"] = true;
                              $_SESSION["id"] = $id;
                              $_SESSION["username"] = $username;
                              header("location: https://smashsdgs.me/start.php");
                          } else
                          {
                              $auth_flag=1;
                          }
                      }
                  } else
                  {
                      $auth_flag=2;
                  }
              } else{
                  echo "<span>Oops! Something went wrong. Please try again later.</span>";
              }
          }

          $stmt->close();
          CloseCon($mysqli);
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login</title>
  </head>
  <body>
    <?php
    if($auth_flag==1){echo "<div class='alert alert-warning logauth alert-dismissible fade show'>The password you entered was not valid.<button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";}
    if($auth_flag==2){echo "<div class='alert alert-warning logauth alert-dismissible fade show'>No account found with that username.<button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";}
    if($auth_flag==3){echo "<div class='alert alert-succcess logauth alert-dismissible fade show'>Your account has been successfully verified.<button type='button' class='close' data-dismiss='alert' aria-label='Close'> <span aria-hidden='true'>&times;</span></button></div>";}
     ?>
  <div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
					<span class="login100-form-title p-b-26">
						Welcome
					</span><br><br>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" id="username" name="username" placeholder="Username (KTU ID)" required>
					</div>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="password" id="password" name="pass" placeholder="Password" required>
					</div>

					<button class="login100-form-btn" id="login" name="login" type="submit">Login</button>

					<div class="text-center newreg">
						<a href="">New Student?</a>
            <a href="">New Teacher?</a>
					</div>
				</form>
			</div>
		</div>
	</div>

</body>
</html>
