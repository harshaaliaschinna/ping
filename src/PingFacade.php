<?php 

/**
 * Ping Facade
 *
 * @author Harsha vardhan (harshaaliaschinna@gmail.com)
 *
 * @version 0.1
 */
namespace Harshaaliaschinna\Ping;

use Illuminate\Support\Facades\Facade;

class PingFacade extends Facade
{
	
	/**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 
    	return 'Ping';
    }
}