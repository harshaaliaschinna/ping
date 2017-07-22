<?php
/**
 * Payload Model
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

use Illuminate\Database\Eloquent\Model;

class Payload extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='ping_payloads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
    	'message',
    	'seen',
    	'sender_deleted',
    	'receiver_deleted',
    	'sender_id',
    	'connection_id',
    ];

    /**
     * Make insertion structure
     *
     * @param (int) $conId, (int) $userId, (string) $payload
     * @return array
     */
    public static function makeSkeleton($conId, $userId, $payload)
    {
        return [
            'message'   =>  $payload,
            'sender_id'   =>  $userId,
            'connection_id' => $conId,
            'seen'  =>  0,
            'sender_deleted'    =>  0,
            'receiver_deleted'    =>  0,
        ];
    }

    /**
     * Get query by connection
     *
     * @param (object) $query, (int) $conId, (string) $order
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public function scopeByConnection($query, $conId, $order='DESC')
    {
    	return $query->orderBy('created_at', $order)->where('connection_id', $conId);
    }

    /**
     * Get query by unseen
     *
     * @param (object) $query, (int) $conId, (int) $userId
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public function scopeByUnseen($query, $conId, $userId=null)
    {
    	if(!is_null($userId)){
	    	return $query->where('connection_id', $conId)->where('seen', 0)->whereNotIn('sender_id', [$userId]);
		}
		return $query->where('connection_id', $conId)->where('seen', 0);
    }

    /**
     * Delete payload (User scope)
     *
     * @param (object) $payload, (int) $userId
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public static function deletePayload($payload, $userId)
    {
    	if($payload->sender_id==$userId)
    	{
    		$payload->sender_deleted=1;
    	} else {
    		$payload->receiver_deleted=1;
    	}
    	return $payload->save();
    }
}
