## 5.4.11
config.ini

default allow user full control module
```
user_default_acl=1
```


## 5.4.6
```php
public Testing_index extends ALT\Page{
    function get(){
        $this->alert->info("info");
        $this->alert->danger("danger");
        $this->alert->warning("warning");
        $this->alert->success("success");
    }
}
```

---
## 5.3.0
- multi menu group
- get current user
```php
$this->app->user;
```

---
## 5.2.0
- allow check login information

```php
new App\App(__DIR__,$loader);
App\User::Login($username,$password);
```

---
## 5.1.0
- Rewrite front translate function

---
## 4.15.0
```php
$e=$this->createE();
$e->add("CKEDITOR")->ckeditor("field");
$e->add("ROXY FILEMAN")->roxyfileman("field");
```
---
## 4.14.0
- javascript autoload function

---

## 4.11.0
- view as function, sugguested by eric
administrator can use view as mode to view from another user

---
## 4.10.1
- fix document root path

---
## 4.10.0

---
- rt selectable
```php
$rt=$this->createRT();
$rt->attr("selectable",true);
``` 

---
## 4.8.1