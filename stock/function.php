<?php
session_start();

//membuat koneksi ke database

$conn = mysqli_connect("localhost", "root", "", "stockbarang");


//tambah barang baru

if (isset($_POST['tambah'])) {

    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];
    $stock = $_POST['stock'];

    $query = mysqli_query($conn, "INSERT INTO stock (namabarang, deskripsi, stock) VALUES ('$namabarang','$deskripsi','$stock')");
    if ($query) {
        header("location:index.php");
    } else {
        echo "
        <script>
        alert('Gagal menambah data');
        window.location='index.php';
        </script>
        ";
    }
}


//menambah barang masuk

if (isset($_POST['barangmasuk'])) {
    $barang = $_POST['barang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahstocksekarangdenganqty = $stocksekarang + $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO masuk (idbarang, keterangan, qty) VALUES ('$barang','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock ='$tambahstocksekarangdenganqty' WHERE idbarang='$barang'");
    if ($addtomasuk && $updatestockmasuk) {
        header("location:masuk.php");
    } else {
        echo "
        <script>
        alert('Gagal menambah barang masuk');
        window.location='masuk.php';
        </script>
        ";
    }
}

//menambah barang keluar

if (isset($_POST['barangkeluar'])) {
    $barang = $_POST['barang'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $cekstocksekarang = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$barang'");
    $ambildatanya = mysqli_fetch_array($cekstocksekarang);

    $stocksekarang = $ambildatanya['stock'];
    $tambahstocksekarangdenganqty = $stocksekarang - $qty;

    $addtomasuk = mysqli_query($conn, "INSERT INTO keluar (idbarang, penerima, qty) VALUES ('$barang','$penerima','$qty')");
    $updatestockmasuk = mysqli_query($conn, "UPDATE stock SET stock ='$tambahstocksekarangdenganqty' WHERE idbarang='$barang'");
    if ($addtomasuk && $updatestockmasuk) {
        header("location:keluar.php");
    } else {
        echo "
        <script>
        alert('Gagal menambah barang masuk');
        window.location='keluar.php';
        </script>
        ";
    }
}


//update info barang

if (isset($_POST['updatebarang'])) {
    $idb = $_POST['idb'];
    $namabarang = $_POST['namabarang'];
    $deskripsi = $_POST['deskripsi'];

    $update = mysqli_query($conn, "UPDATE stock SET namabarang='$namabarang', deskripsi='$deskripsi' WHERE idbarang='$idb'");
    if ($update) {
        header("location:index.php");
    } else {
        echo "
        <script>
        alert('Gagal menambah barang masuk');
        window.location='index.php';
        </script>
        ";
    }
}


if (isset($_POST['hapusbarang'])) {
    $idb = $_POST['idb'];

    $hapus = mysqli_query($conn, "DELETE FROM stock WHERE idbarang='$idb'");
    if ($hapus) {
        header("location:index.php");
    } else {
        echo "
        <script>
        alert('Gagal menambah barang masuk');
        window.location='index.php';
        </script>
        ";
    }
}


//ubah data barang masuk

if (isset($_POST['updatebarangmasuk'])) {
    $idb = $_POST['idb'];
    $idm = $_POST['idm'];
    $keterangan = $_POST['keterangan'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $lihatqty = mysqli_query($conn, "SELECT * FROM masuk WHERE idmasuk='$idm' ");
    $quantitinya = mysqli_fetch_array($lihatqty);
    $qtysekarang = $quantitinya['qty'];

    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurang = $stocksekarang - $selisih;
        $kurangstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurang' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty ='$qty', keterangan='$keterangan'  WHERE idmasuk='$idm'");
        if ($kurangstocknya && $updatenya) {
            header("location:masuk.php");
        } else {
            echo "
            <script>
            alert('Gagal menambah barang masuk');
            window.location='masuk.php';
            </script>
            ";
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurang = $stocksekarang + $selisih;
        $kurangstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurang' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE masuk SET qty ='$qty', keterangan='$keterangan'  WHERE idmasuk='$idm'");
        if ($kurangstocknya && $updatenya) {
            header("location:masuk.php");
        } else {
            echo "
            <script>
            alert('Gagal menambah barang masuk');
            window.location='masuk.php';
            </script>
            ";
        }
    }
}

//Menghapus barang masuk

if (isset($_POST['hapusbarangmasuk'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idm = $_POST['idm'];
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock - $qty;
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM masuk WHERE idmasuk='$idm'");

    if ($update && $hapusdata) {
        header("location:masuk.php");
    } else {
        echo "
        <script>
        alert('Gagal menghapus barang masuk');
        window.location='masuk.php';
        </script>
        ";
    }
}

//ubah data barang keluar

if (isset($_POST['updatebarangkeluar'])) {
    $idb = $_POST['idb'];
    $idk = $_POST['idk'];
    $penerima = $_POST['penerima'];
    $qty = $_POST['qty'];

    $lihatstock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $stocknya = mysqli_fetch_array($lihatstock);
    $stocksekarang = $stocknya['stock'];

    $lihatqty = mysqli_query($conn, "SELECT * FROM keluar WHERE idkeluar='$idk' ");
    $quantitinya = mysqli_fetch_array($lihatqty);
    $qtysekarang = $quantitinya['qty'];

    if ($qty > $qtysekarang) {
        $selisih = $qty - $qtysekarang;
        $kurang = $stocksekarang - $selisih;
        $kurangstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurang' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty ='$qty', penerima='$penerima'  WHERE idkeluar='$idk'");
        if ($kurangstocknya && $updatenya) {
            header("location:keluar.php");
        } else {
            echo "
            <script>
            alert('Gagal menambah barang masuk');
            window.location='keluar.php';
            </script>
            ";
        }
    } else {
        $selisih = $qtysekarang - $qty;
        $kurang = $stocksekarang + $selisih;
        $kurangstocknya = mysqli_query($conn, "UPDATE stock SET stock='$kurang' WHERE idbarang='$idb'");
        $updatenya = mysqli_query($conn, "UPDATE keluar SET qty ='$qty', penerima='$penerima'  WHERE idkeluar='$idk'");
        if ($kurangstocknya && $updatenya) {
            header("location:keluar.php");
        } else {
            echo "
            <script>
            alert('Gagal menambah barang masuk');
            window.location='keluar.php';
            </script>
            ";
        }
    }
}

//Menghapus barang keluar

if (isset($_POST['hapusbarangkeluar'])) {
    $idb = $_POST['idb'];
    $qty = $_POST['qty'];
    $idk = $_POST['idk'];
    $getdatastock = mysqli_query($conn, "SELECT * FROM stock WHERE idbarang='$idb'");
    $data = mysqli_fetch_array($getdatastock);
    $stock = $data['stock'];

    $selisih = $stock + $qty;
    $update = mysqli_query($conn, "UPDATE stock SET stock='$selisih' WHERE idbarang='$idb'");
    $hapusdata = mysqli_query($conn, "DELETE FROM keluar WHERE idkeluar='$idk'");

    if ($update && $hapusdata) {
        header("location:keluar.php");
    } else {
        echo "
        <script>
        alert('Gagal menghapus barang masuk');
        window.location='keluar.php';
        </script>
        ";
    }
}
