<?php

class admin_access_core {

    private $write = '|update|deleteFile|';
    private $read = '';
    private $remove = '';
    private $func = false;

    public function isSingleton() {

    }

    protected function readAdd($func) {
        $this->read .= $func . '|';
    }

    protected function readDel($func) {
        $this->read = str_replace('|' . $func . '|', '|', $this->read);
    }

    protected function writeAdd($func) {
        $this->write .= $func . '|';
    }

    protected function writeDel($func) {
        $this->write = str_replace('|' . $func . '|', '|', $this->write);
    }

    protected function removeAdd($func) {
        $this->remove .= $func . '|';
    }

    protected function removeDel($func) {
        $this->remove = str_replace('|' . $func . '|', '|', $this->remove);
    }

    public function setRule($func) {
        $this->func = $func;
    }

    public function isRule($where) {
        if ($this->func) {
            $func = $this->func;
            return $func($where);
        }
        return true;
    }

    public function isCall($func) {
        foreach (explode('|', $this->callFuncRead) as $f) {
            if ($f === $func) {
                $function = 'read';
                break;
            }
        }
        foreach (explode('|', $this->callFuncWrite) as $f) {
            if ($f === $func) {
                cAccess::isWrite($this);
                $function = 'write';
            }
        }
        foreach (explode('|', $this->callFuncDelete) as $f) {
            if ($f === $func) {
                cAccess::isDelete($this);
                $function = 'delete';
            }
        }

        if (isset($function)) {
            return $function === 'write' or $function === 'delete';
        } else {
            cAccess::isControllerRead($this);
        }
    }

}

?>