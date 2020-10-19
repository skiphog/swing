<?php

use App\Models\Photo;

echo remember(auth()->isUser() ? 'auth_top_photos' : 'guest_top_photos', static function () {
    $photos = Photo::topPhotos(auth());
    ob_start();
    ?>
    <section class="main-page-section">
        <div class="page-section-header">Рейтинг фото на этой неделе</div>
        <div class="page-section-body">
            <div class="section-users">
                <?php foreach ($photos as $photo) : ?>
                    <div class="section-users-user">
                        <a class="user-link" href="/albums_<?php echo $photo['album_id']; ?>_<?php echo $photo['photo_id']; ?>">
                            <img class="avatar" src="<?php echo $photo['thumb']; ?>" width="70" height="70" alt="">
                        </a>
                        <div>
                            <span class="p-botom pb-3" title="Лайки"><?php echo $photo['likes']; ?></span><br>
                            <span class="p-botom pb-2" title="Просмотры"><?php echo $photo['v_count']; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php return compress(ob_get_clean());
}, 3600);
