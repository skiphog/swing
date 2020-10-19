<?php

namespace App\Models;

use App\Models\Users\Auth;

class Album
{
    public static function newAlbums(Auth $auth)
    {
        if ($auth->isUser()) {
            $dop = $auth->isModerator() ? '' : 'and pa.pass = 0';
            $dop .= $auth->isReal() ? '' : ' and pa.albvisibility != 3';
            $func = static function (&$row) {
                $thumb = explode('|', $row['thumb']);
                $row['photo_id'] = $thumb[0];
                $row['thumb'] = "/{$thumb[1]}thumb_{$thumb[2]}";
            };
        } else {
            $dop = 'and pa.pass = 0 and pa.albvisibility = 0';
            $func = static function (&$row) {
                $thumb = explode('|', $row['thumb']);
                $row['photo_id'] = $thumb[0];
                $row['thumb'] = "/{$thumb[1]}" . md5($thumb[2]) . '.jpg';
            };
        }

        $sql = /** @lang */
            "select u.id user_id, u.login user_login, u.gender user_gender, pa.aid album_id, pa.count album_cnt, pa.pass album_password,
		        (select CONCAT(pid, '|', filepath, '|', filename) 
		        from photo_pictures pp where pp.aid = pa.aid order by pid limit 1) thumb
		    from photo_albums pa 
		    join users u on u.id = pa.us_id_album and u.status != 2 
		    where pa.count != 0 {$dop}
		order by pa.aid desc limit 8";

        $stmt = db()->query($sql);
        $albums = [];

        while ($row = $stmt->fetch()) {
            $func($row);
            $albums[] = $row;
        }

        return $albums;
    }
}
