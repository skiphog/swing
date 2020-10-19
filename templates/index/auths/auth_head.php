<?php
/**
 * @var PDO $db
 */

$banner = remember('banner', static function () {
    $sql = 'select img, link, target from banners where activity = \'1\' order by rand() limit 1';

    return db()->query($sql)->fetch();
}, 30);

$theme = remember('theme', static function () {
    $result = db()->query('select theme from site where site_id = 1')->fetchColumn();

    return unserialize($result, ['allowed_classes' => true]);
});
?>

<section class="main-page-head-section">
    <a class="sw-table-cell" <?php if(!empty($banner['link'])) : ?>href="<?php echo $banner['link']; ?>"<?php endif; ?> target="<?php echo $banner['target']; ?>">
        <img src="/<?php echo $banner['img']; ?>" width="300" height="248" alt="">
    </a>
    <div class="sw-table-cell pl-10">
        <?php foreach($theme as $item) : ?>
            <div class="sw-media">
                <div class="sw-media-left brulik"><img src="<?php echo $item['image']; ?>" width="80" height="21" alt=""></div>
                <div class="sw-media-body"><a href="<?php echo $item['link']; ?>"><?php echo e($item['text']); ?></a></div>
            </div>
        <?php endforeach; ?>
    </div>
</section>