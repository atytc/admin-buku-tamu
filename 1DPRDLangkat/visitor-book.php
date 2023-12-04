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
$nama        = "";
$asal       = "";
$tanggal     = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id          = $_GET['id'];
    $sql1        = "delete from db_visitor where id = '$id'";
    $q1          = mysqli_query($conn, $sql1);
    if ($q1) {
        $sukses  = "Berhasil hapus data, Halaman akan direfresh dalam 5 detik!";
    } else {
        $error   = "Gagal melakukan delete data, Halaman akan direfresh dalam 5 detik!";
    }
}

if ($op == 'edit') {
    $id          = $_GET['id'];
    $sql1        = "select * from db_visitor where id = '$id'";
    $q1          = mysqli_query($conn, $sql1);
    $r1          = mysqli_fetch_array($q1);
    $nama        = $r1['nama'];
    $asal        = $r1['asal'];
    $tanggal     = $r1['tanggal'];

    if ($nama == '') {
        $error = "Data tidak ditemukan";
    }
}

if (isset($_POST['simpan'])) { //untuk create
    $nama        = $_POST['nama'];
    $asal       = $_POST['asal'];
    $tanggal     = $_POST['tanggal'];

    if ($nama && $asal && $tanggal) {
        if ($op == 'edit') { //untuk update
            $sql1       = "update db_visitor set nama = '$nama', asal = '$asal', tanggal = '$tanggal' where id = '$id'";
            $q1         = mysqli_query($conn, $sql1);
            if ($q1) {
                $sukses = "Data berhasil diupdate, Halaman akan direfresh dalam 5 detik!";
            } else {
                $error  = "Data gagal diupdate, Halaman akan direfresh dalam 5 detik!";
            }
        } else { //untuk insert
            $sql1   = "insert into db_visitor(nama, asal, tanggal) values ('$nama','$asal','$tanggal')";
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
    <title>Visitor Book</title>
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

    <div style="margin-left: 30%; margin-right: 30%; margin-bottom: 2%;">
        <div class="card">
            <div class="card-header text-center" style="color : black">
                Buat atau Edit Data Tamu
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh: 3; url=visitor-book.php");
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh: 3; url=visitor-book.php");
                }
                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label" style="color: black">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="asal" class="col-sm-2 col-form-label" style="color : black">Asal</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="asal" name="asal" value="<?php echo $asal ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="tanggal" class="col-sm-2 col-form-label" style="color : black">Tanggal</label>
                        <div class="col-sm-10">
                            <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>">
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-success" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div style="margin-left: 20%; margin-right: 20%;">
        <table class="table table-striped table-hover table-bordered">
            <thead>
                <tr class="text-center info fw-normal" style="vertical-align: middle; background-color: yellow;">
                    <th scope="col">No</th>
                    <th scope="col">Nama</th>
                    <th scope="col">Asal</th>
                    <th scope="col" style="width: 17%;">Tanggal <br> (YYYY/MM/DD)</th>
                    <th scope="col" style="width: 17%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql2   = "select * from db_visitor order by tanggal desc";
                $q2     = mysqli_query($conn, $sql2);
                $urut   = 1;
                while ($r2 = mysqli_fetch_array($q2)) {
                    $id         = $r2['id'];
                    $nama       = $r2['nama'];
                    $asal       = $r2['asal'];
                    $tanggal    = $r2['tanggal'];
                ?>

                    <tr class="info fw-normal" style="vertical-align: middle;">
                        <th class="text-center" scope="row"><?php echo $urut++ ?></th>
                        <td><?php echo $nama ?></td>
                        <td class="text-center"><?php echo $asal ?></td>
                        <td class="text-center"><?php echo $tanggal ?></td>
                        <td class="text-center">
                            <a href="visitor-book.php?op=edit&id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                            <a href="visitor-book.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin mau hapus data?')"><button type="button" class="btn btn-danger">Delete</button></a>
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