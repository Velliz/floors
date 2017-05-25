# Todo for Floors

**Backlog**
- update to puko framework version 1.1.0 [progress]
- documentation and implementation user guide

**General features**
- add register feature [progress]
- add recovery account feature
- add select app to continue if sso parameter not set [progress]
- add authorization access to user login data

**Managerial**
- add term of service
- add about privacy policy
- add about author page

**Operator features**
- add operator account settings

**User features**
- add login logs

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