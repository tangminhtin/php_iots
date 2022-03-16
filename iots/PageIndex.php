<?php
// include database connection
include 'config.php';
if (isset($_POST['Logout'])) {
  header('location:login.php');
}

// select all data
$query = "SELECT id, Temperature, Humidity, pH, d, t FROM giamsat";
$stmt = $con->prepare($query);
$stmt->execute();

// this is how to get number of rows returned
$num = $stmt->rowCount();
?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>

  <style>
    html {
      font-family: Arial;
      display: inline-block;
      margin: 0px auto;
      text-align: center;
    }

    h1 {
      font-size: 2.0rem;
      text-align: center;
      padding-top: 100px;
      color: greenyellow;
      font-size: 60px;
    }

    p {
      font-size: 5.0rem;
      padding-top: 20px;
      padding-bottom: 0px;
    }

    .units {
      font-size: 1.8rem;
    }

    .dht-labels {
      font-size: 3.5rem;
      vertical-align: middle;
      padding-bottom: 15px;
    }

    body {
      background-image: url('iot2.jpg');
      background-repeat: no-repeat;
      background-size: 1920px;
    }

    #customChart {
      max-width: 800px;
      max-height: 400px;
    }
  </style>
</head>
<title>GIÁM SÁT THÔNG SỐ MÔI TRƯỜNG</title>

<body style=" padding-bottom:20%;">
  <div class="float-start mt-3" style="margin-left: 50px">
    <span>Welcome, User</span>
    <a name='Logout' class=" ">Logout</a>
  </div>

  <form action="" method="POST">
    <h1>GIÁM SÁT THÔNG SỐ MÔI TRƯỜNG</h1>
    <!-- Load data in background -->
    <div id="autodata"></div> <!-- refer to script.js and auto-refresh-data.php to understand what happen in it -->
  </form>

  <!-- show the real chart -->
  <div class="justify-content-md-center mt-5 mb-5" style="max-width: 900px; max-height: 500px; margin-left: auto; margin-right: auto;">
    <canvas id="speedChart" style="width: 900px; height: 500px"></canvas>
    <small>Biểu đồ về Temperature, Humidity, pH hiển thị trong ngày hiện tại</small>
  </div>



  <!-- model to confirm các thông số cài đặt của Temperature, Humidity -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Thông báo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Bạn có đồng ý với thiết lập này không?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
          <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="compareValue()"> Đồng ý
          </button>
        </div>
      </div>
    </div>
  </div>


  <!-- Setup các cài đặt -->
  <div class="container mt-5 mb-5" style="margin-top: 1rem">
    <div class="row justify-content-md-center">
      <div class="col-2 border border-success rounded m-2">
        <div class="column p-1 justify-content-md-center">
          <div id="fantest">
            <i id="fanIcon" class="bi bi-fan" style="font-size: 5rem; color: #667480;">
            </i>
          </div>
          <div class="col justify-content-md-center">
            <div class="badge bg-primary " style="margin-bottom: 0.5rem">
              Quạt
            </div>
          </div>
          <div class="col justify-content-md-center">
            <div class="form-switch">
              <input id="fanSwitch" class="form-check-input" type="checkbox" onclick="changeStatus(this)">
            </div>
          </div>
        </div>
      </div>

      <div class="col-2 border border-success rounded m-2">
        <div class="column p-1 justify-content-md-center">
          <i id="lightIcon" class="bi bi-lightbulb-fill" style="font-size: 5rem; color: #667480;"></i>
          <div class="col justify-content-md-center">

            <div class="badge bg-primary text-wrap" style="margin-bottom: 0.5rem">
              Đèn
            </div>
          </div>
          <div class="col justify-content-md-center">
            <div class="form-switch">
              <input id="lightSwitch" class="form-check-input" type="checkbox" onclick="changeStatus(this)">
            </div>
          </div>
        </div>
      </div>
      <div class="col-2 border border-success rounded m-2">
        <div class="column p-1 justify-content-md-center">
          <i id="sprayIcon" class="bi bi-cloud-drizzle-fill" style="font-size: 5rem; color: #667480;"></i>
          <div class="col justify-content-md-center">

            <div class="badge bg-primary text-wrap" style="margin-bottom: 0.5rem">
              Phun sương
            </div>
          </div>
          <div class="col justify-content-md-center">
            <div class="form-switch">
              <input id="spraySwitch" class="form-check-input" type="checkbox" onclick="changeStatus(this)">
              <!--                     <input id="spraySwitch" class="form-check-input" type="checkbox" onclick="changeStatus(this)">-->
            </div>
          </div>
        </div>
      </div>

      <!-- Setup and input Temperature, Humidity -->
      <div class="container p-5">
        <form>
          <div class="row justify-content-md-center">
            <div class="col-4 border border-success rounded m-2 p-5">
              <h2 class="mb-5">Cài đặt thông số</h2>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1" style="color: red">
                  <i class='fa fa-thermometer-half' style="font-size:1.5rem;color:red; margin-right: 1rem"></i>
                  Nhiệt độ
                </span>
                <input type="number" id="temInput" class="form-control" placeholder="Nhiệt độ" aria-label="Nhiệt độ" aria-describedby="basic-addon1">
              </div>
              <div class="input-group mb-3">
                <span class="input-group-text" id="basic-addon1" style="color: green;">
                  <i class='fa fa-tint' style='font-size:1.5rem;color:#75e095; margin-right: 1rem'></i>
                  Độ ẩm</span>
                <input type="number" id="huInput" class="form-control" placeholder="Độ ẩm" aria-label="Độ ẩm" aria-describedby="basic-addon1">
              </div>
              <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Xác nhận
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>

  <script src="script.js"></script> <!-- all script -->
  <script src="customChart.js"></script> <!-- script to show chart -->

</body>

</html>