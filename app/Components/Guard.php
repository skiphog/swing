<?php

namespace App\Components;

use App\Models\Users\Auth;
use App\Models\Users\User;

/**
 * Class Auth
 *
 * @package App\Components
 */
class Guard
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var \System\Http\Request
     */
    protected $request;

    /**
     * @var string
     */
    protected $domain;

    /**
     * @var bool
     */
    protected $secure;

    public function __construct()
    {
        $this->db = db();
        $this->request = request();
        $this->domain = config('domain');
        $this->secure = config('secure');
    }

    /**
     * @return Auth
     */
    public static function init()
    {
        if (isset($_SESSION['id'], $_SESSION['password'])) {
            return Auth::init($_SESSION['id'], $_SESSION['password']) ?: new Auth();
        }

        if (isset($_COOKIE['id'], $_COOKIE['password'])) {
            $user = Auth::init($_COOKIE['id'], $_COOKIE['password']);

            if (!empty($user)) {
                $_SESSION['id'] = $user->id;
                $_SESSION['password'] = $user->password;
                $_SESSION['login'] = $user->login;

                return $user;
            }
        }

        return new Auth();
    }

    public static function login(array $user)
    {
        return (new static())
            ->enter($user);
    }

    /**
     * Выход с сайта
     */
    public static function logout()
    {
        unset($_SESSION['id'], $_SESSION['login'], $_SESSION['password']);

        $time = time() - 3600;
        $domain = config('domain');

        foreach (['id', 'login', 'password'] as $value) {
            setcookie($value, '', $time, '/', $domain);
        }
    }

    /**
     * Процедура входа с проверкой на Дубли
     *
     * @param array $user [id,login,password, stealth, vip_time, uuid]
     *
     * @return bool
     */
    public function enter($user)
    {
        foreach (['id', 'login', 'password'] as $item) {
            $_SESSION[$item] = $user[$item];
            setcookie($item, $user[$item], 0x7FFFFFFF, '/', $this->domain, $this->secure, true);
        }

        if (!($user['stealth'] && strtotime($user['vip_time']) - $_SERVER['REQUEST_TIME'] >= 0)) {
            $this->db->exec('update users_timestamps set last_view = NOW(), ip = ' . $this->request->clientIp2long() . ' where id = ' . (int)$user['id']);
        }

        cache()->delete('online_users');

        if ($user['uuid'] === $ses_uuid = $this->request->cookie('SSKSESID')) {
            return true;
        }

        if (null === $ses_uuid || !preg_match('~^[a-f0-9]{32}$~i', $ses_uuid) || !$this->existsUuid($ses_uuid)) {
            return setcookie('SSKSESID', $user['uuid'], 0x7FFFFFFF, '/', $this->domain);
        }

        return $this->setUuid($user, $ses_uuid);
    }

    protected function setUuid($user, $ses_uuid)
    {
        if ($user['uuid'] < $ses_uuid) {
            $min_uuid = $user['uuid'];
            $max_uuid = $ses_uuid;
            setcookie('SSKSESID', $min_uuid, 0x7FFFFFFF, '/', $this->domain);
        } else {
            $min_uuid = $ses_uuid;
            $max_uuid = $user['uuid'];
        }

        return $this->notificate($user, $min_uuid, $max_uuid);
    }

    /**
     * @param array  $user
     * @param string $min_uuid
     * @param string $max_uuid
     *
     * @return bool
     */
    protected function notificate($user, $min_uuid, $max_uuid)
    {
        $moderators = User::moderatorIds();
        $txt = render('notifications/moder_double', compact('user'));

        try {
            $this->db->beginTransaction();

            $this->db->exec("update users set uuid = unhex('$min_uuid') where uuid = unhex('$max_uuid')");
            $this->db->exec('insert into log_moder (id_type, id_user, content) values (1,' . $user['id'] . ',' . $this->db->quote($min_uuid) . ')');
            $sth = $this->db->prepare('insert into notification (id_user,mesage,ntime) values (:id,:txt, now())');

            foreach ($moderators as $id) {
                $sth->execute(['id' => $id, 'txt' => $txt]);
            }

            return $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();

            return false;
        }
    }

    /**
     * @param string $uuid
     *
     * @return bool
     */
    protected function existsUuid($uuid)
    {
        $sql = "select id from users where uuid = unhex('$uuid')";

        return (bool)$this->db->query($sql)->rowCount();
    }
}
