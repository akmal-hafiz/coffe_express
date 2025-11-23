<?php
$db = new PDO('sqlite:database/database.sqlite');
echo "Orders table structure in SQLite:\n";
$result = $db->query('PRAGMA table_info(orders)');
while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    echo "  " . $row['name'] . " - " . $row['type'] . "\n";
}
