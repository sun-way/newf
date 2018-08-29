<?php
class Message
{
    const WARNING = 'alert-warning';    // Предупреждающее сообщение
    const DANGER = 'alert-danger';      // Сообщение об ошибке
    const SUCCESS = 'alert-success';    // Сообщение об успешном выполнении действия или подтверждающее сообщение
    const INFO = 'alert-info';          // Информационные сообщения
    protected $message;
    protected $type;
    protected $code;
    public function __construct($message, $type, $errorCode, $save = true)
    {
        $type = $this->checkType($type) ? $type : self::INFO;
        $this->message = $message;
        $this->type = $type;
        $this->code = $errorCode;
    }

    protected function checkType($type)
    {
        return in_array($type, [self::WARNING, self::DANGER, self::SUCCESS, self::INFO]);
    }

    public static function all()
    {
        $errorsList = Session::Flash('errors');
        if (is_array($errorsList)) {
            foreach ($errorsList as $error) {
                $errors[] = new Message($error['message'], $error['type'], $error['code'], false);
            }
        }
        return isset($errors) ? $errors : [];
    }

    public static function setCriticalErrorAndRedirect($message, $code, $type = self::DANGER)
    {
        $message = new Message($message, $type, $code);
        $message->save();
        Router::redirect(Router::$base_route);
    }

    public function save()
    {
        Session::Add(
            'errors',
            [
                'message' => $this->getMessage(),
                'type' => $this->getType(),
                'code' => $this->getCode()
            ]);
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCode()
    {
        return $this->code;
    }
}