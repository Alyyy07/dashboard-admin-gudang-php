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
    <title>Barang Keluar</title>
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
                    <h1 class="mt-4">Barang Keluar</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#myModal">Tambah Barang</button><button type="button" class="btn btn-info" data-bs-toggle="modal"><a class="text-decoration-none text-white" href="export/exportkeluar.php" target="_blank">Buat Laporan</a></button>
                            <br>
                            <form method="post" class="mt-4">
                                <div class="row w-75">
                                    <input type="date" name="tgl_mulai" class="form-control tanggal col" style="max-width: 300px;">
                                    <input type="date" name="tgl_selesai" style="max-width: 300px;" class="form-control col ms-2 tanggal">
                                    <button type="submit" name="filter_tgl" class="btn btn-info col ms-2" style="max-width: 100px;">Filter</button>
                                </div>
                            </form>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($_POST['filter_tgl'])) {
                                        $mulai = $_POST['tgl_mulai'];
                                        $selesai = $_POST['tgl_selesai'];

                                        if ($mulai != null || $selesai != null) {
                                            $getalldata = mysqli_query($conn, "SELECT idkeluar,m.idbarang,tanggal,namabarang,penerima,qty,image,nama from barangkeluar m, stockbarang s,login l where m.idbarang = s.idbarang and m.idadmin = l.iduser and tanggal between '$mulai' and DATE_ADD('$selesai',INTERVAL 1 Day) order by idkeluar desc");
                                        } else {
                                            $getalldata = mysqli_query($conn, "SELECT idkeluar,m.idbarang,tanggal,namabarang,penerima,qty,image,nama from barangkeluar m, stockbarang s,login l where m.idbarang = s.idbarang and m.idadmin = l.iduser order by idkeluar desc");
                                        }
                                    } else {
                                        $getalldata = mysqli_query($conn, "SELECT idkeluar,m.idbarang,tanggal,namabarang,penerima,qty,image,nama from barangkeluar m, stockbarang s,login l where m.idbarang = s.idbarang and m.idadmin = l.iduser order by idkeluar desc");
                                    }
                                    $i = 1;
                                    while ($data = mysqli_fetch_array($getalldata)) {
                                        [$idkeluar, $idbarang, $tanggal, $namabarang, $penerima, $qty, $gambar, $admin] = $data;
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
                                            <td><?= $admin ?></td>
                                            <td><button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#edit<?= $idbarang ?>">Edit</button>
                                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete<?= $idbarang ?>">Delete</button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="edit<?= $idbarang ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Edit Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            <p class="fs-5 fw-semibold">Jumlah</p>
                                                            <input type="number" name="qty" min="1" value="<?= $qty ?>" class="form-control" required>
                                                            <br>
                                                            <p class="fs-5 fw-semibold">Penerima</p>
                                                            <input type="text" name="penerima" value="<?= $penerima ?>" class="form-control" required>
                                                            <br>
                                                            <input type="hidden" name="idbarang" value="<?= $idbarang ?>">
                                                            <input type="hidden" name="idkeluar" value="<?= $idkeluar ?>">
                                                            <button type="submit" class="btn btn-primary" name="updatebarangkeluar">Submit</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal fade" id="delete<?= $idbarang ?>">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Hapus Barang</h4>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <form method="post">
                                                        <div class="modal-body">
                                                            Apakah anda yakin ingin menghapus <?= $namabarang ?> ?
                                                            <br>
                                                            <br>
                                                            <input type="hidden" name="idbarang" value="<?= $idbarang ?>">
                                                            <input type="hidden" name="idkeluar" value="<?= $idkeluar ?>">
                                                            <input type="hidden" name="qty" value="<?= $qty ?>">
                                                            <button type="submit" class="btn btn-danger" name="hapusbarangkeluar">Hapus</button>
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
</body>

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Tambah Barang Keluar</h4>
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
                    <input type="number" name="qty" min="1" placeholder="Quantity" class="form-control" required>
                    <br>
                    <p class="fs-5 fw-semibold">Penerima</p>
                    <input type="text" name="penerima" placeholder="Penerima" class="form-control" required>
                    <br>
                    <button type="submit" class="btn btn-primary" name="barangkeluar">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

</html>