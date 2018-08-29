<?php
class Session
{

    public static function Put($key, $value)
    {
        if (!empty($key) && !empty($value)) {
            $_SESSION[$key] = $value;
            return true;
        }
        return false;
    }

    public static function Add($key, $value)
    {
        if (!empty($key) && !empty($value)) {
            if (is_array($_SESSION[$key])) {
                $_SESSION[$key][] = $value;
            } elseif (isset($_SESSION[$key])) {
                $oldValue = $_SESSION[$key];
            }
            if (isset($_SESSION[$key])) {
                $_SESSION[$key][] = $oldValue;
            }
            $_SESSION[$key][] = $value;
            return true;
        }
        return false;
    }

    public
    static function Get($key)
    {
        if (!empty($key) && isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return '';
    }

    public
    static function Flash($key)
    {
        if (!empty($key) && isset($_SESSION[$key])) {
            $result = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $result;
        }
        return '';
    }
}