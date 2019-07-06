<?php


namespace App\webserver;


class WebServer
{
    private $currentDirectoryFiles = [];
    private $cwd = '';

    /**
     * WebServer constructor.
     */
    public function __construct()
    {
    }

    public function start($path = '')
    {
        $this->cwd = empty($path) ? getcwd() : base64_decode($path);
        $html = $this->getDirectoryContent();
        $html = "<html><head></head><body>{$this->cwd}<hr/>{$html}</body>";
        return $html;
    }

    public function readFile($filePath)
    {
        $filePath = base64_decode($filePath);
        header('Content-Type: ' . mime_content_type($filePath));
        echo file_get_contents($filePath);
    }

    private function getDirectoryContent()
    {
        $this->scanDirectory();
        return $this->buildHtml();
    }

    private function scanDirectory()
    {
        $files = scandir($this->cwd);

        foreach ($files as $key => $value) {
            $path = realpath($this->cwd . DIRECTORY_SEPARATOR . $value);
            if (is_dir($path)) {
                $this->currentDirectoryFiles[] = [
                    'key'  => base64_encode(realpath($this->cwd . DIRECTORY_SEPARATOR . $value)),
                    'type' => 'directory',
                    'path' => $path,
                    'name' => $value
                ];
            } else if ($value != "." && $value != "..") {
                $this->currentDirectoryFiles[] = [
                    'key'  => '',
                    'type' => 'file',
                    'path' => $path,
                    'name' => $value
                ];
            }
        }
    }

    private function buildHtml()
    {
        $ret = '<ul>';
        foreach ($this->currentDirectoryFiles as $f) {
            $ret .= "<li>";
            if ($f['type'] == 'directory')
                $ret .= "<a href='/?p={$f['key']}'>{$f['name']}</a>\n";
            else{
                $path =base64_encode($f['path']);
                $ret .= "<a href='/?f={$path}'>{$f['name']}</a>\n";
            }

            $ret .= "</li>";
        }
        $ret .= "</ul>";
        return $ret;
    }


}