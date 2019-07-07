<?php


namespace App\KeyValueStore;


// $fp = stream_socket_client("tcp://127.0.0.1:1337", $errno, $errstr, 30);
// if (!$fp) {
//     echo "$errstr ($errno)<br />\n";
// } else {
//     // fwrite($fp, "set a www".PHP_EOL);
//     // echo fgets($fp);
//     // fflush($fp);
//     fwrite($fp, "get a" . PHP_EOL);
//     echo fgets($fp);
//     fflush($fp);
//
//     fclose($fp);
// }

class Client
{
    private $dsn;

    /**
     * Client constructor.
     */
    public function __construct($dsn)
    {
        $this->dsn = $dsn;
    }

    public function set($key, $value)
    {
        return $this->sendCommand("set {$key} {$value}");
    }

    public function get($key)
    {
        return $this->sendCommand("get {$key}");
    }

    public function has($key)
    {
        return $this->sendCommand("has {$key}");
    }

    public function delete($key)
    {
        return $this->sendCommand("delete {$key}");
    }

    private function sendCommand($command)
    {
        $fp = $this->buildSocket();
        fwrite($fp, $command . PHP_EOL);
        $ret = fgets($fp);
        fflush($fp);
        $this->destroySocket();
        return $ret;
    }

    private function buildSocket()
    {
        $this->fp = stream_socket_client($this->dsn, $errno, $errstr, 30);
        if (!$this->fp) {
            echo "$errstr ($errno)<br />\n";
        }
        return $this->fp;
    }

    private function destroySocket()
    {
        fclose($this->fp);
    }
}

// $client = new Client("tcp://127.0.0.1:1337");
// echo $client->set('a', 12) . PHP_EOL;
// echo $client->get('a') . PHP_EOL;
// echo $client->has('a') . PHP_EOL;
// echo $client->delete('a') . PHP_EOL;
// echo $client->has('a') . PHP_EOL;
