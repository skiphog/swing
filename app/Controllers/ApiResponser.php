<?php

namespace App\Controllers;

use System\Http\Response;

trait ApiResponser
{
    /**
     * @param array $data
     * @param int   $code
     *
     * @return Response
     */
    public function error(array $data, $code = 422)
    {
        return json(['errors' => $data], $code);
    }

    /**
     * @param mixed $data
     * @param int   $code
     *
     * @return Response
     */
    public function success($data = null, $code = 200)
    {
        return json(['status' => 'ОК', 'data' => $data], $code);
    }
}
