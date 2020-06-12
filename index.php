<?php
include_once("clases/Serie.php");
include_once("config/config.php");
include_once("config/funciones.php");
session_start();

$usuarioTemp = "";
$serie = Serie::getInstancia();

if (!isset($_SESSION['mensaje'])){
    $_SESSION['mensaje'] = "";
    $_SESSION['logeado'] = false;
    $_SESSION['usuario'] = "invitado";
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
    echo "<a href=\"login.php\">Login </a><a href=\"registro.php\">Registro</a><br>";
    echo "<form action= ".htmlspecialchars($_SERVER["PHP_SELF"])." method= \"POST\">";
    echo "<h3>No estás registrado. Click <a href=\"registro.php\">aquí</a> para crear tu cuenta y comenzar a ver tus series favoritas.</h3>";
    echo"<table border=1>";
    echo "<caption>Series</caption>";
    echo "<tr><td>Título</td><td>Caratula</td><td>Reproducciones</td><td>Plan</td></tr>";
    $series = $serie->getAllSeries();
    for($i = 0; $i < count($series); $i++){
        echo "<tr>";
        echo "<td>".$series[$i]['titulo']."</td>";
        echo "<td><img width=\"100px\" heigth=\"100px\" src=\"img/".$series[$i]['caratula']."\"></img></td>";
        echo "<td>".$series[$i]['numero_reproducciones']."</td>";
        switch($series[$i]['id_plan']){
            case 1:
                echo "<td>Básico</td>";
            break;
            case 2:
                echo "<td>Premium</td>";
            break;
        }
        echo "<td><input type=\"submit\" value=\"Ver serie\" disabled></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";
?>

</body>
</html>