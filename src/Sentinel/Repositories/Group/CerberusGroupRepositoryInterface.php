<?php
/**
 * CerberusGroupRepositoryInterface.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:26.
 */

namespace Cerberus\Repositories\Group;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Cerberus\Models\User;

interface CerberusGroupRepositoryInterface
{
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store($data);
    
    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id);

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id);

    /**
     * Return a specific user by a given id
     * 
     * @param  integer $id
     * @return User
     */
    public function retrieveById($id);

    /**
     * Return a specific user by a given name
     * 
     * @param  string $name
     * @return User
     */
    public function retrieveByName($name);

    /**
     * Return all the registered users
     *
     * @return Collection
     */
    public function all();
}
