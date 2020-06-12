<?php
include_once("../clases/Encuesta.php");
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

$encuesta = Encuesta::getInstancia();
$cantidad = 0;
$lProcesaFormulario = false;

if(!$_SESSION['logeado'] or $_SESSION['perfil'] != "admin"){
    $_SESSION['mensaje'] = "Nada de trampas.";
    header('Location: ../login.php');
}

if(isset($_POST['generarFormulario'])){
    $cantidad = limpiarDatos($_POST['cantidad']);
    $lProcesaFormulario = true;
}

if(isset($_POST['crearEncuesta'])){
    $encuesta->setEncuesta(limpiarDatos($_POST['nombre']));
    $_SESSION['mensaje'] = $encuesta->mensaje;
    foreach($_POST['pregunta'] as $clave=>$valor){
        $encuesta = Encuesta::getInstancia();
        $idEncuesta = $encuesta->getEncuesta(limpiarDatos($_POST['nombre']));
        $encuesta->setPregunta($valor, $idEncuesta['id']);
    }
    $_SESSION['mensaje'] = $encuesta->mensaje;
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
    echo "<br>Usted está logeado como ".$_SESSION['usuario'].".<br>";
    echo "<a href=\"../cerrar.php\">Logout</a>";

    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
        echo "<p>Cantidad de preguntas:</p><br> ";
        echo "<input type=\"text\" name=\"cantidad\" value=\"\" required><br>";
        echo "<br><input type=\"submit\" name=\"generarFormulario\" value=\"Enviar\">";
    echo "</form>";

    if($lProcesaFormulario){
        echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
        echo "<p>Nombre:</p><br> ";
        echo "<input type=\"text\" name=\"nombre\" value=\"\" required><br>";
        for($i = 1; $i <= $cantidad; $i++){
            echo "<p>Pregunta ".$i.":</p><br> ";
            echo "<input type=\"text\" name=\"pregunta[".$i."]\" value=\"\" required><br>";
        }

        echo "<p>Fecha de inicio:</p><br> ";
        echo "<input type=\"date\" name=\"fechaInicio\" value=\"\" required><br>";
        echo "<p>Fecha final:</p><br> ";
        echo "<input type=\"date\" name=\"fechaFinal\" value=\"\" required><br>";
        
        echo "<br><input type=\"submit\" name=\"crearEncuesta\" value=\"Enviar\">";
        echo "</form>";
    }

    $encuestas = $encuesta->get();
    echo "<h2>Encuestas</h2>";
    foreach($encuestas as $enc){
        echo"<table border=1>";
        echo "<caption>".$enc['Titulo']."</caption>";
        $preguntas = $encuesta->getPreguntas($enc['id']);
        foreach($preguntas as $pregunta){
            echo "<tr>";
            echo "<td>".$pregunta['pregunta']."</td>";
            echo "<td>".$encuesta->getPuntuacionMedia($pregunta['id'])."</td>";
            echo "</tr>";
        }  
        echo "</table>";
    }
?>

</body>
</html>