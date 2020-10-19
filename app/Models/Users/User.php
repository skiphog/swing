<?php

namespace App\Models\Users;

use PDO;
use ArrayAccess;
use System\Database\Model;

/**
 * Class User
 *
 * @property int    $id
 * @property int    $admin
 * @property int    $moderator
 * @property int    $assistant
 * @property string $login
 * @property int    $gender
 * @property string $city
 * @property int    $region_id
 * @property int    $status
 * @property int    $rate
 * @property int    $real_status
 * @property string $moder_text
 * @property string $vip_time
 *
 * @package App\Models\Users
 */
class User extends Model implements ArrayAccess
{
    /**
     * Статус Активно
     */
    public const ACTIVE = 1;

    /**
     * Статус не активно
     */
    public const INACTIVE = 2;

    /**
     * Статус на модерации
     */
    public const ON_MODERATION = 3;

    /**
     * Забанен
     */
    public const BAN = '_БАН_';

    /**
     * @var bool
     */
    protected ?bool $vip = null;

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (bool)$this->admin;
    }

    /**
     * @return bool
     */
    public function isModerator(): bool
    {
        return (bool)$this->moderator;
    }

    /**
     * @return bool
     */
    public function isReal(): bool
    {
        return (bool)$this->real_status;
    }

    /**
     * @return bool
     */
    public function isVip(): bool
    {
        if (null === $this->vip) {
            $this->vip = strtotime($this->vip_time) - $_SERVER['REQUEST_TIME'] >= 0;
        }

        return $this->vip;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return self::ACTIVE === $this->status;
    }

    /**
     * @return bool
     */
    public function isInActive(): bool
    {
        return self::INACTIVE === $this->status;
    }

    /**
     * @return bool
     */
    public function isOnModeration(): bool
    {
        return self::ON_MODERATION === $this->status;
    }

    /**
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    protected function specialSet($name, $value)
    {
        return $this->{$name} = is_numeric($value) ? (int)$value : $value;
    }

    /**
     * Получить id всех модераторов сайта
     *
     * @return array
     */
    public static function moderatorIds(): array
    {
        $sql = 'select id from users where moderator <> 0 and id <> 935 order by id';

        return db()->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->{$offset});
    }
}
