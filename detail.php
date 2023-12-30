<?php
require 'function.php';
require 'cek.php';

$idbarang = $_GET['id'];
$detailbarang = mysqli_fetch_array(mysqli_query($conn, "Select * from stockbarang where idbarang='$idbarang'"));
[, $namabarang, $deskripsi, $stock, $gambar] = $detailbarang;
if ($gambar == null) $img = '';
else $img = '<img src="img/' . $gambar . '" class="zoomable">';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Stock - Detail Barang</title>
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .zoomable {
            height: 200px;
        }

        .zoomable:hover {
            transform: scale(2);
            transition: 0.3s ease;
        }
    </style>
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-lg me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Brand-->
        <a class="navbar-brand fs-3" href="index.php">Citrakara Warehouse</a>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Stock Barang
                        </a>
                        <a class="nav-link" href="masuk.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Masuk
                        </a>
                        <a class="nav-link" href="keluar.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Barang Keluar
                        </a>
                        <a class="nav-link" href="peminjaman.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Peminjaman Barang
                        </a>
                        <a class="nav-link" href="admin.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Kelola Admin
                        </a>
                        <a class="nav-link" href="logout.php">
                            <div class="sb-nav-link-icon"><i></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Detail Barang</h1>
                    <div class="card mb-4 mt-4">
                        <div class="card-header">
                            <h2 class=""><?= $namabarang ?></h2>
                            <?= $img ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col md-3">
                                    <h4>Deskripsi</h4>
                                </div>
                                <div class="col md-9">
                                    <h4>: <?= $deskripsi ?></h4>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col md-3">
                                    <h4>Stock</h4>
                                </div>
                                <div class="col md-9">
                                    <h4>: <?= $stock ?><h4>
                                </div>
                            </div>
                            <br><br>
                            <hr>
                            <h3>Laporan Barang Masuk</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="barangmasuk" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Keterangan</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $datamasuk = mysqli_query($conn, "select * from barangmasuk where idbarang='$idbarang'");
                                        $i = 1;
                                        while ($fetch = mysqli_fetch_array($datamasuk)) {
                                            $tanggal = $fetch['tanggal'];
                                            $keterangan = $fetch['keterangan'];
                                            $qty = $fetch['qty'];
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $keterangan ?></td>
                                                <td><?= $qty ?></td>
                                                </td>
                                            </tr>
                                        <?php
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <br><br>
                            <hr>
                            <h3>Laporan Barang Keluar</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="barangkeluar" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Penerima</th>
                                            <th>Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <?php
                                        $datakeluar = mysqli_query($conn, "select * from barangkeluar where idbarang='$idbarang'");
                                        $i = 1;
                                        while ($fetch = mysqli_fetch_array($datakeluar)) {
                                            $tanggal = $fetch['tanggal'];
                                            $penerima = $fetch['penerima'];
                                            $qty = $fetch['qty'];
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $penerima ?></td>
                                                <td><?= $qty ?></td>
                                                </td>
                                            </tr>
                                        <?php
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <br><br>
                            <hr>
                            <h3>Laporan Peminjaman Barang</h3>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="barangkeluar" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Nama Barang</th>
                                            <th>Quantity</th>
                                            <th>Peminjam</th>
                                            <th>Admin</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $getalldata = mysqli_query($conn, "SELECT idpeminjaman,m.idbarang,tanggalpinjam,namabarang,peminjam,qty,status,l.nama from peminjaman m, stockbarang s,login l where m.idbarang = s.idbarang and m.idadmin = l.iduser and m.idbarang='$idbarang'");
                                        $i = 1;
                                        while ($data = mysqli_fetch_array($getalldata)) {
                                            [$idpinjam, $idbarang, $tanggal, $namabarang, $penerima, $qty, $status, $namaadmin] = $data;
                                            if ($gambar == null) $img = 'No Photo';
                                            else $img = '<img src="img/' . $gambar . '" class="zoomable">';
                                        ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $tanggal ?></td>
                                                <td><?= $namabarang ?></td>
                                                <td><?= $qty ?></td>
                                                <td><?= $penerima ?></td>
                                                <td><?= $namaadmin ?></td>
                                                <td><?= $status ?></td>
                                            </tr>
                                        <?php
                                        };
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2023</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="js/boostrap.bundle.min.js" crossorigin="anonymous">
    </script>
    <script src="js/scripts.js"></script>
    <script src="js/Chart.min.js" crossorigin="anonymous"></script>
    <script src="assets/demo/chart-area-demo.js"></script>
    <script src="assets/demo/chart-bar-demo.js"></script>
    <script src="js/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
</body>
<!-- The Modal -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="fs-5 fw-semibold">Nama Barang</p>
                    <input type="text" name="namabarang" placeholder="Nama Barang" class="form-control" required>
                    <br>
                    <p class="fs-5 fw-semibold">Deskripsi</p>
                    <input type="text" name="deskripsi" placeholder="Deskripsi Barang" class="form-control" required>
                    <br>
                    <p class="fs-5 fw-semibold">Jumlah</p>
                    <input type="number" name="stock" placeholder="stock" class="form-control" required>
                    <br>
                    <input type="file" name="file" class="form-control">
                    <br>
                    <button type="submit" class="btn btn-primary" name="addnewbarang">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>