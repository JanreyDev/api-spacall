<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "--- WALLET BALANCE CHECK ---\n";
$mobile = '09998887777'; // Fresh unique number
User::where('mobile_number', $mobile)->forceDelete();

$u = User::create([
    'mobile_number' => $mobile,
    'first_name' => 'Final',
    'last_name' => 'WalletTest',
    'role' => 'client',
    'wallet_balance' => 5000,
    'is_verified' => true,
    'pin_hash' => password_hash('123456', PASSWORD_DEFAULT),
]);

echo "1. User Created ID: {$u->id}\n";
echo "2. Initial Balance: {$u->wallet_balance}\n";

$u->decrement('wallet_balance', 850);
echo "3. Balance after 850 deduction: " . User::find($u->id)->wallet_balance . "\n";
echo "--- TEST COMPLETE ---\n";
