<?php 

$koneksi = mysqli_connect('localhost','root','','db_longor');

function query($query)
{
    global $koneksi;
    $hasil = mysqli_query($koneksi, $query); //nilai objek
    $kotakbesar = [];
    while ($kotakkacil = mysqli_fetch_assoc($hasil)){ //array assosiatif
        $kotakbesar [] = $kotakkacil;
    }
    return $kotakbesar;
}

function tambah ($post) {
    global $koneksi;

    $nama = $post["nama"];
    $nisn = $post["nisn"];
    $jurusan = $post["jurusan"];
    $email = $post["email"];
   
    $gambar = upload();
    if (!$gambar){
        return false;
    }
    
    $sql = "INSERT INTO tbl_orang VALUES (
        '','$nama','$nisn','$jurusan','$email','$gambar'
    )";
    
    mysqli_query ($koneksi, $sql);

    return mysqli_affected_rows($koneksi);

    }
    
    function upload(){
        $namafile = $_FILES ["gambar"]["name"];
        $ukuranfile = $_FILES ["gambar"]["size"];
        $error = $_FILES ["gambar"]["error"];
        $tmpname = $_FILES ["gambar"]["tmp_name"];
        
        if ($error === 4){
            echo"
            <script>
                alert ('pilih gambar dahulu');
            </script>";
            return false;
        }
        $ekstensiValid = ['jpg','jpeg','png'];
        $ekstensigambar = explode('.', $namafile);
        $ekstensigambar = strtolower(end($ekstensigambar));

        if ( !in_array($ekstensigambar, $ekstensiValid)){
            echo"
            <script>
                alert ('Maaf, file yang di upload bukan gambar:(');
            </script>";
            return false;
        }
        if ($ukuranfile > 500000){
            echo"
            <script>
                alert('Maaf, ukuran file terlalu besar:(');
            </script>"
            ;
            return false;
        }
        $namafileBaru = uniqid();
        $namafileBaru .= '.';
        $namafileBaru .= $ekstensigambar;
        
        move_uploaded_file($tmpname, 'img/' .$namafileBaru);
        return $namafileBaru;
    }
    function hapus ($id){
        global $koneksi;
        mysqli_query($koneksi, "DELETE FROM tbl_orang WHERE Id = $id");
        
        return mysqli_affected_rows($koneksi);
    }
    
    function ubah ($post) {
        global $koneksi;

        $id = htmlspecialchars($post["Id"]);
        $nama = htmlspecialchars($post["nama"]);
        $nisn = htmlspecialchars($post["nisn"]);
        $email = htmlspecialchars($post["email"]);
        $jurusan = htmlspecialchars($post["jurusan"]);
        $gambarLama = htmlspecialchars($post["gambarLama"]);

        if ($_FILES ["gambar"]["error"] === 4) {
            $gambar = $gambarLama;
        } else {
           $gambar = upload();
        }
        $sql = "UPDATE tbl_orang SET
            nama = '$nama',
            nisn = '$nisn',
            email = '$email',
            jurusan = '$jurusan',
            gambar = '$gambar'
            
            WHERE Id = $id";

            mysqli_query($koneksi, $sql);
            return mysqli_affected_rows($koneksi);
    }


?>
