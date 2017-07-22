<?php 
/**
 * Ping Contract
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

interface PingContract
{
	/**
     * Set Id
     *
     * @param (int) $Id
     * @return bool|object \harshaaliaschinna\Ping\Ping 
     */
    public function setId($Id);

    /**
     * Send Payload
     *
     * @param (int) $toId, (string) $payload
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function send($toId, $payload);

    /**
     * Make new connection
     *
     * @param (int) $u1, (int) $u2
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function new($u1, $u2=null);

    /**
     * Check if connected to user
     *
     * @param (int) $toId
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function exists($toId);

    /**
     * Retrive payload
     *
     * @param (int) $payload, (bool) $seen
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function receive($payload_id, $seen = false);

    /**
     * retrive all payloads
     *
     * @param (int) $conId, (bool) $seen, (string) $order, (int|null) $skip, (int|null) $take
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function receiveAll($conId, $seen = false, $order='ASC', $skip=null, $take=null);

    /**
     * Current users total connections
     *
     * @param (int|null) $userId
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function totalConnections($userId=null);

    /**
     * Count unread payloads in connection
     *
     * @param (int) $conId, (int|null) $userId
     * @return bool|int 
     */
    public function unreadCount($conId, $userId=null);

    /**
     * Make payload as received
     *
     * @param (int) $payload_id
     * @return bool
     */
    public function markAsSeen($payload_id);

    /**
     * Make payloads as received
     *
     * @param (int) $conId, (int|null) $user_id
     * @return bool 
     */
    public function markUnreadAsSeen($conId, $user_id = null);

    /**
     * Check if user has access to connection
     *
     * @param (int) $conId, (int|null) $userId
     * @return bool
     */
    public function hasAccess($conId, $userId = null);

    /**
     * Remove Payload in user scope
     *
     * @param (int) $payload_id, (int|null) $userId
     * @return bool
     */
    public function delete($payload_id, $userId=null);

    /**
     * Remove payload permanently
     *
     * @param (int) $payload_id
     * @return bool
     */
    public function hardDelete($payload_id);

    /**
     * Remove all payloads in a connection (OR) Reset connection
     *
     * @param (int) $conId
     * @return bool
     */
    public function hardDeleteAll($conId);

    /**
     * Remove connection and its payloads permanently
     *
     * @param (int) $conId
     * @return bool
     */
    public function hardDeleteConnection($conId);

    /**
     * Remove connection and its payloads by user
     *
     * @param (int) $userId
     * @return bool
     */
    public function hardDeleteConnectionByUserId($userId);

}

 ?>