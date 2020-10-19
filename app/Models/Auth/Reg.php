<?php

namespace App\Models\Auth;

class Reg
{
    /**
     * Проверяет наличие такого адреса в таблице с пользователями
     *
     * @param string $email
     *
     * @return bool
     */
    public static function existsEmail($email)
    {
        return static::exists('users', 'email', $email);
    }

    /**
     * Проверяет на существование login
     *
     * @param string $login
     *
     * @return bool
     */
    public static function existsLogin($login)
    {
        return static::exists('users', 'login', $login);
    }

    /**
     * Проверяет существование значения
     *
     * @param string $table
     * @param string $column
     * @param string $value
     *
     * @return bool
     */
    public static function exists($table, $column, $value)
    {
        $sql = "select exists(select * from {$table} where {$column} = :{$column})";

        $sth = db()->prepare($sql);
        $sth->execute([$column => $value]);

        return (bool)$sth->fetchColumn();
    }

    /**
     * Проверяет наличие email в таблице registration с учетом времени в 20 минут
     *
     * @param string $email
     * @param int    $operation
     *
     * @return mixed
     */
    public static function existsUserPreRegByEmail($email, $operation)
    {
        $sql = 'select created_at from registration 
          where email = :email and operation = :operation and created_at > date_sub(now(), interval 20 minute) 
        limit 1';

        $sth = db()->prepare($sql);
        $sth->execute([
            'email'     => $email,
            'operation' => $operation,
        ]);

        return $sth->fetch();
    }

    /**
     * Получить пользователя из предварительной таблицы регистрации
     *
     * @param string $token
     *
     * @param int    $operation
     *
     * @return mixed
     */
    public static function getUserPreRegByToken($token, $operation)
    {
        $sql = 'select id, email, token 
          from registration where token = :token and operation = :operation
        limit 1';

        $sth = db()->prepare($sql);
        $sth->execute([
            'token'     => $token,
            'operation' => $operation,
        ]);

        return $sth->fetch();
    }

    /**
     * Вставляет или обновляет запись в таблице registration
     *
     * @param string $email
     * @param string $token
     * @param int    $operation
     *
     * @return bool
     */
    public static function store($email, $token, $operation)
    {
        $sql = 'insert into registration (email, token, operation) 
          values (:email, :token, :operation) 
        on duplicate key update email = values (email), token = :token, operation = :operation, created_at = now()';
        $sth = db()->prepare($sql);

        return $sth->execute([
            'email'     => $email,
            'token'     => $token,
            'operation' => $operation,
        ]);
    }

    /**
     * Вставить нового пользователя в таблицу users
     *
     * @param array $data
     *
     * @return bool
     */
    public static function userSave(array $data)
    {
        $sql = sprintf(/** @lang text */
            'insert into users (%s) values (:%s)',
            implode(',', array_keys($data)),
            implode(',:', array_keys($data))
        );
        $sth = db()->prepare($sql);

        return $sth->execute($data);
    }

    /**
     * Удалить запись из таблицы регистрации
     *
     * @param int $id
     *
     * @return bool
     */
    public static function destroy($id)
    {
        $sql = 'delete from registration where id = ' . abs((int)$id);

        return (bool)db()->exec($sql);
    }

    /**
     * Получить user по email
     *
     * @param string $email
     *
     * @return array|false
     */
    public static function getUserForAuth($login)
    {
        $sql = 'select id, login, password, vip_time, stealth, HEX(uuid) as uuid 
          from users  
        where login = :login limit 1';

        $sth = db()->prepare($sql);
        $sth->execute([
            'login' => $login,
        ]);

        return $sth->fetch();
    }
}
