<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_EMAIL);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   // Handle image upload
   if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
      $image_name = $_FILES['image']['name'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_size = $_FILES['image']['size'];
      $image_folder = 'uploaded_images/';
      $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);
      $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

      if(in_array($image_ext, $allowed_ext) && $image_size <= 2097152){
         $image_new_name = uniqid() . '.' . $image_ext;
         if (move_uploaded_file($image_tmp_name, $image_folder . $image_new_name)) {
            // File uploaded successfully
         } else {
            $message[] = 'gagal mengupload gambar!';
            $image_new_name = '';
         }
      } else {
         $message[] = 'file gambar terlalu besar atau ekstensi tidak diizinkan!';
         $image_new_name = '';
      }
   } else {
      $image_new_name = '';
   }

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND message = ?");
   $select_message->execute([$name, $email, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'Pesan sudah terkirim sebelumnya!';
   } else {
      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, message, image) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $msg, $image_new_name]);

      $message[] = 'Pesan terkirim!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Kontak</title>
   
   <!-- Font Awesome CDN Link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">
   <form action="" method="post" enctype="multipart/form-data">
      <h3>Pesan</h3>
      <input type="text" name="name" placeholder="Masukkan nama" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="Masukkan email anda" required maxlength="50" class="box">
      <textarea name="msg" class="box" placeholder="masukkan pesan" cols="30" rows="10"></textarea>
      <input type="file" name="image" class="box" accept="image/*">
      <input type="submit" value="Kirim pesan" name="send" class="btn">
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
