<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="./dashStyl.css">
</head>

<?php
    $user = $_GET['usernm'];
    $flastyr = $flastmon = $ftoday=0.0;
    $clastyr = $clastmon = $ctoday=0.0;
    $slastyr = $slastmon = $stoday=0.0;
    $elastyr = $elastmon = $etoday=0.0;

    $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
    if (!$con) {
        echo "Connect failed: %s\n".mysqli_connect_error();
        exit();
    }

    $flastyr = mysqli_query($con,"select this_year from expense where username='$user' and expenseType='food';") or die(mysqli_error($con));
    $flastmon = mysqli_query($con,"select this_month from expense where username='$user' and expenseType='food';") or die(mysqli_error($con));
    $ftoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='food';") or die(mysqli_error($con));
    
    $clastyr = mysqli_query($con,"select this_year from expense where username='$user' and expenseType='clothes';") or die(mysqli_error($con));
    $clastmon = mysqli_query($con,"select this_month from expense where username='$user' and expenseType='clothes';") or die(mysqli_error($con));
    $ctoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='clothes';") or die(mysqli_error($con));

    $slastyr = mysqli_query($con,"select this_year from expense where username='$user' and expenseType='study';") or die(mysqli_error($con));
    $slastmon = mysqli_query($con,"select this_month from expense where username='$user' and expenseType='study';") or die(mysqli_error($con));
    $stoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='study';") or die(mysqli_error($con));

    $elastyr = mysqli_query($con,"select this_year from expense where username='$user' and expenseType='extras';") or die(mysqli_error($con));
    $elastmon = mysqli_query($con,"select this_month from expense where username='$user' and expenseType='extras';") or die(mysqli_error($con));
    $etoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='extras';") or die(mysqli_error($con));

    $f=$c=$s=$e=0.0;$msgg="";
    while($row=mysqli_fetch_assoc($flastmon)){
        $f=(float)$row;
    }
    while($row=mysqli_fetch_assoc($clastmon)){
        $c=(float)$row;
    }
    while($row=mysqli_fetch_assoc($slastmon)){
        $s=(float)$row;
    }
    while($row=mysqli_fetch_assoc($elastmon)){
        $e=(float)$row;
    }
    if(($f+$c+$s+$e)>=3000){
        $msgg="You are overspending..:(";
    }
    else{
        $msgg = "You are doing well..:)";
    }

?>

<body>                                 
    <nav id="navigation">
        <ul id="nav-list">
            <li id="one"><a href="./addTrans.php?usernm=<?php echo $user?>">Add/Delete transaction</a></li>
            <li id="two"><a href="./viewTrans.php?usernm=<?php echo $user?>">View transactions</a></li>
            <li id="three"><a href="./login.php">Logout</a></li>
        </ul>
    </nav><!--id=navigation-->

    <div id="money-slider">
        <div id="top">
            <h3>Food</h3>
            <p>Expense last year: Rs. <?php while($row=mysqli_fetch_assoc($flastyr)){echo $row["this_year"];}?></p>
            <p>Expense last month: Rs. <?php while($row=mysqli_fetch_assoc($flastmon)){echo $row["this_month"];}?></p>
            <p>Expense today: Rs. <?php while($row=mysqli_fetch_assoc($ftoday)){echo $row["today"];}?></p>
        </div><!--id=top-->

        <div id="money-slider-line">
            <div id="left">
                <h3>Clothes</h3>
                <p>Expense last year: Rs. <?php while($row=mysqli_fetch_assoc($clastyr)){echo $row["this_year"];}?></p>
                <p>Expense last month: Rs. <?php while($row=mysqli_fetch_assoc($clastmon)){echo $row["this_month"];}?></p>
                <p>Expense today: Rs. <?php while($row=mysqli_fetch_assoc($ctoday)){echo $row["today"];}?></p>
            </div>      <!--id=left-->
            <div id="center-box">
                <!--Notification message will be printed here-->
                <h1 id="status"><?php echo $msgg?></h1>
            </div>      <!--id=center-box-->
            <div id="right"><h3>Study material</h3>
            <p>Expense last year: Rs.<?php while($row=mysqli_fetch_assoc($slastyr)){echo $row["this_year"];}?></p>
            <p>Expense last month: Rs. <?php while($row=mysqli_fetch_assoc($slastmon)){echo $row["this_month"];}?></p>
            <p>Expense today: Rs. <?php while($row=mysqli_fetch_assoc($stoday)){echo $row["today"];}?></p>
            </div>      <!--id=right-->
        </div><!--id=money-slider-line-->      
        <div id="bottom"><h3>Extras</h3>
            <p>Expense last year: Rs. <?php while($row=mysqli_fetch_assoc($elastyr)){echo $row["this_year"];}?></p>
            <p>Expense last month: Rs. <?php while($row=mysqli_fetch_assoc($elastmon)){echo $row["this_month"];}?></p>
            <p>Expense today: Rs. <?php while($row=mysqli_fetch_assoc($etoday)){echo $row["today"];}?></p>
        </div><!--id=bottom-->
    </div><!--id=money-slider-->
    <a id="billy" class="btn btn-primary" href="#" role="button">Ask Billy</a>
</body>
</html>