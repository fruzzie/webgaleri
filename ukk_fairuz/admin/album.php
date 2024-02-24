<?php
    session_start();
    include '../config/koneksi.php';
    if ($_SESSION['status'] != 'login' ){
        echo "<script>alert('anda belum login');
        location.href='../index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <title>Website Galeri Foto</title>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="index.php">Website Galeri Foto</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse mt-2" id="navbarNavAltMarkup">
      <div class="navbar-nav me-auto">
        <a href="home.php" class="nav-link">Home</a>
        <a href="album.php" class="nav-link">Album</a>
        <a href="foto.php" class="nav-link">Foto</a>
      </div>
      
      <a href="../config/aksi_logout.php" class="btn btn-outline-danger m-1">Keluar</a>
    </div>
  </div>
</nav>


    <!-- content -->
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card mt-2">
                    <div class="card-header">Tambah Album</div>
                    <div class="card-body">
                        <form action="../config/aksi_album.php" method="POST">
                            <label for="" class="form-label">Nama Album</label>
                            <input type="text" name="namaalbum"  class="form-control" required>
                            <label for="" class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" required></textarea>
                            <button class="btn btn-primary mt-2" name="tambah" type="submit" >Tambah Data</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card mt-2">
                    <div class="card-header">Data Album</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Album</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $userid = $_SESSION['userid'];
                                    $sql = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                                    while ($data = mysqli_fetch_array($sql)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $data['namaalbum'] ?></td>
                                    <td><?php echo $data['deskripsi'] ?></td>
                                    <td><?php echo $data['tanggaldibuat'] ?></td>
                                    <td>
                                        <!-- Button edit-->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['albumid'] ?>">
                                        Edit
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="edit<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../config/aksi_album.php" method="post">
                                                    <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>" >
                                                    <label for="" class="form-label">Nama Album</label>
                                                    <input type="text" name="namaalbum" value="<?php echo $data['namaalbum'] ?>" class="form-control" required>
                                                    <label for="" class="form-label" >Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control"><?php echo $data['deskripsi']; ?></textarea>
                                                   
                                               
                                            </div>
                                            <div class="modal-footer">
                                            <button class="btn btn-primary mt-2" name="edit" type="submit" >Simpan Data</button>
                                            </form>
                                            </div>
                                            </div>
                                        </div>
                                        </div>

                                        <!-- Button hapus -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['albumid'] ?>">
                                        Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="hapus<?php echo $data['albumid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../config/aksi_album.php" method="post">
                                                    <input type="hidden" name="albumid" value="<?php echo $data['albumid'] ?>" >
                                                    Apakah anda yakin ingin menghapus data   <strong><?php echo $data['namaalbum'] ?></strong> ?    
                                                                                           
                                            </div>
                                            <div class="modal-footer">
                                            <button class="btn btn-danger mt-2" name="hapus" type="submit" >Hapus Data</button>
                                            </form>
                                            </div>
                                            </div>
                                        </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="d-flex justify-content-center border-top mt-3 bg-light fixed-bottom">
        <p>&copy; UKK RPL 2024 | Fairuz Anas Ferdiansyah</p>
    </footer>
   
    <script src="../assets/js/bootstrap.min.js"></script>
</body>
</html>