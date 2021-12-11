Multi Agent Library
=====
A php package that enable the user to handle multiple agent (devices) at the same time. This package allows you to link
any model with many devices, and provide a kind of authentication for the functionality this device can use.


Installation
------------

Install using composer:

```bash
composer require aldeebhasan/multi-agents
```
After finishing the installation process you need to migrate the package tables to the database.
run the following command
```php
php artisan migrate
```


Basic Usage
-----------

### Devices

To identify that any specific model could be linked with various devices, you can use the `HasMultiAgent` trait.

```php
class Client 
{
    use HasMultiAgent;
    ...
```

To create a new device you can use the `register` function.
<b>Note:</b> `uuid` should be unique for each device

```php
$device = Device::register('uuid');
```

Now, you can use the following functions to link/unlink the device with the `Client` model

```php
use Aldeebhasan\MultiAgents\Models\Device

$device = Device::register('uuid');
$client = new Client();

// Link the device with the model
$client->linkDevices($device);

//you can also pass a list of device objects
$client->linkDevices([$device,$device,....]);

// Unlink the device from the model
$client->unlinkDevices($device);

//you can also pass a list of device objects
$client->unlinkDevices([$device,$device,....]);

//unlink all the devices related to specific model
$client->unlinkAll();
```

To retrieve all te devices lined to specific model

```php
$client->devices
```
To obtain the owner of the device use the following.

<b>Note:</b> The owner could be any model use the `HasMultiAgent` trait, and the device already linked to it.

```php
$device->owner
```

Finally, thanks for the [Agent](https://github.com/jenssegers/agent) package author, where you can perform a list of
check functions over the current device agent. yYou can check all the function related to the device egent
from [here](https://github.com/jenssegers/agent).

Now, to retrieve the device agent object you can use.

```php
$device = $client->devices->first();
$device->agent
```

### Middleware
After registering any device, the device object will have a `token` attribute.
This attribute should be sent at the `Header` or as `POST`or `GET` parameter with the key `Device-Token` to authenticate this registered device.

To use the device authentication middleware, you need to add it first to the `app\Http\Kernel.php` under `$routeMiddleware` as follow:
```php
'auth.device' => \Aldeebhasan\MultiAgents\Middleware\DeviceAuthenticated::class,
```
Next, you have to attach the middleware to your rout or inside your controller.
```php
//in the routes
Route::middleware(['auth.device'])->group(function () {
    Route::get('/device-info', function () {
        return getCurrentDevice();
    });
});
//in the controller
 public function __construct()
    {
        $this->middleware('auth.device';
    }
```
The package contains `getCurrentDevice()` helper function to retrieve the device that match the passed `Device-Token`

Furthermore, if you want to  authenticate only the devices linked with a specific model, you can pass the Model class name to the middleware.
```php
// if the passed token match with any of the token
// registered for the `Client` model it wil allow
// it to pass to the inner route.
// Otherwise, DeviceAuthenticationException Exception will be thrown
Route::middleware(['auth.device:'.Client::class])->group(function () {
    Route::get('/device-info', function () {
        return getCurrentDevice();
    });
});
```
### Exceptions
Two kinds of exceptions could be thrown from the package DeviceAuthenticated middleware:

- **DeviceAuthenticationException**: when no device match the passed token.
- **DeviceExpiredTokenException**: when the device token is expired and the device need to be registered again.

### Settings

Each device could have a list of setting related to it.

You can initialize a list of settings you want to link with a specific set of devices, and attach them to the devices.

```php
use Aldeebhasan\MultiAgents\Models\Setting

$setting1 = Setting::register('key1');
$setting2 = Setting::register('key2');
//add settings with its value to specific device
$device->addSettings([
            'key1'=>'value1',
            'key2'=>'value2',
        ]);
//remove setting/s from a specific device
//single setting
$device->deleteSettings('key1'); 
//multiple settings
$device->deleteSettings(['key1','key2']); 

//to perform the two operations togather and sync the settings with their value with specific device
$device2->syncSettings([
         'key1'=>'value1',
        ]);
//the sync function will unlink all the settings doesn't much the keys,
// and link/change the  value of the settings that are appeared in the  provided list

```

Finally, to retrive all the setting related to specific device

```php
$device->getSettings(); 
//output: [
//    'key1'=>'value1',
//    'key2'=>'value2',
//  ]

```


## License

Laravel Multi Agent package is licensed under [The MIT License (MIT)](https://github.com/git/git-scm.com/blob/main/MIT-LICENSE.txt).

## Security contact information

To report a security vulnerability, contact directly to the developer contact email [Here](mailto:aldeeb.91@gmail.com).
