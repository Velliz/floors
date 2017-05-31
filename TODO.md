# Todo for Floors

**Backlog**
- update to puko framework version 1.1.0 [done]
- documentation and implementation user guide

**General features**
- add register feature [QA]
- add recovery account feature [pending] needs email account setup
- add select app to continue if sso parameter not set [QA]
- add authorization list to JSON user login data
- implement secure login key and signature for app data exchange
- provide API based authentication with JWT

**Managerial**
- add term of service
- add about privacy policy
- add about author page

**Operator features**
- add operator account settings [done]

**User features**
- add login logs notification
- add language settings for change language
- add user login history page
- add user authorization list page

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