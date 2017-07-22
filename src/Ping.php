<?php

/**
 * Class Ping
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

use Harshaaliaschinna\Ping\Payload;
use Harshaaliaschinna\Ping\Connection;
use Harshaaliaschinna\Ping\PingContract;

class Ping implements PingContract
{
    /**
     * Base User Id
     *
     * @var int
     */
    protected $userId;

    /**
     * Serialize in ascending order
     *
     * @param (int) $user1, (int) $user2
     * @return array
     */
    protected function serializeUser($user1, $user2)
    {
        $user = [];
        $user['one'] = ($user1 < $user2) ? $user1 : $user2;
        $user['two'] = ($user1 < $user2) ? $user2 : $user1;

        return $user;
    }

    public function test()
    {
        return "test";
    }

    /**
     * Set Id
     *
     * @param (int) $Id
     * @return bool|object \harshaaliaschinna\Ping\Ping 
     */
    public function setId($Id)
    {
        if(!is_null($Id)){
            $this->userId=$Id;
            return $this;
        }
        return false;
    }

    /**
     * Send Payload
     *
     * @param (int) $toId, (string) $payload
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function send($toId, $payload)
    {
        if($con=$this->exists($toId))
        {
            $rtData=$this->makePacket($con['id'], $this->userId, $payload);
        } else {
            if($con=$this->new($this->userId, $toId)){
                $rtData=$this->makePacket($con['id'], $this->userId, $payload);
            } else {
                return false;
            }
        }
        if($rtData=Payload::create($rtData))
        {
            return $rtData;
        }
        return false;
    }

    /**
     * Make insertion structure
     *
     * @param (int) $conId, (int) $userId, (string) $payload
     * @return array 
     */
    protected function makePacket($conId, $userId, $payload)
    {
        return Payload::makeSkeleton($conId, $userId, $payload);
    }

    /**
     * Make new connection
     *
     * @param (int) $u1, (int) $u2
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function new($u1, $u2=null)
    {
        if(is_null($u2))
        {
            $u2=$this->userId;
        }
        $user=$this->serializeUser($u1, $u2);
        if($con=Connection::newConnection($user['one'], $user['two']))
        {
            return $con;
        }
        return false;
    }

    /**
     * Check if connected to user
     *
     * @param (int) $toId
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function exists($toId)
    {
        $user = $this->serializeUser($this->userId, $toId);
        if($con=Connection::existsBetween($user['one'], $user['two'])->first())
        {
            return $con;
        }
        return false;
    }

    /**
     * Retrive payload
     *
     * @param (int) $payload, (bool) $seen
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function receive($payload_id, $seen = false)
    {
        if($payload=$this->payloadExists($payload_id))
        {
            if($seen&&$payload['seen']==0)
            {
                $this->markAsSeen($payload['id']);
                $payload['seen']=1;
            }
            return $payload;
        }
        return false;
    }

    /**
     * Check for payload
     *
     * @param (int) $id
     * @return object \Illuminate\Database\Eloquent\Model 
     */
    protected function payloadExists($id)
    {
        return Payload::where('id', $id)->first();
    }

    /**
     * Check for connection
     *
     * @param (int) $conId
     * @return object \Illuminate\Database\Eloquent\Model 
     */
    protected function connectionExists($conId)
    {
        return Connection::where('id', $conId)->first();
    }

    /**
     * retrive all payloads
     *
     * @param (int) $conId, (bool) $seen, (string) $order, (int|null) $skip, (int|null) $take
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function receiveAll($conId, $seen = false, $order='ASC', $skip=null, $take=null)
    {
        if($con=$this->connectionExists($conId))
        {
            if($seen)
            {
                $this->markUnreadAsSeen($conId, $this->userId);
            }
            $rtData = Payload::byConnection($conId, $order);
            if(!is_null($skip))
            {
                $rtData->skip($skip);
            }
            if(!is_null($take))
            {
                $rtData->take($take);
            }
            $rtData=$rtData->get();
            return $rtData;
        }
        return false;
    }

    /**
     * Current users total connections
     *
     * @param (int|null) $userId
     * @return bool|object \Illuminate\Database\Eloquent\Model 
     */
    public function totalConnections($userId=null)
    {
        if(is_null($userId))
        {
            $userId=$this->userId;
        }

        if($cons=Connection::byUser($userId))
        {
            return $cons->get();
        }
        return false;
    }

    /**
     * Count unread payloads in connection
     *
     * @param (int) $conId, (int|null) $userId
     * @return bool|int 
     */
    public function unreadCount($conId, $userId=null)
    {
        if(is_null($userId))
        {
            $userId=$this->userId;
        }

        if($con=$this->connectionExists($conId))
        {
            $payloads=Payload::byUnseen($conId, $userId);
            $count=$payloads->count();
            return $count;
        }
        return false;
    }

    /**
     * Make payload as received
     *
     * @param (int) $payload_id
     * @return bool
     */
    public function markAsSeen($payload_id)
    {
        if(Payload::where('id', $payload_id)->update(['seen' => 1]))
        {
            return true;
        }
        return false;
    }

    /**
     * Make payloads as received
     *
     * @param (int) $conId, (int|null) $user_id
     * @return bool 
     */
    public function markUnreadAsSeen($conId, $user_id = null)
    {
        if($payloads = Payload::byUnseen($conId, $user_id)->get())
        {
            foreach ($payloads as $payload) {
                $payload->update(['seen' => 1]);
            }
            return true;
        }
        return false;
    }

    /**
     * Check if user has access to connection
     *
     * @param (int) $conId, (int|null) $userId
     * @return bool
     */
    public function hasAccess($conId, $userId = null)
    {
        if(is_null($userId))
        {
            $userId = $this->userId;
        }
        if(Connection::userExists($conId, $userId)->exists())
        {
            return true;
        }
        return false;
    }

    /**
     * Remove Payload in user scope
     *
     * @param (int) $payload_id, (int|null) $userId
     * @return bool
     */
    public function delete($payload_id, $userId=null)
    {
        if(is_null($userId))
        {
            $userId=$this->userId;
        }

        if($payload=$this->payloadExists($payload_id))
        {
            Payload::deletePayload($payload, $userId);
            return true;
        }
        return false;
    }

    /**
     * Remove payload permanently
     *
     * @param (int) $payload_id
     * @return bool
     */
    public function hardDelete($payload_id)
    {
        if($payload=$this->payloadExists($payload_id))
        {
            $payload->delete();
            return true;
        }
        return false;
    }

    /**
     * Remove all payloads in a connection (OR) Reset connection
     *
     * @param (int) $conId
     * @return bool
     */
    public function hardDeleteAll($conId)
    {
        if($payloads=Payload::byConnection($conId))
        {
            $payloads->delete();
            return true;
        }
        return false;
    }

    /**
     * Remove connection and its payloads permanently
     *
     * @param (int) $conId
     * @return bool
     */
    public function hardDeleteConnection($conId)
    {
        if($con=$this->connectionExists($conId))
        {
            if($this->hardDeleteAll($conId))
            {
                $con->delete();
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * Remove connection and its payloads by user
     *
     * @param (int) $userId
     * @return bool
     */
    public function hardDeleteConnectionByUserId($userId)
    {
        if($con=$this->exists($userId))
        {
            if($this->hardDeleteConnection($con['id']))
            {
                return true;
            }
            return false;
        }
        return false;
    }

}
