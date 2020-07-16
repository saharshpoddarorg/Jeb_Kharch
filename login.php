<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SampleLog</title>
    <link rel="stylesheet" href="login.css">
</head>
<?php
    $usrn = $pass = "";
    $usrerr = $passerr = "";$msg="";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["Email"])){
            $usrerr="Username required";
        }
        else{
            $usrn=$_POST["Email"];
        }
    }
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["Password"])){
            $passerr="Password required";
        }
        else{
            $pass=$_POST["Password"];
        }
    }
    if(isset($_POST["login"])){
        if($usrerr==""&&$passerr==""){
            $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
            if (!$con) {
                echo "Connect failed: %s\n".mysqli_connect_error();
                exit();
            }
        $user = mysqli_query($con,"select * from signup where username='$usrn';");
        $nrows =mysqli_num_rows($user);
        if($nrows==0){
            $msg = "Username does not exist!";
        }
        else{
            $pwd = mysqli_query($con,"select password from signup where username='$usrn';");
            while($row = mysqli_fetch_assoc($pwd)){
                $cpwd = $row['password'];
            }
            if($cpwd==$pass){
                header("Location: ./dashboard.php?usernm=".urlencode($usrn));
            }
            else{
                $msg = "Incorrect username/password.....pls check!";
            }
        }
    }
    else{
        $msg = "Asterisked fields are required!";

    }
}
?>
<body>
    <div class="log">
        <h1 id="heads"><span style="border: 2px #333945 solid;padding:8px">JEB KHARCH</span></h1>
        <form action="#" method="POST">
            <b>Username:</b><span style="color: red">*<?php echo $usrerr;?></span><input type="text" name="Email" placeholder="ask@gmail.com"><br/>
            <b>Password:</b><span style="color: red">*<?php echo $passerr;?></span><input type="password" name="Password"><br/>
            <input type="submit" value="login" name="login">
            <div style="color:red;">*<?php echo $msg?></div>
            <a href="#"><p id="for">Forgot password?</p></a>
            <a href="signup.php"><p id="new">New user? Sign up here</p></a>
        </form>
    </div>
</body>
</html>