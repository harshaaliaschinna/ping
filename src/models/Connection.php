<?php
/**
 * Connection Model
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table='ping_connections';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable=[
    	'user_one',
    	'user_two',
    	'status'
    ];

    /**
     * Creation of new connection
     *
     * @param (int) $u1, (int) $u2
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public static function newConnection($u1, $u2)
    {
    	return self::create(['user_one' => $u1, 'user_two' => $u2, 'status' => 1]);
    }

    /**
     * Checking if connection exists between two users
     *
     * @param (object) $query, (int) $u1, (int) $u2
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public function scopeExistsBetween($query, $u1, $u2)
    {
    	return $query->where('user_one', $u1)->where('user_two', $u2);
    }

    /**
     * Check if user exists in current connection
     *
     * @param (object) $query, (int) $conId, (int) $u1
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public function scopeUserExists($query, $conId, $u1)
    {
    	return $query->where('id', $conId)->where(function ($query) use ($u1) {
                    $query->where('user_one', $u1)->orWhere('user_two', $u1);
                });
    }

    /**
     * Get query by user
     *
     * @param (object) $query, (int) $u1
     * @return Illuminate\Database\Eloquent\Model Instance
     */
    public function scopeByUser($query, $u1)
    {
    	return $query->where('user_one', $u1)->orWhere('user_two', $u1);
    }
}
