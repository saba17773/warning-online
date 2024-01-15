# Install
```json
{
  "require" : {
    "wattanar/sqlsrv" : "dev-master"
  }
}
```
# Usage
```php
use Wattanar\Sqlsrv;

$connection = Sqlsrv::connect($server, $username, $password, $database)
$rows = Sqlsrv::rows($connection, $query, array $params = null)
$hasRows = Sqlsrv::hasRows($connection, $query, array $params = null)
$query = Sqlsrv::query($connection, $query, array $params = null)
```
