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
$title       = "";
$image_link  = "";
$news_date   = "";
$description = "";
$source      = "";
$sukses      = "";
$error       = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id          = $_GET['id'];
    $sql1        = "delete from db_news where id = '$id'";
    $q1          = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses  = "Berhasil hapus data, Halaman akan direfresh dalam 5 detik!";
    } else {
        $error   = "Gagal melakukan delete data, Halaman akan direfresh dalam 5 detik!";
    }
}

if ($op == 'edit') {
    $id          = $_GET['id'];
    $sql1        = "select * from db_news where id = '$id'";
    $q1          = mysqli_query($conn, $sql1);
    $r1          = mysqli_fetch_array($q1);
    $title       = $r1['title'];
    $image_link  = $r1['image_link'];
    $news_date   = $r1['news_date'];
    $description = $r1['description'];
    $source      = $r1['source'];

    if ($title == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) { //untuk create
    $title        = $_POST['title'];
    $image_link   = $_POST['image_link'];
    $news_date    = $_POST['news_date'];
    $description  = $_POST['description'];
    $source       = $_POST['source'];

    if ($title && $image_link && $news_date && $description && $source) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update db_news set title = '$title', image_link = '$image_link', news_date = '$news_date', description = '$description', source = '$source' where id = '$id'";
            $q1         = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate, Halaman akan direfresh dalam 5 detik!";
            } else {
                $error  = "Data gagal diupdate, Halaman akan direfresh dalam 5 detik!";
            }
        } else { //untuk insert
            $sql1   = "insert into db_news(title, image_link, news_date, description, source) values ('$title','$image_link','$news_date','$description','$source')";
            $q1     = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses     = "Berhasil memasukkan data baru, Halaman akan direfresh dalam 5 detik!";
            } else {
                $error      = "Gagal memasukkan data, Halaman akan direfresh dalam 5 detik!";
            }
        }
    } else {
        $error = "Silakan masukkan semua data, Halaman akan direfresh dalam 5 detik!";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>News Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="image/logo.png">
</head>

<body>
    <div style="margin-top: 10%; margin-bottom: 15%;">
        <nav class="navbar navbar-default" role="navigation">
            <a class="navbar-brand" href="index.php"><img src="image/header.png" alt="" style="width: 100%; height: 100%;"></a>
        </nav>
    </div>

    <div style="margin-left: 20%; margin-right: 20%; margin-bottom: 2%;">
        <div class="card">
            <div class="card-header text-center" style="color : black">
                Input atau Edit Berita
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh: 3; url=news.php");
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh: 3; url=news.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-5 row">
                        <label for="title" class="col-sm-2 col-form-label" style="color: black">Judul Berita</label>
                        <div class="col-sm-10">
                            <input type="text" style="height: 150%;" class="form-control" id="title" name="title" value="<?php echo $title ?>">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label for="image_link" class="col-sm-2 col-form-label" style="color : black">Link Gambar Berita</label>
                        <div class="col-sm-10">
                            <input type="text" style="height: 150%;" class="form-control" id="image_link" name="image_link" value="<?php echo $image_link ?>">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label for="description" class="col-sm-2 col-form-label" style="color : black">Deskripsi Singkat</label>
                        <div class="col-sm-10">
                            <input type="text" style="height: 150%;" class="form-control" id="description" name="description" value="<?php echo $description ?>">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label for="source" class="col-sm-2 col-form-label" style="color : black">Link Sumber Berita</label>
                        <div class="col-sm-10">
                            <input type="text" style="height: 150%;" class="form-control" id="source" name="source" value="<?php echo $source ?>">
                        </div>
                    </div>
                    <div class="mb-5 row">
                        <label for="news_date" class="col-sm-2 col-form-label" style="color : black">Tanggal Berita</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="news_date" name="news_date" value="<?php echo $news_date ?>">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" style="width: 95%;" name="simpan" value="Simpan Data" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div style="margin-left: 10%; margin-right: 10%;">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr class="text-center info fw-normal" style="vertical-align: middle; background-color: yellow;">
                    <th scope="col">No</th>
                    <th scope="col" style="width: 25%;">Judul Berita</th>
                    <th scope="col" style="width: 5%;">Link Gambar Berita</th>
                    <th scope="col" style="width: 25%;">Deskripsi Singkat</th>
                    <th scope="col" style="width: 5%;">Link Sumber Berita</th>
                    <th scope="col" style="width: 5%;">Tanggal Berita <br> (YYYY/MM/DD)</th>
                    <th scope="col" style="width: 30%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql2   = "select * from db_news order by news_date desc";
                $q2     = mysqli_query($conn, $sql2);
                $urut   = 1;
                while ($r2 = mysqli_fetch_array($q2)) {
                    $id             = $r2['id'];
                    $title          = $r2['title'];
                    $image_link     = $r2['image_link'];
                    $news_date      = $r2['news_date'];
                    $description    = $r2['description'];
                    $source         = $r2['source'];
                ?>

                    <tr class="info fw-normal" style="vertical-align: middle;">
                        <th class="text-center" scope="row"><?php echo $urut++ ?></th>
                        <td><?php echo $title ?></td>
                        <td><a target="_blank" href="<?php echo $image_link ?>"><?php echo $image_link ?></a></td>
                        <td><?php echo $description ?></td>
                        <td><a target="_blank" href="<?php echo $source ?>"><?php echo $source ?></a></td>
                        <td class="text-center"><?php echo $news_date ?></td>
                        <td class="text-center">
                            <a href="news.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                            <a href="news.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau hapus data?')"><button type="button" class="btn btn-danger">Delete</button></a>
                        </td>
                    </tr>

                <?php
                }
                ?>
            </tbody>
        </table>
    </div>


    <div class="container">
        <div class="content">
            <a href="index.php" class="btn">Kembali</a>
        </div>
        <div class="content">
            <a href="logout.php" class="btn">logout</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>

</html>