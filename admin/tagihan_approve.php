<?php  
session_start();
require_once "../config/koneksi.php";
if(!isset($_SESSION['admin'])){
  header("Location: login.php");
}
$adminId = $_SESSION['admin'];
$resPanitia = mysqli_query($conn, "SELECT * FROM admin WHERE id='$adminId'");
$rowPanitia = mysqli_fetch_assoc($resPanitia);
$siswaId = $_GET['id'];
$resData = mysqli_query($conn, "SELECT *, user.id AS userID FROM tagihan INNER JOIN rumah ON tagihan.rumah_id = rumah.id INNER JOIN user ON tagihan.rumah_id = user.rumah_id WHERE tagihan.id='$siswaId'");
$rowData = mysqli_fetch_assoc($resData);
$tarif = mysqli_query($conn, "SELECT * FROM tarif");
$rowTarif = mysqli_fetch_assoc($tarif);
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $status = $_POST['status'];
  $userID = $rowData['userID'];
  if($status == 0){
    unlink("../images/bukti/" . $rowData['bukti_bayar']);
    mysqli_query($conn, "UPDATE tagihan SET bukti_bayar='' WHERE id='$siswaId'");
    $pesan = "Pembayaran IPL pada bulan " . bulanIndo($rowData['bulan']) . " " . $rowData['tahun'] . " ditolak";
  }elseif ($status == 2) {
    $pesan = "Pembayaran IPL pada bulan " . bulanIndo($rowData['bulan']) . " " . $rowData['tahun'] . " diterima";
  }
  mysqli_query($conn, "UPDATE tagihan SET status='$status' WHERE id='$siswaId'");
  mysqli_query($conn, "INSERT INTO pesan (user_id, pesan, status) VALUES ('$userID', '$pesan', '0')");
  header("Location: tagihan.php");
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <?php include('../config/title.php'); ?>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="../lib/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="../plugins/iCheck/flat/blue.css">
  <!-- Morris chart -->
  <link rel="stylesheet" href="../plugins/morris/morris.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="../plugins/jvectormap/jquery-jvectormap-1.2.2.css">
  <!-- Date Picker -->
  <link rel="stylesheet" href="../plugins/datepicker/datepicker3.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="shortcut icon" type="images/x-icon" href="../images/favicon.png">
  <link rel="stylesheet" href="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>IPL</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>IPL Payment</b> | Admin</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../images/user.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $rowPanitia['nama']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php include('../config/nav.php'); ?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Approve Tagihan</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
          <a href="../images/bukti/<?php echo $rowData['bukti_bayar']; ?>"><button class="btn btn-primary btn-flat">Lihat Bukti Pembayaran</button></a><br><br>
          <form action="" method="post">
      <div class="form-group has-feedback">
        <label>Status</label>
          <select name="status" class="form-control">
            <option value="0" <?php if($rowData['status'] == 0){echo "selected";} ?>>Belum dibayar</option>
            <option value="1" <?php if($rowData['status'] == 1){echo "selected";} ?>>Pending</option>
            <option value="2" <?php if($rowData['status'] == 2){echo "selected";} ?>>Lunas</option>
          </select>
        </div>
        <div class="row">
          
        <div class="col-xs-8">
          <button type="submit" class="btn btn-primary btn-flat">Simpan</button>
        </div>
        <div class="col-xs-4">
        </div>
        </div>
        </form>
        </div>
            <!-- /.box-body -->
          </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2018 Kelompok 10.</strong> All rights
    reserved.
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.3 -->
<script src="../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="../lib/bootstrap/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="../plugins/morris/morris.min.js"></script>
<!-- Sparkline -->
<script src="../plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="../plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="../plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="../plugins/knob/jquery.knob.js"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="../plugins/daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="../plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="../plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="../plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../dist/js/app.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="../dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../dist/js/demo.js"></script>
</body>
</html>
