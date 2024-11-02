<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);
if ($data && isset($data['sendImageSource'])) {
    $_SESSION['breakImageSource'] = $data['sendImageSource'];
}
if ($data && isset($data['alertsWillBeShown'])) {
    $_SESSION['alertsWillBeShown'] = $data['alertsWillBeShown'];
}