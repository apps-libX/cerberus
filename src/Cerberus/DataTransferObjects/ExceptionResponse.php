<?php
/**
 * ExceptionResponse.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:46.
 */

namespace Cerberus\DataTransferObjects;

class ExceptionResponse extends BaseResponse
{
    public function __construct($message, array $payload = null)
    {
        parent::__construct($message, $payload);

        $this->success = false;
        $this->error = true;
    }
}
