<?php

namespace App\Models\Users;

use PDO;
use Exception;
use DateTimeImmutable;
use System\Date\SwDate;

/**
 * Class RowUser
 *
 * @property string $pic1
 * @property int    $photo_visibility
 * @property int    $visibility
 * @property string $hot_time
 * @property string $regdate
 * @property string $now_status
 * @property string $hot_text
 * @property int    $vipsmile
 * @property string $fname
 * @property string $about
 * @property SwDate $last_view
 * @property SwDate $birthday
 *
 * @package App\Models\Users
 */
class RowUser extends User
{
    /**
     * @var bool
     */
    protected ?bool $hot;

    /**
     * @return string
     */
    public function getBackground()
    {
        if ($this->isVip()) {
            $sql = 'select v.background 
              from `option` o 
              left join vip_background v on v.id = o.vip_background 
            where o.u_id = ' . $this->id . ' limit 1';

            return 'url(' . (db()->query($sql)->fetchColumn() ?: '/img/vip.jpg') . ')';
        }

        return $this->isHot() ? '#cad9ff' : '#eae9f1';
    }

    /**
     * @return bool
     */
    public function isHot()
    {
        if (null === $this->hot) {
            $this->hot = !empty($this->hot_text) && strtotime($this->hot_time) > $_SERVER['REQUEST_TIME'];
        }

        return $this->hot;
    }

    /**
     * @return bool
     */
    public function isNowStatus():bool
    {
        return !empty($this->now_status);
    }

    /**
     * @return bool
     */
    public function isNewbe():bool
    {
        return ($_SERVER['REQUEST_TIME'] - strtotime($this->regdate)) < 604800;
    }

    /**
     * @return bool
     */
    public function isBirthday():bool
    {
        try {
            return $this->birthday->format('dm') === (new DateTimeImmutable())->format('dm');
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public function isOnline():bool
    {
        return ($this->last_view->getTimestamp() + 600) > $_SERVER['REQUEST_TIME'];
    }

    /**
     * @param $value
     *
     * @throws Exception
     */
    public function setBirthday($value)
    {
        $this->{'birthday'} = new SwDate($value);
    }

    /**
     * @param $value
     *
     * @throws Exception
     */
    public function setLastView($value)
    {
        $this->{'last_view'} = new SwDate($value);
    }

    /**
     * @param $sql
     *
     * @return RowUser[]
     */
    public static function users($sql)
    {
        $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
           u.real_status, u.visibility, u.hot_time, u.regdate,
           u.vip_time, u.now_status, u.hot_text,
           u.vipsmile, u.admin, u.moderator, u.city, u.region_id,
           u.login, u.fname, u.gender, u.about, u.job,
           ut.last_view
        from users u join users_timestamps ut on ut.id = u.id where u.status = 1 ' . $sql;

        return db()->query($sql)
            ->fetchAll(PDO::FETCH_CLASS, static::class);
    }
}
