<?php 

namespace Bravist\Cnvex\Signers;

abstract class AbstractSigner
{
    public function __construct(array $config = [])
    {
        $this->setConfig($config);
    }

    /**
     * Sort the source data
     * @param  array  $data
     * @return string
     */
    public function sort(array $data)
    {
        //去掉无效参数
        $filteredData = array();
        foreach ($data as $key => $val) {
            if ($key == "sign" || $val == "") {
                continue;
            } else {
                $filteredData[$key] = $data[$key];
            }
        }
        //对数组排序
        ksort($filteredData, 0);
        //构建请求参数字符串
        $arg  = "";
        foreach ($filteredData as $key => $val) {
            if (is_array($val)) {
                $val = json_encode($val);
            } elseif (is_bool($val)) {
                if ($val) {
                    $val="true";
                } else {
                    $val="false";
                }
            }
            $arg .= $key.'='.($val).'&';
        }
        $arg = substr($arg, 0, -1);
        //如果存在转义字符，那么去掉转义
        if (get_magic_quotes_gpc()) {
            $arg = stripslashes($arg);
        }
        return $arg;
    }

    /**
     * setConfig.
     *
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $method = 'set'.ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
        return $this;
    }
}
