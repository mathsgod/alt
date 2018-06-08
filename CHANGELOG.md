## 5.5.3
auto load system value
```php
public Testing extends App\Model{
    static $status; //it will auto load systemvalue : self::_sv("Testing/status");
}
```
also can get value from function

```php
$t=new Testing(1);
echo $t->status(); // equal=>  Testing::$status[$t->status];
```

---

## 5.5.2
sv updated
```php
public Testing extends App\Model{

    static $_status;

    public function __construct($id){
        parent::__construct($id);
        self::$_status=self::_sv("status");
    }
}
```

---

## 5.5.0
- translation fixed

- config.ini
module seqeuence

- build in datatable

```php
public function get(){
    $dt=$this->createDT([$this,"ds"]);
    $dt->add("ID","user_id");
    $dt->add("Username","username");
    $this->write($dt);
}

public function ds(){
    $r=$this->createResponse(App\User::Query());
    $r->add("user_id","user_id");
    $r->add("username","User()");
    return $r;
}
```

---

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