<?php
class User extends BaseModel
{
    protected static $dbTableName = 'php_users';

    public function setUser($operation, $login, $password, $id = null, $role = 'admin')
    {
        $operationHint = self::find($id) ? 'обновлено' : 'добавлено';
        switch ($operation) {
            case 'update';
                if (!self::find($id)) {
                    $message = new Message(
                        'Пользователь не был обновлен - нет такого пользователя',
                        Message::WARNING,
                        404
                    );
                    $message->save();
                    return false;
                }
                $sql = "UPDATE php_users 
                        SET name = :login, password = :password, role = :role, updated_at = NOW() 
                        WHERE id = :id LIMIT 1";
                break;
            default:
                if (self::getItem($login)) {
                    $message = new Message(
                        'Пользователь не был добавлен - уже есть такой логин',
                        Message::WARNING,
                        400
                    );
                    $message->save();
                    return false;
                }
                $sql = "INSERT INTO php_users (name, password, role, created_at, updated_at) 
                        VALUES (:login, :password, :role, NOW(), NOW())";
                break;
        }
        $statement = self::getDB()->prepare($sql);
        $statement->bindParam('login', $login);
        $statement->bindParam('password', $this->getHash($password));
        $statement->bindParam('role', $role);
        if (!empty($id) && $operation === 'update') {
            $statement->bindParam('id', $id);
        }
        $result = $statement->execute();
        if ($result) {
            $message = new Message(
                "Пользователь успешно $operationHint",
                Message::SUCCESS,
                200
            );
        } else {
            $message = new Message(
                "Пользователь не был $operationHint",
                Message::WARNING,
                400
            );
        }
        $message->save();
        return $result;
    }

    public function checkForLogin($login, $password)
    {
        if (!$this->login($login, $password)) {
            $message = new Message(
                'Авторизация не удалась: не найден пользователь, неправильный логин или неправильный пароль',
                Message::WARNING,
                400
            );
            $message->save();
            return false;
        }
        return true;
    }

    protected function login($login, $password)
    {
        $user = !empty($login) && !empty($password) ? self::getItem($login) : null;
        /* Ищем пользователя по логину */
        if ($user !== null && $user['password'] === $this->getHash($password)) {
            Session::Put('user', $user);
            $this->user = $user;
            Session::Put('user_id', $this->user['id']);
            return true;
        }
        return false;
    }

    public function logout()
    {
        session_destroy();
        Router::redirect('login');
    }
}