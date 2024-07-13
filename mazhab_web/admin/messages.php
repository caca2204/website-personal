<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit;
}

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
   $delete_message->execute([$delete_id]);
   header('location:messages.php');
   exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pesan</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="contacts">

<h1 class="heading">Pesan</h1>

<div class="box-container">

   <?php
      $select_messages = $conn->prepare("SELECT * FROM `messages`");
      $select_messages->execute();
      if($select_messages->rowCount() > 0){
         while($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> user id : <span><?= $fetch_message['user_id']; ?></span></p>
      <p> nama : <span><?= $fetch_message['name']; ?></span></p>
      <p> email : <span><?= $fetch_message['email']; ?></span></p>
      <p> Pesan : <span><?= $fetch_message['message']; ?></span></p>
      <?php if($fetch_message['image']): ?>
         <p> Foto : <span><img src="../uploaded_images/<?= $fetch_message['image']; ?>" alt="User Image" width="150"></span></p>
      <?php endif; ?>
      <a href="messages.php?delete=<?= $fetch_message['id']; ?>" onclick="return confirm('Hapus pesan ini?');" class="delete-btn">Hapus</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">Tidak ada pesan masuk</p>';
      }
   ?>

</div>

</section>

<script src="../js/admin_script.js"></script>
   
</body>
</html>
