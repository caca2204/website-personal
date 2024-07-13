<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">
   <div class="row">
      <div class="image">
         <img src="gambar/channels4_profile.jpg" alt="">
      </div>
      <div class="content">
         <h3>📌 MAZHAB CREATIVE 📌</h3>
         <p>
            Kenapa harus beli Kaos Dakwah MAZHAB Apparel❓
            <br>
            🔥✔️ Designya Simpel dan TETAP KEREN dengan Kaos Dakwah Islami
            <br>
            🔥✔️ Bisa menyampaikan PESAN DAKWAH Kakak lewat kaos
            <br>
            🔥✔️ Bahannya berkualitas: ADEM DAN NYAMAN
            <br>
            🔥✔️ Harganya TERJANGKAU
            <br>
            🔥✔️ (1 Kg muat 5 Pcs)
            <br>
            🔥✔️ BISA COD
            <br><br>
            Sampaikan kebaikan dalam berpakaian
            <br><br>
            ⭐⭐⭐⭐⭐ DIJAMIN KUALITAS BARANG TERBAIK DAN PASTI ORIGINAL, PRODUK LANGSUNG DIKIRIMKAN DARI GUDANG KAMI SENDIRI (BUKAN DROPSHIP), PENGIRIMAN CEPAT, PELAYANAN MAKSIMAL
            <br><br>
            📌FAST RESPON (SENIN - JUMAT ) ( 07.00 - 16.00 ) Jam Kerja
         </p>
         <a href="https://api.whatsapp.com/send?phone=6287788172059" class="btn">Hubungi Kami</a>
      </div>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>
