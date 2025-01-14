<?
$mysqli = new mysqli("localhost", "root", "root", "test");

if($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

function getSubgroups($groupId) {
    global $mysqli;
    $subgroups = [];
    $result = $mysqli->query("SELECT id FROM groups WHERE id_parent = $groupId");
    while ($row = $result->fetch_assoc()) {
        $subgroups[] = $row['id'];
        $subgroups = array_merge($subgroups, getSubgroups($row['id']));
    }
    return $subgroups;
}

function getProductCount($groupId) {
    global $mysqli;
    $subgroups = getSubgroups($groupId);
    $subgroups[] = $groupId;
    $ids = implode(',', $subgroups);
    $result = $mysqli->query("SELECT COUNT(*) as count FROM products WHERE id_group IN ($ids)");
    return $result->fetch_assoc()['count'];
}

$selectedGroupId = isset($_GET['group']) ? intval($_GET['group']) : 0;

echo "<h1>Группы товаров</h1><ul>";
$result = $mysqli->query("SELECT id, name FROM groups WHERE id_parent = 0");
while ($row = $result->fetch_assoc()) {
    $count = getProductCount($row['id']);
    echo "<li><a href='?group={$row['id']}'>{$row['name']}</a> ($count)</li>";
}
echo "</ul>";

if ($selectedGroupId > 0) {
    echo "<h2>Подгруппы</h2><ul>";
    $result = $mysqli->query("SELECT id, name FROM groups WHERE id_parent = $selectedGroupId");
    while ($row = $result->fetch_assoc()) {
        $count = getProductCount($row['id']);
        echo "<li><a href='?group={$row['id']}'>{$row['name']}</a> ($count)</li>";
    }
    echo "</ul>";

    echo "<h2>Товары</h2><ul>";
    $productIds = getSubgroups($selectedGroupId);
    $productIds[] = $selectedGroupId; 
    $ids = implode(',', $productIds);
    $result = $mysqli->query("SELECT name FROM products WHERE id_group IN ($ids)");
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['name']}</li>";
    }
    echo "</ul>";
}

$mysqli->close();
?>