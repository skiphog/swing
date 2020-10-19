<?php

namespace App\Models;

use App\Models\Users\Auth;

class Photo
{
    public static function topPhotos(Auth $auth)
    {
        if ($auth->isUser()) {
            $func = static function (&$row) {
                $row['thumb'] = "/{$row['filepath']}thumb_{$row['filename']}";
            };
        } else {
            $func = static function (&$row) {
                $row['thumb'] = "/{$row['filepath']}" . md5($row['filename']) . '.jpg';
            };
        }

        $sql = 'select p.pid photo_id, p.aid album_id, p.filepath, p.filename, p.v_count, p.likes
          from (select max(likes) likes, aid from photo_pictures where mtime > date_sub(now(), interval 1 week) group by aid) a
            join photo_pictures p on p.aid = a.aid
            join photo_albums pa on pa.aid = p.aid
            join users u on u.id = pa.us_id_album
          where p.likes = a.likes and pa.albvisibility < 3 and pa.pass = 0 and u.status != 2
        order by p.likes desc, p.pid limit 8';

        $photos = db()->query($sql)->fetchAll();

        if (8 !== $count = count($photos)) {
            $limit = 8 - $count;
            $not_in = empty($photos) ?
                '' :
                ' and p.pid not in(' . implode(',', array_column($photos, 'photo_id')) . ')';

            /**
             * Это очень медленный запрос. Нужно что-то попроще
             */

            $sql = /** @lang */
                "select p.pid photo_id, p.aid album_id, p.filepath, p.filename, p.v_count, p.likes 
              from photo_pictures p 
                join photo_albums pa on p.aid = pa.aid
                join users u on p.id_us_photo = u.id
              where pa.albvisibility < 3 and p.likes > 50 {$not_in}
                and pa.pass = 0
                and u.status != 2 
            order by p.pid desc limit {$limit}";

            $dop_photos = db()->query($sql)->fetchAll();
            $photos = array_merge($photos, $dop_photos);
        }

        foreach ($photos as &$photo) {
            $func($photo);
        }

        return $photos;
    }
}
