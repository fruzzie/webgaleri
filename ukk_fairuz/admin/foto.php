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
    <title>Document</title>
</head>
<body>
    <!-- navbar -->
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
                    <div class="card-header">Tambah Foto</div>
                    <div class="card-body">
                        <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data" >
                            <label for="" class="form-label">Judul Foto</label>
                            <input type="text" name="judulfoto" class="form-control" required>
                            <label for="" class="form-label">Deskripsi</label>
                            <textarea name="deskripsifoto" class="form-control" required></textarea>
                            <label for="" class="form-label">Album</label>
                            <select name="albumid" class="form-control">
                                <?php
                                    $userid = $_SESSION['userid'];
                                    $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                                    while($data_album = mysqli_fetch_array($sql_album)){ ?>
                                        <option value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                                    <?php }  
                                ?>
                            </select>
                            <label for="" class="form-label">File</label>
                            <input type="file" name="lokasifile" class="form-control" required>

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
                                    <th>Foto</th>
                                    <th>Judul Foto</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $batas = 5;
                                $halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
                                $halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;
                               
                                $previous = $halaman - 1;
                                $next = $halaman + 1;
                               
                                $data = mysqli_query($koneksi,"select * from foto");
                                $jumlah_data = mysqli_num_rows($data);
                                $total_halaman = ceil($jumlah_data / $batas);
                                    $no = $halaman_awal+1;
                                    $userid = $_SESSION['userid'];
                                    $sql = mysqli_query($koneksi, "SELECT * FROM foto WHERE userid='$userid' limit $halaman_awal, $batas");
                                    while ($data = mysqli_fetch_array($sql)) {
                                ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="100" ></td>
                                    <td><?php echo $data['judulfoto'] ?></td>
                                    <td><?php echo $data['deskripsifoto'] ?></td>
                                    <td><?php echo $data['tanggalunggah'] ?></td>
                                    <td>
                                        <!-- Button edit-->
                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#edit<?php echo $data['fotoid'] ?>">
                                        Edit
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="edit<?php echo $data['fotoid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Edit</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../config/aksi_foto.php" method="POST" enctype="multipart/form-data" >
                                                    <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>" >
                                                    <label for="" class="form-label">Judul Foto</label>
                                                    <input type="text" name="judulfoto" value="<?php echo $data['judulfoto'] ?>" class="form-control">
                                                    <label for="" class="form-label" >Deskripsi</label>
                                                    <textarea name="deskripsifoto" class="form-control"><?php echo $data['deskripsifoto']; ?></textarea>
                                                    <label for="" class="form-label">Album</label>
                                                    <select name="albumid" class="form-control">
                                                        <?php
                                                            $userid = $_SESSION['userid'];
                                                            $sql_album = mysqli_query($koneksi, "SELECT * FROM album WHERE userid='$userid'");
                                                            while($data_album = mysqli_fetch_array($sql_album)){ ?>
                                                                <option <?php if($data_album['albumid'] == $data['albumid'] ) {
                                                                    ?> selected= "selected" <?php
                                                                } ?> value="<?php echo $data_album['albumid'] ?>"><?php echo $data_album['namaalbum'] ?></option>
                                                            <?php }  
                                                        ?>
                                                    </select>
                                                    <label for="" class="form-label">Foto</label>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <img src="../assets/img/<?php echo $data['lokasifile'] ?>" width="100" >
                                                        </div>
                                                        <div class="col-md-8">
                                                            <label for="" class="form-label">Ganti File</label>
                                                            <input type="file" name="lokasifile" class="form-control">
                                                        </div>
                                                    </div>
                                                   
                                               
                                            </div>
                                            <div class="modal-footer">
                                            <button class="btn btn-primary mt-2" name="edit" type="submit" >Simpan Data</button>
                                            </form>
                                            </div>
                                            </div>
                                        </div>
                                        </div>

                                        <!-- Button hapus -->
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#hapus<?php echo $data['fotoid'] ?>">
                                        Hapus
                                        </button>

                                        <!-- Modal -->
                                        <div class="modal fade" id="hapus<?php echo $data['fotoid'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="../config/aksi_foto.php" method="post">
                                                    <input type="hidden" name="fotoid" value="<?php echo $data['fotoid'] ?>" >
                                                    Apakah anda yakin ingin menghapus data   <strong><?php echo $data['judulfoto'] ?></strong> ?    
                                                                                           
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
                        <nav>
                            <ul class="pagination ">
                                <?php
                                    for($x=1;$x<=$total_halaman;$x++){
                                ?>
                                    <li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
                                <?php
                                    }
                                ?>
                                    <li class="page-item">
                                    <a  class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a>
                            </li>
                            </ul>
                        </nav>
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