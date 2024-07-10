<?php
// The route does not exist
require('functions.php');
$oJ = [
    'error' => 404,
    'message' => 'Request not found',
    'result' => [],
];
response_json($oJ);

