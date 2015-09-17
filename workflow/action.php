<?php
$operation = $argv[1];
$payload = json_decode($argv[2]);

if ($operation === 'copy') {
    echo $payload->cmd;
} else {
    echo $payload->url;
}
