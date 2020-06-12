<?php
include_once("../config/config.php");
include_once("../config/funciones.php");
include_once("../clases/Pago.php");;
session_start();

if(!$_SESSION['logeado'] or $_SESSION['perfil'] == "admin" or $_SESSION['perfil'] == "invitado"){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}
$pago = Pago::getInstancia();

$pago->set($_SESSION['arrayPagos'][$_SESSION['indice']]['idUser'], $_SESSION['arrayPagos'][$_SESSION['indice']]['mensualidad'], $_SESSION['arrayPagos'][$_SESSION['indice']]['anyo'], $_SESSION['arrayPagos'][$_SESSION['indice']]['importe']);
$_SESSION['mensaje'] = "<span style=\"color:green\">Pago realizado con éxito</span>";

$fileName = "pago.txt";


    header("Content-disposition: attachment; filename=$fileName");
    header("Content-type: text/plain");

    echo "CARTA DE PAGO\n";
    echo $_SESSION['usuario']."\n";
    echo "Fecha del pago: ".$_SESSION['arrayPagos'][$_SESSION['indice']]['fecha']."\n";
    echo "Mensualidad: ".$_SESSION['arrayPagos'][$_SESSION['indice']]['mensualidad']."\n";
    echo "Importe: ".$_SESSION['arrayPagos'][$_SESSION['indice']]['importe']."\n";

    readfile("../config/sample.txt");
    exit;

?>