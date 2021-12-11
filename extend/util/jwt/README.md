# JWT加密验证

> 简单的jwt加密验证扩展

## 使用方法

### HS256(HS384,HS512)加密

```php

$key = 'ThisIsKey';
$jwt =new \util\jwt\Jwt();
$token = $jwt->setAlg('HS256')
->setKey($key)
->setClaim()
->getToken();

```

### HS256(HS384,HS512)验证

```php
$token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJpc3MiLCJhdWQiOiJhdWQiLCJqdGkiOiI2YjkxNTE3YmZkMzM5YmI4ZDI5MmJiOGM1NmYzZDVkOSIsImlhdCI6MTYzNjM0MTQ3NywibmJmIjoxNjM2MzQxNDc3LCJleHAiOjE2MzYzNDg2NzcsInVpZCI6MX0.352KUpUo08S6ujeSCyVhwYgA9l5XMU9drremhFE5KKM';
$key = 'ThisIsKey';
$jwt =new \util\jwt\Jwt();
$result = $jwt->setAlg('HS256')->setKey($key)->checkToken($token);
if($result===true){
    $uid = $jwt->getUid();
}else{
    var_dump($jwt->getMessage());
}
```

### RS256(RS384,RS512)加密
```php
$private_key = 'ThisIsKey';
$jwt =new \util\jwt\Jwt();
$token = $jwt->setAlg('RS256')
->setPrivateKey($private_key)
->setClaim()
->getToken();
```
### RS256(RS384,RS512)验证
```php
$public_key = 'ThisIsKey';
$jwt =new \util\jwt\Jwt();
$token = $jwt->setAlg('RS256')
->setPublicKey($key)
->setClaim()
->getToken();
```

具体示例代码可参考Example.php文件内方法。