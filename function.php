<?php
session_start();

$conn = mysqli_connect('localhost', 'root', '', 'gudang');

//tambah barang
if (isset($_POST['addnewbarang'])) {
    [$namabarang, $deskripsi, $stock] = [$_POST['namabarang'], $_POST['deskripsi'], $_POST['stock']];

    $tipefile = array('png', 'jpg', 'jpeg');
    $nama = $_FILES['file']['name'];
    $dot = explode('.', $nama);
    $extensi = strtolower(end($dot));
    $ukuran = $_FILES['file']['size'];
    $file_location = $_FILES['file']['tmp_name'];
    $image = md5(uniqid($nama, true) . time()) . '.' . $extensi;

    $cek = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where namabarang='$namabarang'"));
    if ($cek < 1) {
        if (in_array($extensi, $tipefile) === true) {
            if ($ukuran < 2000000) {
                move_uploaded_file($file_location, 'img/' . $image);
                $addtotable = mysqli_query($conn, "INSERT INTO stockbarang (namabarang,deskripsi,stock,image) values('$namabarang','$deskripsi','$stock','$image')");
                if ($addtotable) header('location:index.php');
            } else {
                echo '
                <script>
                alert("Ukuran file terlalu besar");
                window.location.href="index.php";
                </script>';
            }
        } else {
            echo '
            <script>
            alert("File harus berupa .png,jpeg atau jpg");
            window.location.href="index.php";
            </script>';
        }
    } else {
        echo '
        <script>
        alert("Nama barang sudah terdaftar");
        window.location.href="index.php";
        </script>';
    }
}

//tambah barang masuk
if (isset($_POST['barangmasuk'])) {
    [$idbarang, $penerima, $qty, $admin] = [$_POST['daftarbarang'], $_POST['penerima'], $_POST['qty'], $_SESSION['admin']];
    $getbarang = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));
    $updatestockbarang = $getbarang['stock'] + $qty;

    $addtomasuk = mysqli_query($conn, "insert into barangmasuk (idbarang,keterangan,qty,idadmin) values('$idbarang','$penerima','$qty','$admin')");
    $updatedata = mysqli_query($conn, "update stockbarang set stock='$updatestockbarang' where idbarang='$idbarang'");
    if ($addtomasuk && $updatedata) header('location:masuk.php');
}

//tambah barang keluar
if (isset($_POST['barangkeluar'])) {
    [$idbarang, $penerima, $qty, $admin] = [$_POST['daftarbarang'], $_POST['penerima'], $_POST['qty'], $_SESSION['admin']];
    $getbarang = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));

    if ($getbarang['stock'] >= $qty) {
        $updatestockbarang = $getbarang['stock'] - $qty;

        $addtokeluar = mysqli_query($conn, "insert into barangkeluar (idbarang,penerima,qty,idadmin) values('$idbarang','$penerima','$qty','$admin')");
        $updatedata = mysqli_query($conn, "update stockbarang set stock='$updatestockbarang' where idbarang='$idbarang'");
        if ($addtokeluar && $updatedata) header('location:keluar.php');
    } else {
        echo '
        <script>
        alert("Stock saat ini tidak mencukupi");
        window.location.href="keluar.php";
        </script>';
    }
}

//update stock barang
if (isset($_POST['updatebarang'])) {
    [$idbarang, $namabarang, $deskripsi] = [$_POST['idbarang'], $_POST['namabarang'], $_POST['deskripsi']];

    $tipefile = array('png', 'jpg', 'jpeg');
    $nama = $_FILES['file']['name'];
    $dot = explode('.', $nama);
    $extensi = strtolower(end($dot));
    $ukuran = $_FILES['file']['size'];
    $file_location = $_FILES['file']['tmp_name']; 
    $image = md5(uniqid($nama, true) . time()) . '.' . $extensi;

    if ($ukuran == 0) {
        $updatedata = mysqli_query($conn, "update stockbarang set namabarang='$namabarang', deskripsi='$deskripsi' where idbarang='$idbarang'");
        if ($updatedata) header('location:index.php');
    } else {
        if (in_array($extensi, $tipefile) === true) {
            if ($ukuran < 2000000) {
                $imglama = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));
                unlink('img/' . $imglama['image']);
                move_uploaded_file($file_location, 'img/' . $image);
            } else {
                echo '
                <script>
                alert("Ukuran file terlalu besar");
                window.location.href="index.php";
                </script>';
            }
        } else {
            echo '<script>
            alert("File harus berupa .png,jpeg atau jpg");
            window.location.href="index.php";
            </script>';
        }
        $updatedata = mysqli_query($conn, "update stockbarang set namabarang='$namabarang', deskripsi='$deskripsi', image='$image' where idbarang='$idbarang'");
        if ($updatedata) header('location:index.php');
    }
}

//hapus stock barang
if (isset($_POST['hapusbarang'])) {
    $idbarang = $_POST['idbarang'];
    $gambar = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));
    $imglocation = 'img/' . $gambar['image'];
    $deletedata = mysqli_query($conn, "delete from stockbarang where idbarang='$idbarang'");
    if ($deletedata) {
        unlink($imglocation);
        header('location:index.php');
    }
}

//update barang masuk
if (isset($_POST['updatebarangmasuk'])) {
    [$idmasuk, $idbarang, $keterangan, $qty, $admin] = [$_POST['idmasuk'], $_POST['idbarang'], $_POST['keterangan'], $_POST['qty'], $_SESSION['admin']];

    $stockbarang = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));
    $qtymasuk = mysqli_fetch_array(mysqli_query($conn, "select * from barangmasuk where idmasuk='$idmasuk'"));

    if ($qty > $qtymasuk['qty']) {
        $updatedstock = $stockbarang['stock'] + ($qty - $qtymasuk['qty']);
        $updatedatabarang = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
        $updatedatabarangmasuk = mysqli_query($conn, "update barangmasuk set qty='$qty', keterangan='$keterangan',idadmin='$admin' where idmasuk='$idmasuk'");
        if ($updatedatabarang && $updatedatabarangmasuk) header('location:masuk.php');
    } else {
        $updatedstock = $stockbarang['stock'] - ($qtymasuk['qty'] - $qty);
        $updatedatabarang = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
        $updatedatabarangmasuk = mysqli_query($conn, "update barangmasuk set qty='$qty', keterangan='$keterangan',idadmin='$admin' where idmasuk='$idmasuk'");
        if ($updatedatabarang && $updatedatabarangmasuk) header('location:masuk.php');
    }
}

//delete barang masuk
if (isset($_POST['hapusbarangmasuk'])) {
    [$idbarang, $idmasuk, $qty] = [$_POST['idbarang'], $_POST['idmasuk'], $_POST['qty']];

    $getstockbarang = mysqli_fetch_array(mysqli_query($conn, "Select * from stockbarang where idbarang='$idbarang'"));
    $updatedstock = $getstockbarang['stock'] - $qty;
    $updatedatastock = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
    $hapusdatamasuk = mysqli_query($conn, "delete from barangmasuk where idmasuk='$idmasuk'");
}

//update barang keluar
if (isset($_POST['updatebarangkeluar'])) {
    [$idkeluar, $idbarang, $penerima, $qty, $admin] = [$_POST['idkeluar'], $_POST['idbarang'], $_POST['penerima'], $_POST['qty'], $_SESSION['admin']];

    $stockbarang = mysqli_fetch_array(mysqli_query($conn, "select * from stockbarang where idbarang='$idbarang'"));
    $qtykeluar = mysqli_fetch_array(mysqli_query($conn, "select * from barangkeluar where idkeluar='$idkeluar'"));

    if ($qty > $qtykeluar['qty']) {
        $updatedstock = $stockbarang['stock'] - ($qty - $qtykeluar['qty']);
        if ($updatedstock < 0) {
            echo '
                <script>
                alert("Stock saat ini tidak mencukupi !!!");
                window.location.href="keluar.php";
                </script>';
        } else {
            $updatedatabarang = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
            $updatedatabarangkeluar = mysqli_query($conn, "update barangkeluar set qty='$qty', penerima='$penerima',idadmin='$admin' where idkeluar='$idkeluar'");
            if ($updatedatabarang && $updatedatabarangkeluar) header('location:keluar.php');
        }
    } else {
        $updatedstock = $stockbarang['stock'] + ($qtykeluar['qty'] - $qty);
        $updatedatabarang = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
        $updatedatabarangkeluar = mysqli_query($conn, "update barangkeluar set qty='$qty', penerima='$penerima',idadmin='$admin' where idkeluar='$idkeluar'");
        if ($updatedatabarang && $updatedatabarangkeluar) header('location:keluar.php');
    }
}

//delete barang keluar
if (isset($_POST['hapusbarangkeluar'])) {
    [$idbarang, $idkeluar, $qty] = [$_POST['idbarang'], $_POST['idkeluar'], $_POST['qty']];

    $getstockbarang = mysqli_fetch_array(mysqli_query($conn, "Select * from stockbarang where idbarang='$idbarang'"));
    $updatedstock = $getstockbarang['stock'] + $qty;
    $updatedatastock = mysqli_query($conn, "update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
    $hapusdatamasuk = mysqli_query($conn, "delete from barangkeluar where idkeluar='$idkeluar'");
}

//tambah admin
if (isset($_POST['addnewadmin'])) {
    [$email, $password, $nama] = [$_POST['email'], $_POST['password'], $_POST['nama']];

    $adddata = mysqli_query($conn, "insert into login (email,password,nama) values('$email','$password','$nama')");
    if ($adddata) header('location:admin.php');
}

//update admin
if (isset($_POST['updateadmin'])) {
    [$iduser, $email, $password, $nama] = [$_POST['iduser'], $_POST['email'], $_POST['password'], $_POST['nama']];

    $updatedata = mysqli_query($conn, "update login set email='$email', password='$password',nama='$nama' where iduser='$iduser'");
    if ($updatedata) header('location:admin.php');
}

if (isset($_POST['hapusadmin'])) {
    $iduser = $_POST['iduser'];
    $deletedata = mysqli_query($conn, "delete from login where iduser='$iduser'");
    if ($deletedata) header('location:admin.php');
}


if (isset($_POST['pinjam'])) {
    [$idbarang, $qty, $penerima, $idadmin] = [$_POST['daftarbarang'], $_POST['qty'], $_POST['penerima'], $_SESSION['admin']];

    $stocksekarang = mysqli_fetch_array(mysqli_query($conn, "Select * from stockbarang where idbarang='$idbarang'"))['stock'];

    if ($qty <= $stocksekarang) {
        $insertdata = mysqli_query($conn, "insert into peminjaman (idbarang,peminjam,qty,idadmin)
        values('$idbarang','$penerima','$qty','$idadmin')");
        if ($insertdata && $stocksekarang) {
            $updatedstock = $stocksekarang - $qty;
            mysqli_query($conn, "Update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
        } else {
            echo '<script>
            alert("Gagal");
            window.location.href="peminjaman.php";
            </script>';
        }
    } else {
        echo '<script>
        alert("Stock saat ini tidak mencukupi");
        window.location.href="peminjaman.php";
        </script>';
    }
}


if (isset($_POST['updatepeminjaman'])) {
    [$idpinjam, $idbarang] = [$_POST['idpinjam'], $_POST['idbarang']];
    $stockbarang = mysqli_fetch_array(mysqli_query($conn, "Select * from stockbarang where idbarang ='$idbarang'"))['stock'];
    $stockpinjaman = mysqli_fetch_array(mysqli_query($conn, "Select * from peminjaman where idpeminjaman ='$idpinjam'"))['qty'];
    $updatedstock = $stockbarang + $stockpinjaman;
    mysqli_query($conn, "Update stockbarang set stock='$updatedstock' where idbarang='$idbarang'");
    mysqli_query($conn, "Update peminjaman set status='Selesai' where idpeminjaman='$idpinjam'");
    header('location:peminjaman.php');
}
