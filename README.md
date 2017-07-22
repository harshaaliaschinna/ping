# Laravel Messaging or Chat or Conversation Package (Ping)
[![Ping Logo](http://i.imgur.com/zDu8x7s.png)]()
## Introduction
**Ping** provides a simple and easy Messaging or Chat or Conversation system to your Laravel Framework. It is easy to integrate and use. 
## Features
- One to One Conversation.
- Send a message without checking for conversation.
- Check if user has access to a conversation.
- Conversation's unread messages count (total & by user).
- Retriving messages with auto marking as seen.
- Marking all unread messages as seen (total & by user).
- Deleting messages from the participant side.
- Hard deletion (delete permanently) of messages and conversation.
- Message Encryption (*Coming soon!*).
- Group Conversation (*Coming soon!*).
## Installation
To get started with Ping, use Composer to add the package to your project:
```
composer require harshaaliaschinna/ping
```
## Configuration
After installing the Ping package, register the `Harshaaliaschinna\Ping\PingServiceProvider` in your `config/app.php` configuration file:
```php
'providers' => [
    // Other service providers...

    Harshaaliaschinna\Ping\PingServiceProvider::class,
],
```
You don't need to add the Ping to alias array. We already done that for you :blue_heart:.
## Migration
Run below command in your terminal to publish required migration files for this package.
```
php artisan vendor:publish --provider="Harshaaliaschinna\Ping\PingServiceProvider"
```
Finally run below command to execute migrations.
```
php artisan migrate
```
## Basic Usage
Example 1:
```php
// Ping::setId(from_id)->send(to_id, message);
Ping::setId(1)->send(2, "Hello, How are you?");
```
Example 2:
```php
Ping::setId(1);
Ping::send(2, "Hello, How are you?");
```
Example 3:
```php
namespace App\Controllers;

..
use Ping;

class Demo extends Controller {
    public function __construct() {
        // Ping once initialized it can be used anywhere without setting From Id.
        Ping::setId(Auth::Id());
    }
    
    public function sendMessage($toId, $message) {
        ..
        ..
        Ping::send($toId, $message);
        ..
    }
    
    public function retriveMessage($Id) {
        ..
        $markAsSeen = true; // Bool
        $message = Ping::recieve($Id, $markAsSeen);
        ..
    }
    ..
    ..
    ..
}
```
## API Reference
| Method | V0.1 |
| ------ | ------ |
| [setId()](#setid) | :heavy_check_mark: |
| [send()](#send) | :heavy_check_mark: |
| [new()](#new) | :heavy_check_mark: |
| [exists()](#exists) | :heavy_check_mark: |
| [receive()](#receive) | :heavy_check_mark: |
| [receiveAll()](#receiveall) | :heavy_check_mark: |
| [totalConnections()](#totalconnections) | :heavy_check_mark: |
| [unreadCount()](#unreadcount) | :heavy_check_mark: |
| [markAsSeen()](#markasseen) | :heavy_check_mark: |
| [markUnreadAsSeen()](#markunreadasseen) | :heavy_check_mark: |
| [hasAccess()](#hasaccess) | :heavy_check_mark: |
| [delete()](#delete) | :heavy_check_mark: |
| [hardDelete()](#harddelete) | :heavy_check_mark: |
| [hardDeleteAll()](#harddeleteall) | :heavy_check_mark: |
| [hardDeleteConnection()](#harddeleteconnection) | :heavy_check_mark: |
| [hardDeleteConnectionByUserId()](#harddeleteconnectionbyuserid) | :heavy_check_mark: |

****Note:*** Please note that **connection** and **conversation** both are same. As the package name itself refers to a Networking scenario, these words were used ***just for fun!*** :stuck_out_tongue: .
### setId
This method sets the base Id known as **base_user**. From which the requests can be made.
```php 
object setId( int $id )
```
**Parameters:**  
***Id:*** Unique Id from which further requests can be performed.

**Return Values:**  
Returns Ping object. **FALSE** on errors.
### send
Sends the message to other user. This method creates automatically a new connection if there is no connection between users. If there exists a connection already it will use it to send message.
```php
object send( int $to_id, string $message)
```
**Parameters:**  
***to_id:*** Unique Id to which the message to be sent.  
***message:*** The message field.
 
**Return Values:**  
Returns object on Success. **FALSE** on errors.  
object returned containes newly sent message id `->id`. It also containes connection id `->connection_id` and few others.  
### new
Creates new connection between users.
```php
object new( int $user_one[, int $user_two = null])
```
**Parameters:**  
***user_one:*** Unique Id.  
***user_two:*** If this field is not set, Ping will create a connection between **base_user**(user set through `setId()` method) and user_one. Else it will create a connection between user_one and user_two.
 
**Return Values:**  
Returns object on Success. **FALSE** on errors.  
returned object containes Connection id `->id`  
### exists
Checks whether a connection exists between **base_user** and provided user.
```php
bool exists( int $user_two)
```
**Parameters:**  
***user_two:*** Unique Id. This method is dependent on `setId()`
 
**Return Values:**  
Returns object on Success. **FALSE** on errors.
### receive
Retrives a message using message_id.
```php
object receive( int $message_id[, $seen = false])
```
**Parameters:**  
***message_id:*** Message id should be passed to retrive message.  
***seen:*** Mark this message as seen.  
 
**Return Values:**  
Returns object on Success. **FALSE** on errors or not found.
### receiveAll
Retrives all messages that are present in a connection or conversation using `connection_id`
```php
object receiveAll( int $connection_id[[[[, bool $seen = false], string $order = 'ASC'], int $skip=null], int $take=null])
```
**Parameters:**  
***connection_id:*** Connection id.  
***seen:*** Mark unread messages as seen by `base_user`. Default it will be as `false`.  

| Value | Result |  
| --- | --- |  
| true | Mark as seen |  
| false | Ignore |  

***order:*** Order by ascending order or descending order using messages timestamp.  

| Value | Result |  
| --- | --- |  
| ASC | Ascending order |  
| DESC | Descending order |  

***skip:*** number of messages to skip.  
***take:*** number of messages to retrive.  
 
**Return Values:**  
Returns object on Success. **FALSE** on errors or not found.
### totalConnections
Retrives all connections that are linked to `base_user` or provided user.
```php
object totalConnections([int $user_id=null])
```
**Parameters:**  
***user_id:*** If `user_id` is passed, Ping will retrive all connections based on provided `user_id`. Else it will use `base_user` as `user_id` and retrives all connections.
 
**Return Values:**  
Returns object on Success. **FALSE** on errors or not found.
### unreadCount
Retrives unread messages count based on `connection_id`.
```php
int unreadCount( int $connection_id[, int $user_id=null])
```
**Parameters:**  
***connection_id:*** If `user_id` is passed, Ping will retrive all unread messages count based on provided `user_id`. Else it will use `base_user` as `user_id` and retrives the count.  
***user_id:*** If `user_id` is passed, Ping will set it as `base_user` and retrives unread message count. Else it will use `base_user` as `user_id` and retrives it.  
 
**Return Values:**  
Returns integer on Success. **FALSE** on errors or not found.
### markAsSeen
Mark a message as seen.
```php
bool markAsSeen( int $message_id)
```
**Parameters:**  
***message_id:*** Message Id should be passed.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### markUnreadAsSeen
Mark all unread messages as seen using `connection_id`. If `user_id` is passed it will mark that user specific received messages as seen. Else it will mark all messages as seen in that particular connection.
```php
bool markUnreadAsSeen( int $conection_id[, int $user_id = null])
```
**Parameters:**  
***connection_id:*** Specific `connection_id` should be passed.  
***user_id:*** If `user_id` is passed it will mark that users received messages as seen.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### hasAccess
Checks whether a user has access to specific connection or not.
```php
bool hasAccess( int $connection_id[, int $user_id = null])
```
**Parameters:**  
***connection_id:*** Specific `connection_id` should be passed.  
***user_id:*** If `user_id` is passed, Ping will check whether the given `user_id` has access to connection or not. Else it will use `base_user` as `user_id` and checks for access.
 
**Return Values:**  
Returns `true` if user has access. **FALSE** if they don't have access.
### delete
To delete a message from one user side.
```php
bool delete( int $message_id[, int $user_id=null])
```
**Parameters:**  
***message_id:*** Specific `message_id` should be passed.  
***user_id:*** If `user_id` is passed, Ping will remove message from that user side. Else it will use `base_user` as `user_id` and removes from that user side.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### hardDelete
Message will be deleted permanently from database. Any user present in that connection cannot retrive this message again.
```php
bool hardDelete( int $message_id)
```
**Parameters:**  
***message_id:*** Specific `message_id` should be passed.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### hardDeleteAll
This will delete all the messages permanently in a connection. In other words it will reset that connection.
```php
bool hardDeleteAll( int $connection_id)
```
**Parameters:**  
***connection_id:*** Specific `connection_id` should be passed.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### hardDeleteConnection
This will delete a connection & messages present in it permanently.
```php
bool hardDeleteConnection( int $connection_id)
```
**Parameters:**  
***connection_id:*** Specific `connection_id` should be passed.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.
### hardDeleteConnectionByUserId
This is same as `hardDeleteConnection()` but this method will accept `user_id` as parameter. Connection & messages that exists between `base_user` and provider `user_id` will be deleted permanently.
```php
bool hardDeleteConnectionByUserId( int $user_id)
```
**Parameters:**  
***user_id:*** Specific `user_id` should be passed.
 
**Return Values:**  
Returns `true` on Success. **FALSE** on errors or not found.