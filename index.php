<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "db_akademik";

$connection = mysqli_connect($host, $user, $pass, $db);
if (!$connection) {
    die("Koneksi ke database GAGAL");
}

$nim = "";
$nama = "";
$alamat = "";
$fakultas = "";

$success = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $queryDelete = "DELETE FROM mahasiswa WHERE id='$id'";
    $executeQDelete = mysqli_query($connection, $queryDelete);
    if ($executeQDelete) {
        $success = "Berhasil menghapus data";
    } else {
        $error = "Gagal menghapus data";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $queryGet = "SELECT * FROM mahasiswa WHERE id='$id'";
    $executeQGet = mysqli_query($connection, $queryGet);
    $data = mysqli_fetch_array($executeQGet);

    if (!$data) {
        $error = "Data tidak ditemukan";
    } else {
        $nim = $data['nim'];
        $nama = $data['nama'];
        $alamat = $data['alamat'];
        $fakultas = $data['fakultas'];
    }
}

// Pada saat tombol 'Simpan' di-klik
if (isset($_POST['simpan'])) {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $fakultas = $_POST['fakultas'];

    if ($nim && $nama && $alamat && $fakultas) {
        if ($op == 'edit') { // UPDATE DATA
            $queryUpdate = "UPDATE mahasiswa SET nim='$nim', nama='$nama', alamat='$alamat', fakultas='$fakultas' WHERE id='$id'";
            $executeQUpdate = mysqli_query($connection, $queryUpdate);
            if ($executeQUpdate) {
                $success = "Data berhasil diperbarui";
            } else {
                $error = "Data gagal diperbarui";
            }
        } else { // CREATE DATA
            $queryInsert = "INSERT INTO mahasiswa(nim, nama, alamat, fakultas) VALUES ('$nim', '$nama', '$alamat', '$fakultas')";
            try {
                $executeQInsert = mysqli_query($connection, $queryInsert);
                if ($executeQInsert) {
                    $success = "Berhasil menambahkan data";
                }
            } catch (mysqli_sql_exception $err) {
                $error = "Gagal menambahkan data";
            }
        }
    } else {
        $error = "Semua data harus diisi!";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px;
        }

        .card {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- Input Data START -->
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                } ?>
                <?php if ($success) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success ?>
                    </div>
                <?php
                } ?>
                <form action="" method="post">
                    <div class="mb-3 row">
                        <label for="nim" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="alamat" name="alamat" value="<?php echo $alamat ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="fakultas" class="col-sm-2 col-form-label">Fakultas</label>
                        <div class="col-sm-10">
                            <select class="form-control" id="fakultas" name="fakultas">
                                <option value="">- Pilih Fakultas -</option>
                                <option value="Ilmu Sosial" <?php if ($fakultas == 'Ilmu Sosial') echo "selected" ?>>Ilmu Sosial</option>
                                <option value="Teknik" <?php if ($fakultas == 'Teknik') echo "selected" ?>>Teknik</option>
                                <option value="MIPA" <?php if ($fakultas == 'MIPA') echo "selected" ?>>MIPA</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Input Data END -->

        <!-- Show data START -->
        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">NIM</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Alamat</th>
                            <th scope="col">Fakultas</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- READ DATA -->
                        <?php
                        $queryGet = "SELECT * FROM mahasiswa ORDER BY id ASC";
                        $executeQGet = mysqli_query($connection, $queryGet);
                        $number = 1;
                        while ($data = mysqli_fetch_array($executeQGet)) {
                            $id = $data['id'];
                            $nim = $data['nim'];
                            $nama = $data['nama'];
                            $alamat = $data['alamat'];
                            $fakultas = $data['fakultas'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $number++ ?></th>
                                <td scope="row"><?php echo $nim ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $alamat ?></td>
                                <td scope="row"><?php echo $fakultas ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&id=<?php echo $id ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <a href="index.php?op=delete&id=<?php echo $id ?>" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <button type="button" class="btn btn-danger">Hapus</button>
                                    </a>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Show data END -->
    </div>
</body>

</html>