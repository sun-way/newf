<?php
class Logger extends BaseModel
{
    //статические переменные
    public static $PATH = 'app/storage/'; // путь к файлу
    protected static $loggers = array();
    protected $name;
    protected $file;
    protected $fp;
    public function __construct($name = null, $fileName = null)
    {
        parent::__construct();
        $this->name = is_null($name) ? 'actions' : $name;
        $this->file = is_null($fileName) ? self::$PATH . '/' . $this->name . '.log' : self::$PATH . '/' . $fileName;
        $this->open();
    }
    public function open()
    {
        if (self::$PATH == null) {
            return;
        }
        $this->fp = fopen($this->file, 'a+');
    }

    public static function getLogContents($loggerName)
    {
        $logger = self::getLogger($loggerName);
        return explode("\n", fread($logger->fp, filesize($logger->file)));
    }

    public static function getLogger($name = 'root', $file = null)
    {
        if (!isset(self::$loggers[$name])) {
            self::$loggers[$name] = new Logger($name, $file);
        }
        return self::$loggers[$name];
    }
    public function log($message)
    {
        if (!is_string($message)) {
            $this->logPrint($message);
            return;
        }
        $log = '';
        $log .= '[' . date('D d.m.Y H:i:s', time()) . '] ';
        if (func_num_args() > 1) {
            $params = func_get_args();
            $message = call_user_func_array('sprintf', $params);
        }
        $log .= $message;
        $log .= "\n";
        $this->_write($log);
    }
    public function logPrint($obj)
    {
        ob_start();
        print_r($obj);
        $ob = ob_get_clean();
        $this->log($ob);
    }
    protected function _write($string)
    {
        fwrite($this->fp, $string);
    }
    public function __destruct()
    {
        fclose($this->fp);
    }
}