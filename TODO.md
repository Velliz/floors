# Todo for Floors

**Backlog**
- documentation and implementation user guide

**General features**
- add login page designer
- add recovery account feature [pending] **need email account setup**
- add authorization list to JSON user login data
- implement secure login key and signature for app data exchange
- provide API based authentication with JWT
- Single Sign Out mechanism

**Managerial**
- add term of service
- add about privacy policy
- add about author page

**Operator features**
> all achived

**User features**
- add login logs notification
- add language settings for change language [progress]

**API**
- update user profile
- update user linked account
- re-login if token expired

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