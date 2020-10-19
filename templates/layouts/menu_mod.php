<?php
/**
 * @var PDO $db
 */

$users = $db->query('select count(*) from users where `status` = 3')->fetchColumn() ?: '';
$events = $db->query('select count(*) from `events` where `status` = 3')->fetchColumn() ?: '';
?>
<style>#moder-count,.moder-anons{color:#F00;font-weight:700;}#moder-count{font-size:16px}</style>
<section class="information">
    <h3 class="information-header">Модераторская</h3>
    <ul class="information-list">
        <li>
            <a href="<?php echo url('/sw-moderator/list?action=moderations'); ?>">Модерация</a>
            <span id="moder-count"><?php echo $users; ?></span>
        </li>
        <li><a href="<?php echo url('/sw-moderator/list=photo'); ?>">Фотографии</a></li>
        <li><a href="<?php echo url('/sw-moderator/notebook'); ?>">Блокнот</a></li>
        <li>
            <a href="<?php echo url('/sw-moderator/parties'); ?>">Анонсы</a>
            <span class="moder-anons"><?php echo $events; ?></span>
        </li>
        <li><a href="<?php echo url('/sw-moderator/management'); ?>">Управление</a></li>
        <li><a href="<?php echo url('/sw-moderator/logs'); ?>">Лог-лист</a></li>
        <li><a href="<?php echo url('/sw-moderator/masters'); ?>">Мастерская</a></li>
    </ul>
</section>
