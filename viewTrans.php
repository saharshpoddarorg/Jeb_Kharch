<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>View Transaction</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <link rel="stylesheet" href="./viewTran.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<?php 
  $user = $_GET["usernm"];
  $con=mysqli_connect('localhost','root','','Jeb_kharch') or die(mysql_error());
    if (!$con) {
        echo "Connect failed: %s\n".mysqli_connect_error();
        exit();
    }

    $ftoday=$ctoday=$stoday=$etoday=0.0;
    $ftoday=mysqli_query($con,"select today from expense where username='$user' and expenseType='food';") or die(mysqli_error($con));
    $ctoday=mysqli_query($con,"select today from expense where username='$user' and expenseType='clothes';") or die(mysqli_error($con));
    $stoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='study';") or die(mysqli_error($con));
    $etoday = mysqli_query($con,"select today from expense where username='$user' and expenseType='extras';") or die(mysqli_error($con));

    $f=$c=$s=$e=0.0;
    while($row=mysqli_fetch_assoc($ftoday)){
      $f=(float)$row["today"];
  }
  while($row=mysqli_fetch_assoc($ctoday)){
      $c=(float)$row["today"];
  }
  while($row=mysqli_fetch_assoc($stoday)){
      $s=(float)$row["today"];
  }
  while($row=mysqli_fetch_assoc($etoday)){
      $e=(float)$row["today"];
  }

  $tran = mysqli_query($con,"select dat,transType,transAmt from transaction where usrnm='$user' ORDER BY dat DESC LIMIT 10;") or mysqli_error($con);
?>
<body>
  <div class="container">
    <h1 id="exp-chart">Expense Chart for this Month....</h1>
    <div id="piechart"></div>
    <div id="transactions">
      <h1>Last 10 transactions:</h1>
      <!--List down last 10 records from transaction table--> 
      <table border="4">
        <thead>
          <th>Date</th>
          <th>Transaction Type</th>
          <th>Transaction Amount</th>
        </thead>
        <tbody>
          <?php 
            while($row=mysqli_fetch_row($tran)){
              echo "<tr>";
              echo "<td>$row[0]</td>";
              echo "<td>$row[1]</td>";
              echo "<td>$row[2]</td>";
              echo "</tr>";
            }
          ?>
        </tbody>
      </table>
    </div><!--id=transactions-->
  </div><!--class=container-->

  <div id="link buttons" class="btn-group" role="group" aria-label="Basic example">
    <form method="get" action="./dashboard.php">
      <button type="submit" class="btn btn-secondary">Goto Dashboard</button>
    </form>
    <form method="get" action="./addTrans.php">  
      <button type="submit" class="btn btn-secondary">Goto Add Transaction</button>
    </form>  
  </div><!--class=btn-group-->
  <a id="billy" class="btn btn-primary" href="#" role="button">Ask Billy</a>
    
  <!--?php
        List down last 5 records from transaction table
  ?-->

</body>

<script type="text/javascript">
  google.charts.load('current',{'packages':['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart(){
    var data = google.visualization.arrayToDataTable([
      ['Transaction type', 'Expense'],
      ['Food', <?php echo $f?>],
      ['Clothes', <?php echo $c?>],
      ['Study Material', <?php echo $s?>],
      ['Extras', <?php echo $e?>],
    ]);
    var options = {'title':'Average Day Expense', 'width':'50%', 'height':'50%'};
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }
</script>
</html>
