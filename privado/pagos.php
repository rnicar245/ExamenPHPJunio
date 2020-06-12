<?php
include_once("../clases/Pago.php");;
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

$usuarioTemp = "";
$pago = Pago::getInstancia();

$mes = date("n");
$contador = 0;
$_SESSION['arrayPagos'] = array();

if(!$_SESSION['logeado'] or $_SESSION['perfil'] == "admin" or $_SESSION['perfil'] == "invitado"){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}

if(isset($_POST['pagar'])){
    foreach($_POST['pagar'] as $i=>$valor){
        $_SESSION['indice'] = $i;
    }

    header('Location: descargarFactura.php');
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

    echo "<a href=\"../cerrar.php\">Logout </a><a href=\"".$_SESSION['perfil'].".php\">Inicio</a><br>";
    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
    echo "<table border=1>";
    echo "<caption>Pagos pendientes</caption>";
    echo "<tr><td>Fecha</td><td>Importe</td></tr>";
    $pagos = $pago->get($_SESSION['id']);
    for($i = 1; $i <= $mes; $i++){
        $encontrado = false;
        $importe = 0;
        $anyo = 0;
        foreach($pagos as $pag){
            if($pag['mes'] == $i){
                $encontrado = true;
            }
        }
        if(!$encontrado){
            echo "<tr>";
            if($i < 10){
                echo "<td>01/0".$i."/2020</td>";
            }else{
                echo "<td>01/".$i."/2020</td>";
            }
            echo "<td>15</td>";
            echo "<td><input type=\"submit\" name=\"pagar[".$contador."]\" value=\"Pagar\"></td>";
            array_push($_SESSION['arrayPagos'], array(
                "fecha"=>date("d")."/".date("m")."/".date("Y"),
                "mensualidad"=>$i,
                "importe"=>15,
                "idUser"=>$_SESSION['id'],
                "anyo"=>date("Y")));
            $contador++;
        }
    }
    echo "</table>";
    echo "</form>";
?>

</body>
</html>