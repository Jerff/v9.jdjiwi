<?php

class cInputIp {

    const path = 'HTTP_X_FORWARDED_FOR|HTTP_CLIENT_IP|HTTP_X_CLIENT_IP|HTTP_X_CLUSTER_CLIENT_IP|REMOTE_ADDR';

    private $ip = null;
    private $proxy = null;

    //cmfGetIp(
    //cInput::ip()->get(;
    public function get() {
        if (is_null($this->ip)) {
            foreach (explode('|', self::path) as $index) {
                if (!empty($_SERVER[$index])) {
                    $item = $_SERVER[$index];
                    if (strpos($item, ',') !== false) {
                        list($item) = explode(',', $item, 2);
                    }
                    if ($this->isValid($item)) {
                        $this->ip = $item;
                        break;
                    }
                }
            }

            if (empty($this->ip)) {
                $this->ip = '0.0.0.0';
            }
        }
        return $this->ip;
    }

    //cmfGetIpInt(
    //cInput::ip()->getInt(
    public function getInt() {
        return ip2long($this->get());
    }

    //cmfGetProxy(
    //cInput::ip()->proxy(
    public function proxy() {
        if (is_null($this->proxy)) {
            if (empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $this->proxy = $_SERVER['REMOTE_ADDR'];
            }
            if (empty($this->proxy)) {
                $this->proxy = '0.0.0.0';
            }
        }
        return $this->proxy;
    }

    //cmfGetProxyInt(
    //cInput::ip()->proxyInt(
    public function proxyInt() {
        return (int) ip2long($this->proxy());
    }

    public function isValid($ip, $flag = '') {
        $flag = strtolower($flag);

        // First check if filter_var is available
        if (is_callable('filter_var')) {
            switch ($flag) {
                case 'ipv4':
                    $flag = FILTER_FLAG_IPV4;
                    break;
                case 'ipv6':
                    $flag = FILTER_FLAG_IPV6;
                    break;
                default:
                    $flag = '';
                    break;
            }

            return (bool) filter_var($ip, FILTER_VALIDATE_IP, $flag);
        }

        if ($flag !== 'ipv6' && $flag !== 'ipv4') {
            if (strpos($ip, ':') !== false) {
                $flag = 'Ipv6';
            } elseif (strpos($ip, '.') !== false) {
                $flag = 'Ipv4';
            } else {
                return FALSE;
            }
        } else {
            $flag = ucwords($flag);
        }

        $func = 'isValid' . $flag;
        return $this->$func($ip);
    }

    protected function isValidIpv4($ip) {
        $mSegments = explode('.', $ip);

        if (count($mSegments) !== 4) {
            return false;
        }
        if ($mSegments[0][0] == '0') {
            return false;
        }

        foreach ($mSegments as $segment) {
            if (!is_numeric($segment) OR (int) $segment > 255 OR strlen($segment) > 3) {
                return false;
            }
        }
        return true;
    }

    protected function isValidIpv6($str) {
        // 8 groups, separated by :
        // 0-ffff per group
        // one set of consecutive 0 groups can be collapsed to ::

        $groups = 8;
        $collapsed = FALSE;

        $chunks = array_filter(
                preg_split('/(:{1,2})/', $str, NULL, PREG_SPLIT_DELIM_CAPTURE)
        );

        // Rule out easy nonsense
        if (current($chunks) == ':' OR end($chunks) == ':') {
            return false;
        }

        // PHP supports IPv4-mapped IPv6 addresses, so we'll expect those as well
        if (strpos(end($chunks), '.') !== false) {
            $ipv4 = array_pop($chunks);
            if (!$this->isValidIpv4($ipv4)) {
                return false;
            }
            $groups--;
        }

        while ($seg = array_pop($chunks)) {
            if ($seg[0] == ':') {
                if (--$groups == 0) {
                    return false; // too many groups
                }
                if (strlen($seg) > 2) {
                    return false; // long separator
                }
                if ($seg == '::') {
                    if ($collapsed) {
                        return false; // multiple collapsed
                    }
                    $collapsed = true;
                }
            } elseif (preg_match("/[^0-9a-f]/i", $seg) OR strlen($seg) > 4) {
                return false; // invalid segment
            }
        }

        return $collapsed OR $groups == 1;
    }

}

?>
