<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema</title>
</head>
    <h2>Meu novo sistema</h2>
<?php
$url = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

# $u = "http" . (isset($_SERVER['HTTP_HOST']))

if (isset($_SERVER['HTTPS'])) {
    echo "<p>É HTTPS</p>\n\r";
} else {
    echo "<p>Não é HTTPS</p>\n\r"; 
}
echo "<p> A hospedagem é: " . $url . "</p>\r\n";
echo "<p> A URI é: " . $uri . "</p>\r\n";
?>
    <p><?php echo "<p>A chave é: " . $_GET['p'] . "</p>\n"; ?></p>
     <!-- <p><?php echo "<p>O valor é: " . $_GET['v'] . "</p>\n"; ?></p> -->
    <div>
        <ul>
            <li><a href="inicio.php?p=1&v=casa">Enviar Param 1</a></li>
            <!-- <li><a href="inicio.php?p=2&v=apartamento">Enviar Param 2</a></li> -->
        </ul>
    </div>
    
<body>
    
</body>
</html>