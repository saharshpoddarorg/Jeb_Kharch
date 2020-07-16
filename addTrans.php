<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./addTran.css">
</head>

<?php
    $user = $_GET["usernm"];
    $dat=$tpass=$ttype=$tamt=$tdel="";
    $msg=$msg2=$msg3=$msg4="";
    
    if(isset($_POST["add-submit"])){
        if($_SERVER["REQUEST_METHOD"]=="POST"){
            $dat = $_POST["date-trans"];
            $tpass = $_POST["add-pass"];
            $ttype = $_POST["add-type"];
            $tamt = $_POST["add-amt"];
            
        }
        if($dat==""||$tpass==""||$ttype==""||$tamt==""){
            $msg="Please fill all asterisked fields";
        }
        else{
            $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
            if (!$con) {
                echo "Connect failed: %s\n".mysqli_connect_error();
                exit();
            }
            
            $paschk = mysqli_query($con,"select * from transaction where transPassword='$tpass';");
            $nrows =mysqli_num_rows($paschk);
            if($nrows==0){
                $query = mysqli_query($con,"insert into transaction values('$dat','$tpass','$ttype','$tamt','$user');");
                if($query){
                    $msg2="Transaction succesfully added!";
                    $msg="";
                }
                else{
                    $msg="Transaction failed";
                    echo $user;
                }

                //changing transaction table
                $last = mysqli_query($con,"select * from transaction where usrnm='$user' ORDER BY dat DESC LIMIT 1,1;");   //checking the previous row, if table is empty returns 0 rows
                $dt = strtotime($dat);
                $day = date('d',$dt);
                $month = date('m',$dt);
                $year = date('Y',$dt);
                $lday=$lmonth=$lyear=$lst=$lst2="";
                while($ldat=mysqli_fetch_assoc($last)){
                    $lst = $ldat["dat"];
                }
                $lst2 = strtotime($lst);
                $lday = date('d',$lst2);
                $lmonth = date('m',$lst2);
                $lyear = date('Y',$lst2);
                echo $lday;

                // echo $lyear;
                
                //calculating today,last month and last year transaction
                $toamt=$tmamt=$tyamt=$tamt;
                if($lday==$day){
                    echo "same day";
                    //its same day
                    $q = mysqli_query($con,"select * from expense where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));
                    $nq = mysqli_num_rows($q);
                    echo $nq;
                    if($nq==0){
                        //no record so insert record
                        echo "inside";
                        $ress = mysqli_query($con,"insert into expense values('$user','$ttype','$tamt','$tamt','$tamt');") or die(mysqli_error($con));
                    
                    }
                    else{
                        //record exists, update record, fetch the amts and update them
                        //1. find all the rows and sum their amts.(done by $q) 2. Add new transaction to the amt 3.Update the row.
                        while($row=mysqli_fetch_assoc($q)){
                            // echo (float)$row["today"];
                            // echo $toamt;
                            // echo ((float)$row["today"]+$toamt);
                            $toamt+=(float)$row["today"];
                            $tmamt+=(float)$row["this_month"];             //may cause problems in datatypes
                            $tyamt+=(float)$row["this_year"];
                        }
                        echo $toamt;
                        $ress = mysqli_query($con,"update expense set today='$toamt', this_month='$tmamt',this_year='$tyamt' where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));
                    }
                }

                else if($month==$lmonth){
                    echo "same month";
                    //its same month
                    $q = mysqli_query($con,"select * from expense where username='$user' and expenseType='$ttype';")or die(mysqli_error($con));
                    $nq = mysqli_num_rows($q);
                    if($nq==0){
                        //no record so insert record
                        $ress = mysqli_query($con,"insert into expense values('$user','$ttype','$tamt','$tamt','$tamt');") or die(mysqli_error($con));
                        if($ress){
                            echo "doneeeeee";
                        }
                    }
                    else{
                        while($row=mysqli_fetch_assoc($q)){
                            $toamt=(float)$tamt;
                            echo $toamt;
                            $tmamt+=(float)$row["this_month"];             //may cause problems in datatypes
                            $tyamt+=(float)$row["this_year"];
                        }
                        $ress = mysqli_query($con,"update expense set today='$toamt',this_month='$tmamt',this_year='$tyamt' where username='$user' and expenseType='$ttype';") or die(mysqli_error($con)); 
                    }

                }

                else if($year==$lyear){
                    echo "same year";
                    //same year
                    $q = mysqli_query($con,"select * from expense where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));
                    $nq = mysqli_num_rows($q);
                    if($nq==0){
                        //no record so insert record
                        $ress = mysqli_query($con,"insert into expense values('$user','$ttype','$tamt','$tamt','$tamt');") or die(mysqli_error($con));
                    }
                    else{
                        while($row=mysqli_fetch_assoc($q)){
                             //may cause problems in datatypes
                             $toamt=(float)$tamt;
                             $tmamt=(float)$tamt;
                             $tyamt+=(float)$row["this_year"];
                        }
                        $ress = mysqli_query($con,"update expense set today='$toamt',this_month='$tmamt',this_year='$tyamt' where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));

                    }

                }

                else{
                    $q = mysqli_query($con,"select * from expense where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));
                    $nq = mysqli_num_rows($q);
                    if($nq==0){
                        //no record so insert record
                        $ress = mysqli_query($con,"insert into expense values('$user','$ttype','$tamt','$tamt','$tamt');") or die(mysqli_error($con));
                    }
                    else{
                        while($row=mysqli_fetch_assoc($q)){
                             //may cause problems in datatypes
                             $toamt=(float)$row["today"];
                             $tmamt=(float)$row["this_month"];
                             $tyamt=(float)$row["this_year"];
                        }
                        $ress = mysqli_query($con,"update expense set today='$toamt',this_month='$tmamt',this_year='$tyamt' where username='$user' and expenseType='$ttype';") or die(mysqli_error($con));

                    }

                }
            }

            else{
                $msg="Transaction password already exists! Please try a different one";
            }
            mysqli_close($con);
        }
        
    }
    if(isset($_POST["del-submit"])){
        $tdel = $_POST["del-pass"];
        if($tdel==""){
            $msg3="Please enter a valid transaction password";
        }
        else{
            $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
            if (!$con) {
                echo "Connect failed: %s\n".mysqli_connect_error();
                exit();
            }
            $pcheck=mysqli_query($con,"select * from transaction where transPassword='$tdel';");
            $nr = mysqli_num_rows($pcheck);
            if($nr==0){
                $msg3="Wrong password! Try again...";
            }
            else{
                $res = mysqli_query($con,"delete from transaction where transPassword='$tdel';");
                $msg4="Deletion successful!";
            }
            mysqli_close($con);
        }
        
    }
?>

<body>
    <div class="container">
        <div id="add">
            <h1 style="color: blue;">Add Transaction</h1>
            <form action="#" method="POST">
                <h2>Transaction date (Can't delete previous-day transactions once you enter next date)</h2>
                <input type="date" id="date-trans" name="date-trans"><span style="color: red;">*</span>
                <h2>Transaction password (Choose one and remember it)</h2>
                <input type="password" id="add-pass" name="add-pass" placeholder="********"><span style="color: red;">*</span>
                <h2>Transaction type</h2>
                <input type="text" id="add-type" name="add-type" placeholder="Type of transaction..."><span style="color: red;">*</span>
                <h2>Transaction amount</h2>
                <input type="number" id="add-amt" name="add-amt" placeholder="Transaction amount..."><span style="color: red;">*</span><br/>
                <div style="color:red;"><?php echo $msg;?></div><div style="color:green;"><?php echo $msg2;?></div>
                <input type="submit" name="add-submit" value="Add Transaction" id="addS">
            </form>
        </div>
        <br/><br/>
        <div id="delete">
            <h1 style="color: blue">Delete Transaction</h1>
            <form action="#" method="POST">
                <h2>Transaction password</h2>
                <input type="password" id="del-pass" name="del-pass"><span style="color: red;">*<?php echo $msg3?></span><br/>
                <input type="submit" name="del-submit" value="Delete Transaction" id="delS">
                <div style="color:green;"><?php echo $msg4;?></div>
            </form>
        </div>
    </div>
    <div id="link buttons" class="btn-group" role="group" aria-label="Basic example">
        <form method="get" action="./dashboard.php">
            <button type="submit" class="btn btn-secondary">Goto Dashboard</button>
        </form>
        <form method="get" action="./viewTrans.php">  
            <button type="submit" class="btn btn-secondary">Goto View Transaction</button>
        </form>  
    </div><!--class=btn-group-->
    <a id="billy" class="btn btn-primary" href="#" role="button">Ask Billy</a>  
</body>
</html>