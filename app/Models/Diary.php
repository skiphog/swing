<?php

namespace App\Models;

use PDO;
use Kilte\Pagination\Pagination;

class Diary
{
    public static function all(?int $page)
    {
        $db = db();
        $count = $db->query('select count(*) from diary where deleted = 0')->fetchColumn();
        $pagination = new Pagination($count, $page, 10, 2);
        $sql = 'select d.id_di, d.title_di, d.text_di, d.data_di,d.likes,d.v_count,
          u.id, u.login, u.gender, u.pic1, u.city, u.region_id, u.fname, u.photo_visibility
          from diary d
            join users u on u.id = d.user_di
          where d.deleted = 0 
        order by d.id_di desc limit ' . $pagination->offset() . ', 10';

        return [$db->query($sql)->fetchAll(), $pagination->build()];
    }

    public static function findById(int $id)
    {
        $sql = "select d.id_di diary_id, d.title_di,d.text_di, d.data_di, d.likes, d.dislikes, d.v_count,
          u.id id_user, u.login, u.gender, u.pic1, u.photo_visibility, u.region_id, u.city
          from diary d
          join users u on u.id = d.user_di
          where d.id_di = {$id} 
        and d.deleted = 0 limit 1";

        return db()->query($sql)->fetch();
    }
}
