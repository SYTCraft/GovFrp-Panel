<?php
// generate_codes.php

require 'db.php';

function generateCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$traffic_value = 1024; // 1GB
$code = generateCode();

$stmt = $pdo->prepare("INSERT INTO redeem_codes (code, traffic_value) VALUES (?, ?)");
$stmt->execute([$code, $traffic_value]);

echo "兑换码: $code 流量奖励: $traffic_value";