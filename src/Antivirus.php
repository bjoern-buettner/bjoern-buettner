<?php

namespace Me\BjoernBuettner;

use Appwrite\ClamAV\Pipe;

class Antivirus
{
    private Pipe $clam;

    public function __construct()
    {
        $this->clam = new Pipe();
    }

    public function sclean(string $data): bool
    {
        if (empty($data)) {
            return true;
        }
        $tmp = dirname(__DIR__) . '/cache/clamav-' . microtime(true) . md5($data);
        if (!file_put_contents($tmp, $data)) {
            error_log('Couldn\'t set data for ClamAV.');
            return false;
        }
        $return = $this->fclean($tmp);
        unlink($tmp);
        return $return;
    }

    public function fclean(string $file): bool
    {
        if (!is_file($file)) {
            return true;
        }
        $tmp = dirname(__DIR__) . '/cache/clamav-' . microtime(true) . md5($file);
        if (!copy($file, $tmp)) {
            error_log('Couldn\'t set data for ClamAV.');
            return false;
        }
        $return = $this->clean($tmp);
        unlink($tmp);
        return $return;
    }

    private function clean(string $file): bool
    {
        if (!$this->clam->ping()) {
            error_log('ClamAV not found!');
            return false;
        }
        if (!chmod($file, 0777)) {
            error_log('Couldn\'t change access for ClamAV.');
            return false;
        }
        $return = $this->clam->fileScan($file);
        if (!$return) {
            error_log('ClamAV found an issue with an uploaded file.');
        }
        return $return;
    }
}
