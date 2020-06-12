<?php
include_once("../clases/Serie.php");
include_once("../clases/Usuario.php");
include_once("../clases/Pago.php");
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

$usuarioTemp = "";
$serie = Serie::getInstancia();
$usuario = Usuario::getInstancia();
$pago = Pago::getInstancia();

$pagosPendientes = 0;
$bloqueado = false;

if(!$_SESSION['logeado'] or $_SESSION['perfil'] != "basico"){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}

if(isset($_POST['verSerie'])){
    $id = "";
    foreach($_POST['verSerie'] as $i=>$valor){
        $id = $i;
    }
    $_SESSION['reproduccion'] = $serie->get($id);
    header('Location: reproduccion.php');
}

if(isset($_POST['premium'])){
    $usuario->edit($_SESSION['id']);
    $_SESSION['perfil'] = "premium";
    $_SESSION['mensaje'] = $usuario->mensaje;
    header('Location: premium.php');
}

?>
<html>
<head>
    <meta charset="utf-8">
    <title>Seriestv</title>
</head>
<body>

<?php
    echo "<p>".$_SESSION['mensaje']."</p>";
    $pagosPendientes = $pago->getPago($_SESSION['id']);
    $mes = date("n");
    if($pagosPendientes > 1){
        $bloqueado = true;
    }

    if($pagosPendientes > 0){
        echo "<a href=\"../cerrar.php\">Logout </a><a href=\"pagos.php\">Pagos pendientes(".$pagosPendientes.") </a><a href=\"encuesta.php\">Encuestas </a><br>";
    }else{
        echo "<a href=\"../cerrar.php\">Logout </a><a href=\"encuesta.php\">Encuestas </a><br>";
    }

    if($bloqueado){
        echo "<h2>Su cuenta está bloqueada debido a que tiene más de un pago pendiente. Si desea volver a ver sus series, salde sus deudas.</h2>";
    }
    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
    echo"<table border=1>";
    echo "<caption>Series</caption>";
    echo "<tr><td>Título</td><td>Caratula</td><td>Reproducciones</td><td>Plan</td></tr>";
    $series = $serie->getAllSeries();
    for($i = 0; $i < count($series); $i++){
        echo "<tr>";
        echo "<td>".$series[$i]['titulo']."</td>";
        echo "<td><img width=\"100px\" heigth=\"100px\" src=\"../img/".$series[$i]['caratula']."\"></img></td>";
        echo "<td>".$series[$i]['numero_reproducciones']."</td>";
        if($bloqueado){
            echo "<td><input type=\"submit\" value=\"Ver serie\" disabled></td>";
        }else{
            switch($series[$i]['id_plan']){
                case 1:
                    echo "<td>Básico</td>";
                    echo "<td><input type=\"submit\" name=\"verSerie[".$series[$i]['id']."]\" value=\"Ver serie\"></td>";
                break;
                case 2:
                    echo "<td>Premium</td>";
                    echo "<td><input type=\"submit\" name=\"premium[".$series[$i]['id']."]\" value=\"Mejorar a premium\"></td>";
                break;
            }
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";
?>

</body>
</html>