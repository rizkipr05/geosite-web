<?php
use App\Models\User;
use Illuminate\Support\Facades\Hash;
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = User::where('email', 'admin@gmail.com')->first();
if ($user) {
    echo "User found: " . $user->name . "\n";
    echo "Role: " . $user->role . "\n";
    if (Hash::check('admin12345', $user->password)) {
        echo "Password matches.\n";
    } else {
        echo "Password DOES NOT match.\n";
    }
} else {
    echo "User NOT found.\n";
}
