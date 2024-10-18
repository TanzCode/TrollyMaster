<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="CSS/slider.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
   
    <title>Dashboard - Seller</title>

    
</head>
<body id="body-pd">
    <?php
    include 'slider.php';
    ?>
    
    <!-- Container Main start -->
    <div class="height-100 bg-light">
      <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
        <iframe id="mainContent" style="width: 100%; height: 80%;" frameborder="0" src="Home.html"></iframe>
    </div>
    <!-- Container Main end -->
</body >
</html>
<script src="JS/slider.js"></script>
 <!-- Bootstrap JS, Popper.js, and jQuery -->
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
