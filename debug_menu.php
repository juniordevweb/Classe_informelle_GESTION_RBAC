<?php
$db = mysqli_connect('localhost', 'root', '', 'classes_informelles');
if (!$db) die('Erreur connexion: ' . mysqli_connect_error());

echo "=== SOUS_MENUS (menu_id = 8) ===\n";
$res = mysqli_query($db, 'SELECT * FROM sous_menus WHERE menu_id = 8');
$hasRows = false;
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
    $hasRows = true;
}
if (!$hasRows) echo "Aucune entrée trouvée\n";

echo "\n=== ROLE_PERMISSIONS (menu_id = 8) ===\n";
$res = mysqli_query($db, 'SELECT * FROM role_permissions WHERE menu_id = 8');
$hasRows = false;
while($row = mysqli_fetch_assoc($res)) {
    print_r($row);
    $hasRows = true;
}
if (!$hasRows) echo "Aucune entrée trouvée\n";

echo "\n=== Role actuel en session ===\n";
echo "Votre user_id: " . (session()->get('user_id') ?? 'N/A') . "\n";
echo "Votre role_id: " . (session()->get('role_id') ?? 'N/A') . "\n";

mysqli_close($db);
?>