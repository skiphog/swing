<?php
/**
* @var array $albums
*/
?>

<section class="main-page-section">
    <div class="page-section-header">Новые Альбомы</div>
    <div class="page-section-body">
        <div class="section-users">
            <?php foreach ($albums as $album) : ?>
                <div class="section-users-user">
                    <a class="user-link" href="">
                        <img class="avatar" src="<?php echo $album['thumb']; ?>" width="70" height="70" alt="">
                    </a>
                    <div><?php echo $album['album_cnt']; ?> фото</div>
                    <?php if(0 !== (int)$album['album_password']) : ?>
                        <div class="red">Пароль</div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
