<?php

declare(strict_types=1);

require dirname(__DIR__) . '/src/Database.php';
require dirname(__DIR__) . '/src/ActivityRepository.php';

use RopaDesk\ActivityRepository;
use RopaDesk\Database;

$db = sys_get_temp_dir() . '/ropadesk-test-' . getmypid() . '.db';
$repo = new ActivityRepository(new Database($db));

$row = $repo->create([
    'name' => 'Customer CRM',
    'purpose' => 'Manage customer relationships',
    'legal_basis' => 'Art. 6(1)(b) contract',
    'data_categories' => 'name, email',
    'locale' => 'de',
]);

if ($row['name'] !== 'Customer CRM' || count($repo->all()) !== 1) {
    echo "FAIL create\n";
    exit(1);
}

$updated = $repo->update((int) $row['id'], array_merge($row, ['retention' => '3 years']));
if ($updated['retention'] !== '3 years') {
    echo "FAIL update\n";
    exit(1);
}

@unlink($db);
echo "All tests passed.\n";
