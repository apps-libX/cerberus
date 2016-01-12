<?php
/**
 * CerberusSessionRepositoryInterface.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:29.
 */

namespace Cerberus\Repositories\Session;

use Cerberus\DataTransferObjects\BaseResponse;

interface CerberusSessionRepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @return BaseResponse
     */
    public function store($data);

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return BaseResponse
     */
    public function destroy();
}
