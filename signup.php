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
    $fnm=$lnm=$usrnm=$pass=$cpass="";
    $fnmerr=$lnmerr=$usrerr=$passerr=$cpasserr="";

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["fname"])){
            $fnmerr="First Name required";
        }
        else{
            $fnm=$_POST["fname"];
        }
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["uname"])){
            $usrerr="Username required";
        }
        else{
            $usrnm=$_POST["uname"];
        }
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["pass"])){
            $passerr="Password required";
        }
        else{
            $pass=$_POST["pass"];
        }
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(empty($_POST["cpass"])){
            $cpasserr="Confirm your password";
        }
        else{
            $cpass=$_POST["cpass"];
        }
    }
    $msg="";$msg2="";
    if(isset($_POST["submit"])){
        
        if($fnmerr==""&& $usrerr==""&& $passerr=="" && $cpasserr==""&&$pass==$cpass){
            $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
            if (!$con) {
                echo "Connect failed: %s\n".mysqli_connect_error();
                exit();
            }
            $query=mysqli_query($con,"select * from signup where username='$usrnm';") or die(mysqli_error($con));
    
            $nrows =mysqli_num_rows($query);
            if($nrows==0){
                $sql="insert into signup values('$fnm','$lnm','$usrnm','$pass');";
                $result=mysqli_query($con,$sql) or die(mysqli_error($con));
                if($result){
                     $msg2="Account successfully created";
                     $msg="";
                }
                else{
                    $msg="Account creation failed";
                }
            }
            else{
                $msg="That username already exists! Please try again with another.";
            }
            mysqli_close($con);
        }
        else{
            if($pass!=$cpass){
                $msg="Password not confirmed!!";
            }
            $msg=$msg."  The asterisked fields are required";
        }
    }
    else{
        echo 'Something wrong';
    }
   
?>
<body>
    <div class="log">
        <h1 id="heads"><span style="border: 2px #333945 solid;padding:8px">JEB KHARCH SIGN UP</span></h1>
        <form action="#" method="POST">
            <b>First Name:</b><span id="ferr" style="color:red;">*<?php echo $fnmerr;?></span><input type="text" name="fname" placeholder="John"><br/>
            <b>Last Name:</b><input type="text" name="lname"><br/>
            <b>Username:</b><span id="userr" style="color:red;">*<?php echo $usrerr;?></span><input type="text" name="uname"><br/>
            <b>Password:</b><span id="paerr" style="color:red;">*<?php echo $passerr;?></span><input type="password" name="pass"><br/>
            <b>Confirm Password:</b><span id="cpaerr" style="color:red">*<?php echo $cpasserr;?></span><input type="password" name="cpass"><br/>
            <input type="submit" value="Submit" name="submit"><div style="color:red"><?php echo $msg?><span style="color:green"><?php echo $msg2?></span></div>
            <a href="login.php"><p id="new">Already have an account? Sign in</p></a>
        </form>
    </div>
</body>
</html>