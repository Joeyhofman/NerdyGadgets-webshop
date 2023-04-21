<?php 
$userName = $user["FullName"];

if(isset($_POST["logout"])){
    logout();
    header("Location: ../login.php");
}
?>

<aside class="d-flex flex-column p-3 text-white bg-dark sticky-top" style="width: 300px; height: 100vh;">
        <div class="logo w-50">
            <div id="LogoImage"></div> 
        </div>
        <div class="user mb-4 py-2">
            <h4>
                Welkom <?php print($userName); ?>
            </h4>
        </div>
        <nav class="nav nav-pills flex-column mb-auto">
        <?php if($userIsCustomerService || $userIsAdministration) { ?>
        <li class="nav-item">
            <a href="../customers/index.php" class="nav-link active py-3" aria-current="page">
            klanten
            </a>
        </li>
        <?php } ?>
        <?php if($userIsCustomerService || $userIsAdministration) { ?>
        <li>
            <a href="../orders/index.php" class="nav-link text-white py-3">
            bestellingen
            </a>
        </li>
        <?php } ?>
        <?php if($userIsAdministrator) { ?>
        <li>
            <a href="../roles/index.php" class="nav-link text-white py-3">
            Rollen toewijzen
            </a>
        </li>
        <?php } ?>
        </nav>
        <hr>
        <div class="dropdown">
        <form action="" method="POST">
            <button name="logout" class="text-white btn btn-danger w-100">
                <strong>Uitloggen</strong>
            </button>
        </form>
        </div>
    </aside>