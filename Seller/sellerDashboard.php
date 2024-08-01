<?php
session_start();

// Check if the first name is set in the session
if (isset($_SESSION['firstName']) && isset($_SESSION['lastName']) && isset($_SESSION['userID'])) {
    $firstName = $_SESSION['firstName'];
    $lastName = $_SESSION['lastName'];
    $userID = $_SESSION['userID'];
   
    // You can now use $firstName in your HTML or other PHP code
} else {
    echo "First name not set.";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    
    
 <!--   <link rel="stylesheet" href="style.css">-->
    <script src="../JS/bootstrap.min.js"></script> 



    <style type="text/css" >
        
        @media (min-width: 992px) {
    .navbar,
    .navbar-collapse {
        flex-direction: column;
    }
    .navbar-expand-lg .navbar-nav {
        flex-direction: column;
    }
    .navbar {
        width: 100%;
        height: 100%;
    }
}

body, html {
    margin: 0;
    padding: 0;
}

header {
    height: 70px;
    width: 80%;
    padding: 0 30px;
    background-color: var(--background-color1);
    position: fixed;
    z-index: 100;
    box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.825);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.logo {
    font-size: 27px;
    font-weight: 600;
    color: rgb(47, 141, 70);
}

.icn {
    height: 30px;
}

.menuicn {
    cursor: pointer;
}

.searchbar,
.message,
.logosec {
    display: flex;
    align-items: center;
    gap: 20px;
}

.searchbar {
    background-color: rgba(161, 182, 253, 0.308);
    height: 40px;
    border-radius: 50px;
    padding: 0 15px;
}

.searchbar input {
    height: 100%;
    width: 300px;
    outline: none;
    border: none;
    background-color: transparent;
}

.searchbar img {
    height: 20px;
}

.message {
    background-color: rgba(161, 182, 253, 0.308);
    height: 40px;
    width: 40px;
    border-radius: 50px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.message img {
    height: 22px;
}

body {
    height: 100vh;
    width: 100vw;
    overflow: hidden;
    background-color: var(--background-color1);
    font-family: "Jost", sans-serif;
}

.main-container {
    display: flex;
    height: 100vh;
    width: 100vw;
    position: relative;
    top: 70px;
}

.assi-body {
    height: 100vh;
    width: 84vw;
    padding: 30px 40px;
    overflow-y: auto;
}

.view-body {
    height: 100vh;
    width: 100vw;
    padding: 30px 40px;
    overflow-y: auto;
}

.sidebar {
    height: 100%;
    width: 16vw;
    background-color: var(--background-color2);
    display: flex;
    align-items: center;
    flex-direction: column;
    position: fixed;
    top: 70px;
    left: 0;
    z-index: 200;
    overflow-y: auto;
}

.sidebar .menu {
    height: 100%;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.sidebar .menu .menuitem {
    height: 50px;
    width: 100%;
    display: flex;
    align-items: center;
    padding: 0 20px;
    gap: 15px;
    cursor: pointer;
    color: rgb(248, 248, 248);
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.sidebar .menu .menuitem:hover {
    background-color: rgba(0, 0, 0, 0.178);
}

.sidebar .menu .menuitem img {
    height: 20px;
}

.sidebar {
    height: 100%;
    width: 16vw;
    background-color: var(--background-color2);
    display: flex;
    align-items: center;
    flex-direction: column;
    position: fixed;
    top: 100px; /* Increased top value to add padding */
    left: 0;
    z-index: 200;
    overflow-y: auto;
}


.sidebar .menu .logout:hover {
    background-color: rgba(0, 0, 0, 0.178);
}

.sidebar .menu .logout img {
    height: 20px;
}

.dash-container {
    display: flex;
    height: 13vh;
    width: 100%;
    gap: 20px;
}

.dash-inside {
    height: 100%;
    flex: 1;
    background-color: var(--background-color2);
    box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.534);
    padding: 10px;
    border-radius: 15px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    color: var(--primary-text-color);
    cursor: pointer;
}

.dash-inside img {
    height: 30px;
    width: 30px;
    filter: invert(1);
}

.dash-inside .head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 20px;
    font-weight: 600;
}

.dash-inside .head .head-img {
    height: 25px;
}

.dash-inside .head p {
    margin: 0;
}

.dash-inside .head img {
    height: 25px;
}

.assi-container {
    display: flex;
    height: auto;
    width: 100%;
    flex-direction: column;
}

.assi-container .head {
    font-size: 25px;
    font-weight: 600;
}

.assi-box {
    display: flex;
    height: auto;
    width: 100%;
    flex-direction: column;
    background-color: var(--background-color2);
    box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.534);
    padding: 10px;
    border-radius: 15px;
    justify-content: space-between;
    color: var(--primary-text-color);
    gap: 10px;
}

.assi-box table {
    width: 100%;
    border-collapse: collapse;
}

.assi-box th,
.assi-box td {
    padding: 10px;
    text-align: left;
}

.view-container {
    display: flex;
    height: auto;
    width: 100%;
    flex-direction: column;
}

.view-container .head {
    font-size: 25px;
    font-weight: 600;
}

.view-box {
    display: flex;
    height: auto;
    width: 100%;
    flex-direction: column;
    background-color: var(--background-color2);
    box-shadow: 1px 1px 15px rgba(161, 182, 253, 0.534);
    padding: 10px;
    border-radius: 15px;
    justify-content: space-between;
    color: var(--primary-text-color);
    gap: 10px;
}

.view-box table {
    width: 100%;
    border-collapse: collapse;
}

.view-box th,
.view-box td {
    padding: 10px;
    text-align: left;
}

footer {
    width: 100vw;
    padding: 20px 30px;
    background-color: var(--background-color1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    bottom: 0;
}

footer .footer-content {
    display: flex;
    align-items: center;
    gap: 20px;
}

footer .footer-content p {
    margin: 0;
    font-size: 14px;
    color: var(--primary-text-color);
}

</style>

    </style>
    <title>Dashboard - Seller</title>
</head>
<body style="background-color: #fffff0;">


    <div class="container-fluid"> 
        <!-- Row with full viewport height -->
        <div class="row " style="height: 100vh">

            <!-- Column for side navigation -->
            <div class="col-lg-2 col-md-3 col-sm-4">
            <nav class="navbar navbar-expand-lg navbar-light bs-side-navbar" style="background-color: #81C408"><!-- Side navigation bar -->
            <div >
            <a class="navbar-brand" href="index.html">
                <img src="log.png" alt="" width="100%" height="100%">
            </a><!-- Logo image -->
            </div>
            <!-- Navbar toggle button icon -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse " id="navbarNav">
                <ul class="nav nav-tabs navbar-nav ms-auto">

                    <!-- Store managemnt -->
                    <li class="nav-item dropdown">
                        <a style="color:#ffffff; font-family: verdana;  font-size: 18px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Store Management 
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- Register link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="Addwaiting()" >Add new store</a></li>
                            <!-- View patient link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewWaiting()" >View store list</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewWaiting()" >Update stores</a></li>
                        </ul>
                    </li>


                    <!-- Product dropdown -->
                    <li class="nav-item dropdown">
                        <a style="color:#ffffff; font-family: verdana;  font-size: 18px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Product
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- Add new Product link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="newProduct()" >Add new Product</a></li>
                            <!-- View Product link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewProductList()">View all Product list</a></li>
                            <!-- Update Producte list link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="updateProduct()">Update product  </a></li>
                        </ul>
                    </li>
                    <!--product management-->
                    <li class="nav-item">
                        <a style="color:#ffffff; font-family: verdana; font-size: 18px;" class="nav-link" href="javascript:void(0)" onclick="invoice()" >Order</a>
                    </li>

                    <!-- customer handling -->
                    <li class="nav-item dropdown">
                        <a style="color:#ffffff; font-family: verdana;  font-size: 18px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customer
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- Add new operation theater link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="NewOT()" >Add new customer</a></li>
                            <!-- View operation theater link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewOT()">Manage customer</a></li>
                        </ul>
                    </li>

                    <!-- Staff dropdown -->
                    <li class="nav-item dropdown">
                        <a style="color:#ffffff; font-family: verdana;  font-size: 18px;" class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            delivery person 
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <!-- Add new Staff member link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="NewwStaff()">Add new delivery member</a></li>
                            <!-- View Staff link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewStaff()">View delivery members</a></li>
                            <!-- View staff schedule link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="viewStaffSchedule()">View delivery schedule</a></li>
                            <!-- Add new OT schedule link -->
                            <li><a class="dropdown-item" href="javascript:void(0)" onclick="newStaffSchedule()">Add delivery schedule</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a style="color:#ffffff; font-family: verdana; font-size: 18px;" class="nav-link" href="Home.html">Log-out</a>
                    </li>
                </ul>
            </div>
        </nav>

            </div>
            <div class="col-lg-10 col-md-9 col-sm-8" >
            
                <!-- Main Content Area -->
                <div class="welcome-message">
                    <h1>Welcome, <?php echo htmlspecialchars($firstName) . " " . htmlspecialchars($lastName); ?>!</h1>
                    <p >Seller ID: <span id="user-id"><?php echo htmlspecialchars($userID); ?></span></p>
                </div>
                <hr>

                <iframe id="mainContent" style="width: 100%; height: 80%;" frameborder="0" src="Home.html"></iframe>
            </div>

        </div>
    </div>
<footer>
        <div class="container">
            <p>&copy; 2024 Trolly Master. All rights reserved.</p>
        </div>
</footer>
<script>

    function loadHome() {
        document.getElementById("mainContent").src = "Home.html";
    }
    function LoadServices() {
        document.getElementById("mainContent").src = "services.html";
    }
    function loadContact() {
        document.getElementById("mainContent").src = "contactUs.html";
    }
    function loadAboutUs() {
        document.getElementById("mainContent").src = "aboutUs.html";
    }

//patient
	function LoadPatientReg() {
        document.getElementById("mainContent").src = "patient_registration.html";
    }
    function loadpatientview() {
        document.getElementById("mainContent").src = "patient_search.php";
    }
    function patient_history() {
        document.getElementById("mainContent").src = "patient_history_search.php";
    }
    function Addpatienthistory() {
        document.getElementById("mainContent").src = "patient_History_form.html";
    }

//waiting List
	function Addwaiting() {
        document.getElementById("mainContent").src = "waitingList.html";
    }
    function viewWaiting() {
        document.getElementById("mainContent").src = "patient_waiting_search.php";
    }
//invoice
   function invoice() {
        document.getElementById("mainContent").src = "invoice.html";
    }
//room
 	function newProduct() {
        document.getElementById("mainContent").src = "productInsert.html";
    }
    function viewProductList() {
        document.getElementById("mainContent").src = "viewAllProducts.php";
    }
    function updateProduct() {
        document.getElementById("mainContent").src = "updateProducts.php";
    }
    function NewRoomSchedule() {
        document.getElementById("mainContent").src = "Room_schedule_form.html";
    }
//ward
 	function Newward() {
        document.getElementById("mainContent").src = "ward.html";
    }
    function viewward() {
        document.getElementById("mainContent").src = "ward_search.php";
    }
    function viewwardSchedule() {
        document.getElementById("mainContent").src = "ward_schedule_search.php";
    }
    function NewWardSchedule() {
        document.getElementById("mainContent").src = "ward_schedule_form.html";
    }
//OT
 	function NewOT() {
        document.getElementById("mainContent").src = "OT.html";
    }
    function viewOT() {
        document.getElementById("mainContent").src = "OT_search.php";
    }
    function viewOTschedule() {
        document.getElementById("mainContent").src = "OT_schedule_search.php";
    }
    function NewwOTschedule() {
        document.getElementById("mainContent").src = "OT_schedule_form.html";
    }
//staff
 	function NewwStaff() {
        document.getElementById("mainContent").src = "Staff_form.html";
    }
    function viewStaff() {
        document.getElementById("mainContent").src = "Staff_search.php";
    }
    function viewStaffSchedule() {
        document.getElementById("mainContent").src = "Staff_schedule_search.php";
    }
    function newStaffSchedule() {
        document.getElementById("mainContent").src = "Staff_schedule.html";
    }


</script>

         

</body>
<body>