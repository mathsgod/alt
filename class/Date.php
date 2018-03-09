<?php

class Date extends DateTime {
    public function __get($name) {
        switch ($name) {
            case "year";
                return $this->format("Y");
                break;
            case "month":
                return $this->format("m");
                break;
            case "day":
                return $this->format("d");
                break;
            case "hour":
                return $this->format("H");
                break;
            case "minute":
                return $this->format("i");
                break;
            case "second":
                return $this->format("s");
                break;
        }
    }

    public function __set($name, $value) {
        switch ($name) {
            case "year":
                $this->setDate($value, $this->month, $this->day);
                break;
            case "month":
                $this->setDate($this->year, $value, $this->day);
                break;
            case "day":
                $this->setDate($this->year, $this->month, $value);
                break;
            case "hour":
                $this->setTime($value, $this->minute, $this->second);
                break;
            case "minute":
                $this->setTime($this->hour, $value, $this->second);
                break;
            case "second":
                $this->setTime($this->hour, $this->minute, $value);
                break;
        }
    }

    public function addYears($years) {
        $this->add(date_interval_create_from_date_string("$years years"));
    }

    public function addMonths($months) {
        $this->add(date_interval_create_from_date_string("$months months"));
    }

    public function addDays($days) {
        $this->add(date_interval_create_from_date_string("$days days"));
    }

    public function addHours($hours) {
        $this->add(date_interval_create_from_date_string("$hours hours"));
    }

    public function addMinutes($minutes) {
        $this->add(date_interval_create_from_date_string("$minutes minutes"));
    }

    public function addSeconds($seconds) {
        $this->add(date_interval_create_from_date_string("$seconds seconds"));
    }

    public function isBefore($another) {
        return $another > $this->format("Y-m-d");
    }

    public function isAfter($another) {
        return $another < $this->format("Y-m-d");
    }

    public function isBetween($start, $end) {
        return $this->format("Y-m-d") >= $start && $this->format("Y-m-d") <= $end;
    }
}

?>