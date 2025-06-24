<?php
$id = intval($_GET['id']);
$sql2 = $koneksi->prepare("SELECT * FROM users WHERE id = ?");
$sql2->bind_param('i', $id);
$sql2->execute();
$result = $sql2->get_result();
$tampil = $result->fetch_assoc();
?>

<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ubah User</h6>
        </div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>NIK</label>
                    <input type="number" name="nik" value="<?= htmlspecialchars($tampil['nik']) ?>" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($tampil['nama']) ?>" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Telepon</label>
                    <input type="number" name="telepon" value="<?= htmlspecialchars($tampil['telepon']) ?>" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($tampil['username']) ?>" class="form-control" required />
                </div>

                <div class="form-group">
                    <label>Password (Isi jika ingin mengganti)</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin mengganti" />
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control" required>
                        <option value="">-- Pilih Level --</option>
                        <option value="superadmin" <?= ($tampil['level'] == 'superadmin') ? 'selected' : '' ?>>Admin</option>
                        <option value="petugas" <?= ($tampil['level'] == 'petugas') ? 'selected' : '' ?>>Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Saat Ini</label><br>
                    <img src="img/<?= htmlspecialchars($tampil['foto']) ?>" width="50" height="50" alt="Foto User">
                </div>

                <div class="form-group">
                    <label>Ganti Foto</label>
                    <input type="file" name="foto" class="form-control" />
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
            </form>

            <?php
            if (isset($_POST['simpan'])) {
                $nik = $_POST['nik'];
                $nama = $_POST['nama'];
                $telepon = $_POST['telepon'];
                $username = $_POST['username'];
                $level = $_POST['level'];
                $password = $_POST['password'];

                $foto = $_FILES['foto']['name'];
                $lokasi = $_FILES['foto']['tmp_name'];

                if (!empty($foto)) {
                    move_uploaded_file($lokasi, "img/" . basename($foto));
                    $query = $koneksi->prepare("UPDATE users SET nik=?, nama=?, telepon=?, username=?, level=?, foto=? WHERE id=?");
                    $query->bind_param('ssssssi', $nik, $nama, $telepon, $username, $level, $foto, $id);
                } else {
                    $query = $koneksi->prepare("UPDATE users SET nik=?, nama=?, telepon=?, username=?, level=? WHERE id=?");
                    $query->bind_param('sssssi', $nik, $nama, $telepon, $username, $level, $id);
                }

                if (!empty($password)) {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $query_pass = $koneksi->prepare("UPDATE users SET password=? WHERE id=?");
                    $query_pass->bind_param('si', $hashed_password, $id);
                    $query_pass->execute();
                }

                if ($query->execute()) {
                    echo '<script>alert("Data Berhasil Diubah"); window.location.href="?page=pengguna";</script>';
                } else {
                    echo '<script>alert("Gagal mengubah data");</script>';
                }
            }
            ?>
        </div>
    </div>
</div>
