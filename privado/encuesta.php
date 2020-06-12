<?php
include_once("../clases/Encuesta.php");
include_once("../config/config.php");
include_once("../config/funciones.php");
session_start();

$encuesta = Encuesta::getInstancia();

if(!$_SESSION['logeado'] or $_SESSION['perfil'] == "admin"){
    $_SESSION['mensaje'] = "Nada de trampas.";
    header('Location: ../login.php');
}

if(isset($_POST['enviar'])){
    foreach($_POST['encuesta'] as $encuesta){
        foreach($encuesta as $pregunta=>$respuesta){
            $encuesta = Encuesta::getInstancia();
            $encuesta->set($pregunta, $respuesta);
        }
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

    echo "<a href=\"../cerrar.php\">Logout </a><a href=\"".$_SESSION['perfil'].".php\">Inicio</a><br>";

    $encuestas = $encuesta->get();
    $contador = 0;
    echo "<h2>Encuestas</h2>";
    foreach($encuestas as $enc){
    echo "</br><form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
        echo"<table border=1>";
        echo "<caption>".$enc['Titulo']."</caption>";
        echo "<tr><td>Puntúa de 1 a 5</td><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td></tr>";
        $preguntas = $encuesta->getPreguntas($enc['id']);
        foreach($preguntas as $pregunta){
            echo "<tr>";
            echo "<td>".$pregunta['pregunta']."</td>";
            for($i = 1; $i <= 5; $i++){
                echo "<td><input type=\"radio\" name=\"encuesta[".$pregunta['idEncuesta']."][".$pregunta['id']."]\" value=\"".$i."\" required><br></td>";
            }
            echo "</tr>";
        }  
        echo "</table>";
    
    echo "<br><td><input type=\"submit\" name=\"enviar\" value=\"Enviar Resultados\"></td>";
    echo "</form>";
    }
?>

</body>
</html>