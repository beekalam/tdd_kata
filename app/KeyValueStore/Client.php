<?php


namespace App\KeyValueStore;


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
