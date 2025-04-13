<?php
$encrypted = 'CElsRO7K3QCzagZ+sBFWcHOxY9ggiwfctx36AABTAYSjl8PX+p3RR9IHsMGffgLk';

// 1. Check if Base64 is valid
$decoded = base64_decode($encrypted, true);
if ($decoded === false) {
    die("❌ Invalid Base64 data");
}

// 2. Check minimum length (IV + ciphertext)
$minLength = 16 + 16; // IV (16) + minimum ciphertext (16)
if (strlen($decoded) < $minLength) {
    die("❌ Data too short. Got " . strlen($decoded) . " bytes, need ≥{$minLength}");
}

// 3. Extract IV and ciphertext
$iv = substr($decoded, 0, 16);
$ciphertext = substr($decoded, 16);

echo "✅ Valid encrypted data\n";
echo "Length: " . strlen($decoded) . " bytes\n";
echo "IV (hex): " . bin2hex($iv) . "\n";
echo "Ciphertext length: " . strlen($ciphertext) . " bytes\n";
echo "First 16 bytes of ciphertext (hex): " . bin2hex(substr($ciphertext, 0, 16)) . "\n";
