<?php

namespace App\KeyValueStore;

use App\map\Map;

require __DIR__ . DIRECTORY_SEPARATOR . "CommandParser.php";
require "../map/Map.php";

$server = stream_socket_server("tcp://127.0.0.1:1337", $errno, $errorMessage);

if ($server === false) {
    throw new UnexpectedValueException("Could not bind to socket: $errorMessage");
}

$map = new Map();

for (; ;) {
    $client = @stream_socket_accept($server);

    if ($client) {
        $commandParser = new CommandParser(fgets($client));
        if ($commandParser->hasValidCommand()) {
            $command = $commandParser->getCommand();
            $params = $commandParser->getParams();

            if ($command == "get") {
                fwrite($client, $map->get($params[0]) . PHP_EOL);
            } else if ($command == "set") {
                $map->put($params[0], $params[1]);
                fwrite($client, "true" . PHP_EOL);
            } else if ($command == "has") {
                $msg = $map->has($params[0]) ? "true" : "false" . PHP_EOL;
                fwrite($client, $msg);
            } else if ($command == "delete") {
                $map->remove($params[0]);
                fwrite($client, "true" . PHP_EOL);
            }
        }
        fflush($client);
        fclose($client);
    }
}