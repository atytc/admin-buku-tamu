<?php

@include 'config.php';

session_start();

if (!isset($_SESSION['user_name'])) {
   header('location:login.php');
}

$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "1dprd";

$conn       = mysqli_connect($host, $user, $pass, $db);
if (!$conn) { //cek koneksi
   die("Tidak bisa terkoneksi ke database");
}
$title         = "";
$image_link    = "";
$description   = "";
$news_date     = "";
$source        = "";
$sukses        = "";
$error         = "";

if (isset($_GET['op'])) {
   $op = $_GET['op'];
} else {
   $op = "";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Satu DPRD Langkat</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/styles.css">
   <link rel="icon" href="image/logo.png">

</head>

<body>
   <div style="margin-top: 10%;">
      <nav class="navbar navbar-default" role="navigation">
         <a class="navbar-brand" href="#"><img src="image/header.png" alt="" style="width: 100%; height: 100%;"></a>
      </nav>
   </div>

   <div class="container">
      <div class="content">
         <h1 style="margin-top: 20%;">Selamat datang <span>
               <?php echo $_SESSION['user_name'] ?>
            </span></h1>
      </div>
   </div>

   <div style="margin-left: 30%; margin-right: 30%; margin-bottom: 3%;">
      <div class="row row-cols-2 row-cols-md-1 g-4">
         <div>
            <div class="col">
               <div class="card">

                  <?php
                  $sql2   = "select * from db_news order by news_date desc";
                  $q2     = mysqli_query($conn, $sql2);
                  $urut   = 1;
                  while ($r2 = mysqli_fetch_array($q2)) {
                     $id            = $r2['id'];
                     $title         = $r2['title'];
                     $image_link    = $r2['image_link'];
                     $description   = $r2['description'];
                     $news_date     = $r2['news_date'];
                     $source        = $r2['source'];
                  ?>

                     <a href="<?php echo $source ?>">
                        <img src="<?php echo $image_link ?>" class="card-img-top" alt="...">
                     </a>

                     <div style="margin: 2%;">
                        <h5 class="card-title"><?php echo $title ?></h5>
                        <p class="card-text"><?php echo $description ?> <a href="<?php echo $source ?>"> Baca Selengkapnya... </a></p>
                     </div><br>
               </div>
            </div>
         <?php
                  }
         ?>
         </div>
      </div>
   </div>

   <div style="margin-left: 20%; margin-right: 20%;">
      <div class="row row-cols-1 row-cols-md-3 g-4">
         <div class="container">
            <div class="col">
               <div class="card h-100">
                  <a href="visitor-book.php"><img src="image/bookl.png" class="card-img-top" alt="...">
                  </a>
               </div>
               <h5 style="text-align: center; margin-top: 10px;">BUKU TAMU</h5>
            </div>
         </div>

         <div class="container">
            <div class="col">
               <div class="card h-100">
                  <a href="news.php"><img src="image/news-icon.png" class="card-img-top" alt="...">
                  </a>
               </div>
               <h5 style="text-align: center; margin-top: 10px;">Berita Online</h5>
            </div>
         </div>
      </div>
   </div>

   <div class="container">
      <div class="content">
         <a href="logout.php" class="btn">logout</a>
      </div>
   </div>
</body>

</html>