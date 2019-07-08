# write a keyvalue store server
- Accepts commands set,get,has,delete
- Write a client that is able to send the commands set,get,has,delete

# running the server
$ php ./Server.php

# example
```php
 $client = new Client("tcp://127.0.0.1:1337");
 echo $client->set('a', 12) . PHP_EOL;
 echo $client->get('a') . PHP_EOL;
 echo $client->has('a') . PHP_EOL;
 echo $client->delete('a') . PHP_EOL;
 echo $client->has('a') . PHP_EOL;
```