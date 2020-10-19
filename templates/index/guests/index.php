<?php

# Head
echo remember('guest_head', static fn() => compress(render('/index/guests/guest_head')));
# New users
echo remember('guest_new_users', static function () {
    $sql = 'select id, login, photo_visibility, pic1, gender, city, region_id, birthday, regdate 
        from users 
        where status = 1 and pic1 != \'net-avatara.jpg\' and photo_visibility = 0
    order by id desc limit 8';
    $users = db()->query($sql)->fetchAll();

    return compress(render('/index/guests/guest_new_users', compact('users')));
}, 3600);
# New Diaries
require __DIR__ . '/../new_diaries.php';
# New Albums
echo remember('guest_new_albums', static function () {
    $albums = \App\Models\Album::newAlbums(auth());

    return compress(render('index/particles/albums', compact('albums')));
}, 120);
# Top threads
require __DIR__ . '/../top_threads.php';
# Rating photo
require __DIR__ . '/../top_photos.php';
# News
require __DIR__ . '/../news.php';