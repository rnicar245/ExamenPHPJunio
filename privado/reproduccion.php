<html>
<head>
    <meta charset="utf-8">
    <title>Seriestv</title>
</head>
<body>

<?php
session_start();

if(!isset($_SESSION['reproduccion'])){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}else if(!$_SESSION['logeado'] or $_SESSION['perfil'] == 'invitado'){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}else if($_SESSION['reproduccion']['id_plan'] == 2 and $_SESSION['perfil'] != "premium"){
    $_SESSION['mensaje'] = "Acceso denegado.";
    header('Location: ../login.php');
}
    echo "<a href=\"".$_SESSION['perfil'].".php\">Volver</a><br>";
    echo "<h2>Visualizando ".$_SESSION['reproduccion']['titulo']."</h2>";
?>

</body>
</html>