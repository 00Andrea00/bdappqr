<?php
// Configuración de CORS
$allowedOrigins = [
  // 'https://ruth.tandempatrimonionacional.eu/bd-appqr',
  'http://localhost:8000', 
  'http://localhost', 
  'https://ruth-appqr.netlify.app', 
  'https://appqr-danieltandem.netlify.app',
  'https://appqrandres.netlify.app',
  'https://appqryekaos.netlify.app/',
  'ttps://app-qr-andrea.netlify.app/'
];

if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
}

header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(204); 
  exit;
}
