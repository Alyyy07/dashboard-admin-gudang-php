<?php
require 'function.php';
require 'cek.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Peminjaman Barang</title>
    <link href="css/styles.css" rel="stylesheet" />
    <style>
        .zoomable {
            width: 100px;
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
                    <h1 class="mt-4">Peminjaman Barang</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Data</button><button type="button" class="btn btn-info" data-bs-toggle="modal"><a class="text-decoration-none text-white" href="export/exportpeminjaman.php" target="_blank">Buat Laporan</a></button>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Gambar</th>
                                        <th>Nama Barang</th>
                                        <th>Quantity</th>
                                        <th>Penerima</th>
                                        <th>Admin</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $getalldata = mysqli_query($conn, "SELECT idpeminjaman,m.idbarang,tanggalpinjam,namabarang,peminjam,qty,image,status,l.nama from peminjaman m, stockbarang s,login l where m.idbarang = s.idbarang");
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($getalldata)) {
                                        [$idpinjam, $idbarang, $tanggal, $namabarang, $penerima, $qty, $gambar, $status, $namaadmin] = $data;
                                        if ($gambar == null) $img = 'No Photo';
                                        else $img = '<img src="img/' . $gambar . '" class="zoomable">';
                                    ?>
                                        <tr>
                                            <td><?= $i++ ?></td>
                                            <td><?= $tanggal ?></td>
                                            <td><?= $img ?></td>
                                            <td><?= $namabarang ?></td>
                                            <td><?= $qty ?></td>
                                            <td><?= $penerima ?></td>
                                            <td><?= $namaadmin ?></td>
                                            <td><?= $status ?></td>
                                            <td>
                                                <?php if ($status == 'Dipinjam') { ?>
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit<?= $idbarang ?>">Selesai</button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#edit<?= $idbarang ?>" disabled>Selesai</button>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit<?= $idbarang ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Update Peminjaman</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="idpinjam" value="<?= $idpinjam ?>">
                                                            <input type="hidden" name="idbarang" value="<?= $idbarang ?>">
                                                            <p>Apakah Anda yakin barang telah selesai dipinjam ?</p>
                                                            <button type="submit" class="btn btn-primary" name="updatepeminjaman">Ya</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    };
                                    ?>
                                </tbody>
                            </table>
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
    <div class="modal fade" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Tambah Data Peminjaman</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <form method="post">
                    <div class="modal-body">
                        <p class="fs-5 fw-semibold">Daftar Barang</p>
                        <select name="daftarbarang" class="form-control">
                            <?php
                            $databarang = mysqli_query($conn, "Select * from stockbarang");
                            while ($fetcharray = mysqli_fetch_array($databarang)) {
                                $namabarang = $fetcharray['namabarang'];
                                $idbarang = $fetcharray['idbarang'];
                            ?>
                                <option value="<?= $idbarang; ?>"><?= $namabarang; ?></option>
                            <?php
                            }
                            ?>
                        </select>
                        <br>
                        <p class="fs-5 fw-semibold">Jumlah</p>
                        <input type="number" name="qty" min="1" placeholder="Quantity" class="form-control" min="1" required>
                        <br>
                        <p class="fs-5 fw-semibold">Penerima</p>
                        <input type="text" name="penerima" placeholder="Penerima" class="form-control" required>
                        <br>
                        <button type="submit" class="btn btn-primary" name="pinjam">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>


</html>