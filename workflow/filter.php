<?php
require_once __DIR__ . "/vendor/autoload.php";
use Alfred\Workflow;

if ($argc < 3) exit;

$w = new Workflow();

$type = $argv[1]; //ready for tabs, but the API documentation is incomplete so I cant implement it yet

//get the search term, and the base64 val of it
$query = urlencode(trim($argv[2]));
$base = base64_encode($query);

//query the api
$url = sprintf("http://www.commandlinefu.com/commands/matching/{$query}/{$base}/json", $query);
$search = json_decode(file_get_contents($url));

//return results or 404
if (count($search)) {
    //build result array
    $results = array_map(function ($command) {
        return [
            'uid'          => $command->id,
            'arg'          => json_encode(array('cmd' => $command->command, 'url' => $command->url)),
            'title'        => $command->summary,
            'subtitle'     => $command->command,
            'icon'         => false,
            'valid'        => 'yes',
        ];
    }, $search);
} else {
    //build not found msg
    $results = [[
        'title' => '404 Command not Found',
        'icon'  => false,
        'valid' => 'yes',
    ]];
}

//set results
$w->results = $results;

//return alfred's xml
echo $w->toXML();
