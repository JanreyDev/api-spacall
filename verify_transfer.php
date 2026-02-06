<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Service;
use App\Models\Provider;

echo "--- WALLET TRANSFER VERIFICATION ---\n";

// 1. Setup Test Customer
$rand = rand(1000, 9999);
$customerMobile = "0911222{$rand}"; // Randomness to avoid unique constraint
User::where('mobile_number', $customerMobile)->forceDelete();
$customer = User::create([
    'mobile_number' => $customerMobile,
    'first_name' => 'Transfer',
    'last_name' => 'Customer',
    'role' => 'client',
    'wallet_balance' => 5000,
    'is_verified' => true,
    'uuid' => (string) \Illuminate\Support\Str::uuid(),
    'pin_hash' => password_hash('123456', PASSWORD_DEFAULT),
]);

// 2. Setup Test Therapist
$therapistMobile = "0922333{$rand}"; 
User::where('mobile_number', $therapistMobile)->forceDelete();
$therapistUser = User::create([
    'mobile_number' => $therapistMobile,
    'first_name' => 'Earnings',
    'last_name' => 'Therapist',
    'role' => 'therapist',
    'is_verified' => true,
    'uuid' => (string) \Illuminate\Support\Str::uuid(),
    'pin_hash' => password_hash('123456', PASSWORD_DEFAULT),
]);
$provider = Provider::create([
    'user_id' => $therapistUser->id,
    'type' => 'therapist',
    'total_earnings' => 0,
    'is_active' => true,
    'is_available' => true,
    'uuid' => (string) \Illuminate\Support\Str::uuid(),
]);

echo "1. Initial State:\n";
echo "   - Customer Balance: {$customer->wallet_balance}\n";
echo "   - Therapist Earnings: {$provider->total_earnings}\n";

// 3. Create Booking (Swedish Massage - Price 850)
$service = Service::find(1);
$booking = Booking::create([
    'booking_number' => 'BK-' . time(),
    'customer_id' => $customer->id,
    'provider_id' => $provider->id,
    'service_id' => $service->id,
    'booking_type' => 'home_service',
    'schedule_type' => 'now',
    'status' => 'pending',
    'service_price' => 850,
    'total_amount' => 850,
    'payment_method' => 'wallet',
]);

echo "\n2. After Booking Creation (Pending):\n";
echo "   - Customer Balance: " . User::find($customer->id)->wallet_balance . " (Expected: 5000)\n";

// 4. Step 1: Therapist marks as 'completed' (No transfer yet)
echo "\n3. Step 1: Therapist marking as Completed...\n";
DB::beginTransaction();
try {
    $booking = Booking::find($booking->id);
    $booking->update(['status' => 'completed', 'completed_at' => now()]);
    $booking->provider->update(['is_available' => true]);
    DB::commit();
    echo "✅ Therapist signaling done.\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "   - Intermediate Customer Balance: " . User::find($customer->id)->wallet_balance . " (Expected: 5000)\n";
echo "   - Intermediate Therapist Earnings: " . Provider::find($provider->id)->total_earnings . " (Expected: 0)\n";

// 5. Step 2: Customer confirms 'completed' (Trigger Transfer)
echo "\n4. Step 2: Customer releasing funds (Marking Completed)...\n";
DB::beginTransaction();
try {
    $booking = Booking::find($booking->id);
    $customer = $booking->customer;
    $provider = $booking->provider;
    
    if ($booking->payment_method === 'wallet' && $booking->payment_status === 'pending') {
        $customer->decrement('wallet_balance', $booking->service_price);
        $provider->increment('total_earnings', $booking->service_price);
        
        // Final Transfer to Therapist User Wallet
        $provider->user->increment('wallet_balance', $booking->service_price);
        
        $booking->update(['payment_status' => 'paid', 'status' => 'completed']);
    }
    DB::commit();
    echo "✅ Success: Customer released funds!\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n5. Final State:\n";
$finalCustomer = $customer->fresh();
$finalTherapistUser = $therapistUser->fresh();
$finalProvider = $provider->fresh();

echo "   - Final Customer Wallet: {$finalCustomer->wallet_balance} (Expected: 4150)\n";
echo "   - Final Therapist Wallet: {$finalTherapistUser->wallet_balance} (Expected: 850)\n";
echo "   - Final Therapist Earnings: {$finalProvider->total_earnings} (Expected: 850)\n";

echo "\n--- VERIFICATION COMPLETE --- \n";
