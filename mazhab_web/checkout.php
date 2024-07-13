<?php
include 'components/connect.php'; // Pastikan koneksi database diimpor

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit;
}

if (isset($_POST['order'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];
   $note = filter_var($_POST['note'], FILTER_SANITIZE_STRING);

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, note) VALUES(?,?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price, $note]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Pemesanan Sukses!';
   } else {
      $message[] = 'Keranjang kosong!';
   }
}

// Fungsi untuk memformat harga menjadi Rupiah
function format_rupiah($angka) {
   return number_format($angka, 0, ',', '.');
}

// hitung ongkir
function calculate_ongkir($state, $total_quantity) {
    $ongkir_jawa_dki = array("Jawa Barat", "DKI Jakarta");
    $base_ongkir = in_array($state, $ongkir_jawa_dki) ? 10000 : 28000;
    $total_weight = ceil($total_quantity / 5); // 1kg untuk setiap 5 baju, lebih dari 5 baju menjadi 2kg
    return $base_ongkir * $total_weight;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

      <h3>Pesanan Anda</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $total_quantity = 0;
         $cart_items = [];
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $cart_items[] = $fetch_cart['name'] . ' (Rp.' . format_rupiah($fetch_cart['price']) . ' x ' . $fetch_cart['quantity'] . ') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
               $total_quantity += $fetch_cart['quantity'];
      ?>
         <p><?= $fetch_cart['name']; ?> <span>(<?= 'Rp.' . format_rupiah($fetch_cart['price']) . '/- x ' . $fetch_cart['quantity']; ?>)</span></p>
      <?php
            }
         } else {
            echo '<p class="empty">Keranjang kosong!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Total Ongkir : <span id="ongkir"></span></div>
         <div class="grand-total">Total Keseluruhan : <span id="grand_total"></span></div>
      </div>

      <h3>Form Order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Nama :</span>
            <input type="text" name="name" placeholder="Masukkan Nama Anda" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Nomor Hp :</span>
            <input type="number" name="number" placeholder="0877654623450" class="box" maxlength="13" required>
         </div>
         <div class="inputBox">
            <span>Email :</span>
            <input type="email" name="email" placeholder="Masukkan Email anda" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Metode Pembayaran :</span>
            <select name="method" class="box" required>
               <option value="BCA">BCA</option>
               <option value="Mandiri">Mandiri</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Nama Jalan :</span>
            <input type="text" name="flat" placeholder="Co. Jl. Mangga besar No. 18" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Provinsi :</span>
            <input type="text" name="state" placeholder="co. Jawa Barat" class="box" maxlength="50" required id="state">
         </div>
         <div class="inputBox">
            <span>Kota/Kabupaten :</span>
            <input type="text" name="city" placeholder="co. Bogor" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Kecamatan :</span>
            <input type="text" name="street" placeholder="Co. Sukaraja" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Kelurahan :</span>
            <input type="text" name="country" placeholder="co. Cimandala" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Kode Pos :</span>
            <input type="number" name="pin_code" placeholder="Co. 16710" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Catatan Pembeli :</span>
            <textarea name="note" placeholder="Masukkan catatan Anda" class="box" maxlength="255" rows="3"></textarea>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Pesan Sekarang">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>
<script>
// JavaScript untuk menghitung ongkir secara otomatis saat state diubah
document.getElementById('state').addEventListener('change', function() {
   var state = this.value;
   var total_quantity = <?= $total_quantity; ?>;
   var ongkir = calculateOngkir(state, total_quantity);
   document.getElementById('ongkir').textContent = 'Rp.' + formatRupiah(ongkir) + '.';
   document.getElementById('grand_total').textContent = 'Rp.' + formatRupiah(<?= $grand_total; ?> + ongkir) + '.';
});

function calculateOngkir(state, total_quantity) {
   var ongkir_jawa_dki = ["Jawa Barat", "DKI Jakarta", "jawa barat", "dki jakarta"];
   var base_ongkir = ongkir_jawa_dki.includes(state) ? 10000 : 28000;
   var total_weight = Math.ceil(total_quantity / 5);
   return base_ongkir * total_weight;
}

function formatRupiah(angka) {
   return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}
</script>

</body>
</html>
