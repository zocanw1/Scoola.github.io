<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$members = \App\Models\TeamMember::all();
echo "=== DEBUG TEAM MEMBERS ===\n";
echo "Total records: " . count($members) . "\n\n";

foreach ($members as $member) {
    echo "ID: {$member->id} | Name: {$member->name} | Role: {$member->role}\n";
}
?>
