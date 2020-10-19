<?php

# Head
require __DIR__ . '/auth_head.php';
# New users
require __DIR__ . '/auth_new_users.php';
# New Diaries
require __DIR__ . '/../new_diaries.php';
# New Albums
echo remember(auth()->isModerator() ? 'moder_new_albums' : 'auth_new_albums', static function () {
    $albums = \App\Models\Album::newAlbums(auth());

    return compress(render('index/particles/albums', compact('albums')));
}, 120);
# Top threads
require __DIR__ . '/../top_threads.php';
# Rating photo
require __DIR__ . '/../top_photos.php';
# News
require __DIR__ . '/../news.php';
