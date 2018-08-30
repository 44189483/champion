<?php
require __DIR__ . '/../autoload.php';

use JPush\Client as JPush;

$app_key = getenv('d1434a34c1638801f1cbb74d');
$master_secret = getenv('b61e55dd7a902c1cdacac7c6');
$registration_id = getenv('registration_id');

$client = new JPush($app_key, $master_secret);
