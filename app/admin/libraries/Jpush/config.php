<?php
require 'autoload.php';

use JPush\Client as JPush;

$app_key = 'd1434a34c1638801f1cbb74d';
$master_secret = 'b61e55dd7a902c1cdacac7c6';
//$registration_id = '190e35f7e0177cce4ad';

$client = new JPush($app_key, $master_secret,null,3,'BJ');