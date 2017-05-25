# Todo for floors

Version 1.0.0

- Make TOS, Terms, Privacy to main class
- Make centralized login session injection module
- dll

### Client code to extract encrypted data from floors
```php

public function __construct() 
{
    $token = Request::Get('token', null);
    $app = Request::Get('app', null);
    
    if ($token != null && $app != null) {
        Session::Get($this)->Login($app, $token, Auth::EXPIRED_1_MONTH);
    }
}

public function Login($app, $token)
{
    $key = hash('sha256', $app);
    $iv = substr(hash('sha256', 'uwmember'), 0, 16);
    $json = openssl_decrypt(base64_decode($token), 'AES-256-CBC', $key, 0, $iv);
    return $json;
}

public function GetLoginData($json)
{
    if ($json != '' || $json != false) {
        return (array)json_decode($json);
    } else {
        return false;
    }
}
```