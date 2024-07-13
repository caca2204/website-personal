<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
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
   <title>Pesanan</title>
   
   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File -->
   <link rel="stylesheet" href="css/style.css">
   <link rel="stylesheet" href="css/style2.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Form Order</h1>

   <div class="box-container">

   <?php
      if ($user_id == '') {
         echo '<p class="empty">Silakan login untuk melihat pesanan Anda.</p>';
      } else {
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p>Tanggal Order : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Nama : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>No HP : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Alamat : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Metode Pembayaran : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Pembelian : <span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Total Harga : <span>Rp.<?= number_format($fetch_orders['total_price'], 0, ',', '.'); ?></span></p>
      <p>Status Pembayaran : <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') { echo 'red'; } else { echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
      <p>Catatan Pembeli : <span><?= $fetch_orders['note']; ?></span></p>
      
      <!-- Form Detail Pembayaran -->
      <div class="payment-details">
         <h2>Detail Pembayaran</h2>
         <?php
            $paymentDetails = '';
            if ($fetch_orders['method'] === 'Mandiri') {
               $paymentDetails = 'Nomor rekening Mandiri 1260011180766 A.N Anastasya Salsabilla';
            } elseif ($fetch_orders['method'] === 'BCA') {
               $paymentDetails = 'Nomor rekening BCA 1672294457 A.N Anastasya Salsabilla';
            }
         ?>
         <p><?= $paymentDetails; ?></p>
         <p>Jika sudah transfer harap mengirim bukti pada nomor WhatsApp ini: <a href="https://wa.me/6287788172059">087788172059</a></p>
      </div>
   </div>
   <?php
            }
         } else {
            echo '<p class="empty2">Belum ada pesanan yang ditempatkan!</p>';
         }
      }
   ?>
   </div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
