<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "--- ALL SERVICES ---\n";
foreach (\App\Models\Service::all() as $s) {
    echo "ID: {$s->id} - Name: {$s->name}\n";
}

echo "\n--- ALL PROVIDERS (Therapists) ---\n";
foreach (\App\Models\Provider::with('user', 'services')->get() as $p) {
    $name = $p->user ? "{$p->user->first_name} {$p->user->last_name}" : "Unknown";
    echo "ID: {$p->id} - Name: {$name}\n";
    foreach ($p->services as $s) {
        echo "   -> Attached Service ID: {$s->id} Name: {$s->name}\n";
    }
}
