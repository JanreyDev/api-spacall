<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Provider;
use App\Models\TherapistProfile;
use App\Models\Service;

$providerId = 12;
$provider = Provider::find($providerId);

if (!$provider) {
    die("Provider with ID $providerId not found.\n");
}

// 1. Verify and Make Available
$provider->update([
    'verification_status' => 'verified',
    'verified_at' => now(),
    'is_active' => true,
    'is_available' => true,
    'is_accepting_bookings' => true,
]);

// 2. Set Location in Profile (so he shows up in search)
$profile = $provider->therapistProfile()->first();
if ($profile) {
    $profile->update([
        'base_location_latitude' => 14.5,
        'base_location_longitude' => 120.9,
    ]);
}

// 3. Attach a Service (Service ID 1 - Swedish Massage)
$service = Service::find(1);
if ($service) {
    $provider->services()->syncWithoutDetaching([
        1 => ['price' => 850, 'is_available' => true]
    ]);
    echo "Service '{$service->name}' attached to Provider ID $providerId.\n";
}

echo "Janrey Therapist (ID $providerId) is now VERIFIED, AVAILABLE, and has a SERVICE.\n";
echo "Location set to: 14.5, 120.9\n";
