<?php

namespace App\Helpers;

trait Helper
{

    public function jsonSend(string $message, string $status, string $url = null)
    {
        $array = array(
            "message" => $message,
            "status" => $status
        );

        $url ?? ($array["url"] =  URL_BASE . $url);

        return json_encode($array);
    }


    public function render($file, $array = null)
    {
        if (!is_null($array)) {
            foreach ($array as $var => $value) {
                ${$var} = $value;
            }
        }

        ob_start();
        include "{$file}.php";
        ob_flush();
    }

    public function isEmpty(array $keys, ?array $data)
    {
        if (!Helper::isExists($keys, $data)) return true;

        for ($i = 0; $i < count($keys); $i++) {
            $key = $keys[$i];

            if (empty($data[$key])) {
                return true;
            }
        }

        return false;
    }

    function isExists(array $key, ?array $data)
    {
        $notExist = array_diff($key, array_keys($data));

        if ($notExist) return false;

        return true;
    }
}
