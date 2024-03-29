<?php
namespace App;

abstract class Model extends \R\ORM\Model
{
    public static $_db;
    public static $_app;
    public function __construct($id = null)
    {
        parent::__construct($id);
        $c = new \ReflectionClass(get_class($this));
        if ($static = $c->getStaticProperties()) {
            foreach ($static as $k => $v) {
                if ($r = self::_sv("$c->name/$k")) {
                    $c->setStaticPropertyValue($k, $r);
                }
            }
        }
    }

    public function id()
    {
        $key = $this->_key();
        return $this->$key;
    }

    public static function __db()
    {
        return self::$_db;
    }

    public function _db()
    {
        return $this->db;
    }

    public function _app()
    {
        return \App::_();
    }

    public function bind($rs)
    {
        foreach (get_object_vars($this) as $key => $val) {
            if (is_object($rs)) {
                if (isset($rs->$key)) {
                    if ($key[0] != "_") {
                        if (is_array($rs->$key)) {
                            $this->$key = implode(",", $rs->$key);
                        } else {
                            $this->$key = $rs->$key;
                        }
                    }
                }
            } else {
                if (array_key_exists($key, $rs)) {
                    if ($key[0] != "_") {
                        if (is_array($rs[$key])) {
                            $this->$key = implode(",", array_filter($rs[$key], function ($o) {
                                return $o !== "";
                            }));
                        } else {
                            $this->$key = $rs[$key];
                        }
                    }
                }
            }
        }
        return $this;
    }

    public function uri($a = null)
    {
        $reflect = new \ReflectionClass($this);
        $uri = $reflect->getShortName();
        if ($this->id()) {
            $uri .= "/" . $this->id();
        }
        if (isset($a)) {
            $uri .= "/" . $a;
        }
        return $uri;
    }

    public function canDelete()
    {
        if (!ACL::Allow(get_class($this), "D")) {
            return false;
        }
        return true;
    }

    public function canRead()
    {
        if (!ACL::Allow(get_class($this), "R")) {
            return false;
        }
        return true;
    }

    public function canUpdate()
    {
        if (!ACL::Allow(get_class($this), "U")) {
            return false;
        }
        return true;
    }

    public function delete()
    {
        EventLog::LogDelete($this);
        return parent::delete();
    }

    public function save()
    {
        $new_record = !$this->id();
        if ($new_record) { // Insert
            $action = "C";

            if (property_exists($this, "created_by")) {
                $this->created_by = \App::UserID();
            }
            if (property_exists($this, "created_time")) {
                $this->created_time = date("Y-m-d H:i:s");
            }
        } else { // Update
            $action = "U";
            if (property_exists($this, "updated_by")) {
                $this->updated_by = \App::UserID();
            }
            if (property_exists($this, "updated_time")) {
                $this->updated_time = date("Y-m-d H:i:s");
            }
        }

        if ($action == "C") {
            $result = parent::save();
            EventLog::Log($this, "C");
        } else {
            EventLog::Log($this, "U");
            $result = parent::save();
        }
        return $this;
    }

    public function CreatedBy()
    {
        return new User($this->created_by);
    }

    public function UpdatedBy()
    {
        return new User($this->updated_by);
    }

    public function User()
    {
        return new User($this->user_id);
    }

    public function __call($function, $args)
    {
        $class = get_class($this);

        //check const
        $c = new \ReflectionClass($class);
        if ($const = $c->getConstants()) {

            $decamlize = function ($string) {
                return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
            };
            $field = $decamlize($function);

            if (array_key_exists(strtoupper($field), $const)) {
                return $const[strtoupper($field)][$this->$field];
            }

            if (array_key_exists($function, $const)) {
                return $const[$function][$this->$field];
            }
        }

        if ($static = $c->getStaticProperties()) {
            $decamlize = function ($string) {
                return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
            };
            $field = $decamlize($function);

            $f = $field;
            if (array_key_exists($f, $static)) {
                return $static[$f][$this->$field];
            }

            $f = "_" . $field;

            if (array_key_exists($f, $static)) {
                return $static[$f][$this->$field];
            }

            $f = "_" . $function;

            if (array_key_exists($f, $static)) {
                return $static[$f][$this->$field];
            }
        }

        $f = "_" . $function;

        if (property_exists($class, $f)) {
            $s = $this->$f;
            return $s[$this->{strtolower($function)}];
        }
        return parent::__call($function, $args);
    }

    public static function _sv($name)
    {
        return self::$_app->sv($name);
    }

    public function _size($class, $where = [])
    {
        $key = $this->_key();
        $w = [];
        $w[] = ["$key=?", $this->id()];
        foreach ($where as $w1) {
            $w[] = $w1;
        }
        return forward_static_call([$class, "Count"], $w);
    }

    public function _scalar($class, $query)
    {
        $key = $this->_key();
        $w = [];
        $w[] = ["$key=?", $this->id()];
        return forward_static_call([$class, "Scalar"], $query, $w);
    }
}
