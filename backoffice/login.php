<?php
include("../Functions/auth/functions.php");
include("../Functions/httperrors/functions.php");
include("../database.php");

$user = getAuthenticatedUser();
if($user){
    if($user["roleID"] == 1){
        header("Location: ./roles/index.php");
    }else{
        header("Location: ./customers/index.php");
    }
}

$invalidCredentials = null;
$databaseConnection = connectToDatabase();

if(isset($_POST["login"])){
    $email = htmlspecialchars($_POST["email"]);
    $password = htmlspecialchars($_POST["password"]);
    $token = htmlspecialchars($_POST["CSRFToken"] ?? "");

    if(!validateCSRFToken($token)){
        ThrowHTTPForbiddenError();
        exit;
    }else{
        $user = authenticate($email, $password, $databaseConnection);
        if($user){
            setAuthenticatedUser($user);
            if($user["roleID"] == 1){
                header("Location: ./roles/index.php");
            }else{
                header("Location: ./customers/index.php");
            }
        }else{
            $invalidCredentials = true;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="m;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="../Public/JS/fontawesome.js"></script>
    <script src="../Public/JS/jquery.min.js"></script>
    <script src="../Public/JS/bootstrap.min.js"></script>
    <script src="../Public/JS/popper.min.js"></script>
    <script src="../Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="../Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="../Public/CSS/typekit.css">

    <title>Document</title>
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card bg-dark text-white">
                    <div class="card-header text-center">inloggen NerdyGadgets</div>

                    <div class="card-body">
                        <?php if($invalidCredentials){ ?>
                        <div class="bg-danger text-white p-4 mb-4" role="alert">
                            <strong>Het wachtwoord of emailadres is onjuist probeer het nogmaals.</strong>
                        </div>
                        <?php }  ?>

                        <form action="" method="POST">
                            <input type="hidden" name="CSRFToken" value="<?php print(createCSRFToken()); ?>">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail:</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control <?php if(isset($invalidCredentials)) print("is-invalid"); ?>" name="email" value="<?php if(isset($email)) print($email); ?>" required autocomplete="email" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Wachtwoord</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control <?php if(isset($invalidCredentials)) print("is-invalid"); ?>" name="password" required autocomplete="password">
                                </div>
                            </div>

                            <div class="form-group mt-5 row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" name="login" class="btn btn-md px-5 btn-success">
                                        Inloggen
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>
</html>
