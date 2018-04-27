# r-alt

Auto load css

add css file and set file name to "themes/global.css"

---
index.php
```php
date_default_timezone_set('Asia/Hong_Kong');
ini_set("display_errors", "On");
error_reporting(E_ALL && ~E_WARNING);
setlocale(LC_ALL, 'en_US.UTF-8');

$loader = require_once("../composer/vendor/autoload.php");
$app = new App\App(__DIR__, $loader);
$app->run();
```
