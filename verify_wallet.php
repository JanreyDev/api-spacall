<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Provider;

echo "--- WALLET SYSTEM VERIFICATION ---\n";

// 1. Create a Test Customer
$mobile = '09990009999';
User::where('mobile_number', $mobile)->forceDelete();

$customer = User::create([
    'mobile_number' => $mobile,
    'first_name' => 'Wallet',
    'last_name' => 'Tester',
    'role' => 'client',
    'wallet_balance' => 5000, // Simulating initial registration balance
    'is_verified' => true,
    'pin_hash' => password_hash('123456', PASSWORD_DEFAULT),
]);

echo "1. Created Test Customer with Balance: {$customer->wallet_balance} points.\n";

// 2. Prepare a Service and Provider
$service = Service::find(1); // Swedish Massage (850)
$provider = Provider::where('is_available', true)->first();

if (!$service || !$provider) {
    die("Error: Service or Provider not found for testing.\n");
}

echo "2. Found Service: {$service->name} (Price: {$service->base_price}).\n";

// 3. Simulate Booking Creation (Deduction Logic)
echo "3. Simulating Booking and Deduction...\n";

// Re-fetch customer to ensure we have fresh state
$customer = User::find($customer->id);

if ($customer->wallet_balance >= $service->base_price) {
    // Logic from BookingController
    $customer->decrement('wallet_balance', $service->base_price);
    
    $booking = Booking::create([
        'customer_id' => $customer->id,
        'provider_id' => $provider->id,
        'service_id' => $service->id,
        'booking_type' => 'home_service',
        'status' => 'pending',
        'service_price' => $service->base_price,
        'total_amount' => $service->base_price,
        'payment_method' => 'wallet',
    ]);

    echo "✅ Booking Created! ID: {$booking->id}\n";
    echo "✅ New Wallet Balance: " . User::find($customer->id)->wallet_balance . " points.\n";
} else {
    echo "❌ Insufficient Balance.\n";
}

echo "\n--- VERIFICATION COMPLETE --- \n";
