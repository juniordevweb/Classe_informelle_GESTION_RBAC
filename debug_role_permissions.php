<?php
$db = new mysqli('localhost', 'root', '', 'classe_informelle', 3306);
if ($db->connect_error) { exit($db->connect_error); }
$queries = [
    'SELECT id,nom_role FROM roles ORDER BY id',
    'SELECT id,nom,email,role_id FROM users ORDER BY id',
    'SELECT role_id,menu_id,sous_menu_id,permission_id FROM role_permissions ORDER BY role_id,menu_id,sous_menu_id,permission_id',
];
foreach ($queries as $sql) {
    $result = $db->query($sql);
    while ($row = $result->fetch_assoc()) { echo json_encode($row, JSON_UNESCAPED_UNICODE) . PHP_EOL; }
    echo "---" . PHP_EOL;
}
