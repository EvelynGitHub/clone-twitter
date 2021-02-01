<?php

namespace App\Helpers;

trait Helper
{

    public function jsonSend(string $message, int $status = HttpStatusCode::__default, $token = null, $data = [])
    {
        $array = array(
            "message" => $message,
            "status" => $status,
            "token" => $token,
            "data" => $data
        );

        http_response_code($status);

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
