<?
$mysqli = new mysqli("localhost", "root", "root", "test");

if($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

function getSubgroups($groupId) {
    global $mysqli;
    $subgroups = [];
    $result = $mysqli->query("SELECT id FROM groups WHERE id_parent = $groupId");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Тестовое задание </title>
</head>
<body>
    
</body>
</html>