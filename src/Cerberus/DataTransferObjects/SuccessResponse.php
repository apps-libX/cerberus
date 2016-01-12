<?php
/**
 * SuccessResponse.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:47.
 */

namespace Cerberus\DataTransferObjects;

class SuccessResponse extends BaseResponse
{
    public function __construct($message, array $payload = null)
    {
        parent::__construct($message, $payload);

        $this->success = true;
    }
}
