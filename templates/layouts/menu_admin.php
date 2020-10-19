<?php
/**
 * @var PDO $db
 */

$complaints = db()->query('select count(*) from complaints where `status` = 0')->fetchColumn() ?: '';
?>
<style>.count-complaints{color:#F00;font-weight:700}#admin-deep{padding:0}</style>
<section class="information">
    <h3 class="information-header">Админка</h3>
    <ul class="information-list">
        <li><a href="<?php echo url('/sw-admin/panel'); ?>">Админ-панель</a></li>
        <li><a href="<?php echo url('/sw-admin/news'); ?>">Новости</a></li>
        <li><a href="<?php echo url('/sw-admin/options'); ?>">Настройки</a></li>
        <li><a href="<?php echo url('/sw-admin/complaints?getOpenComplaints'); ?>">Жалобы</a>
            <span class="count-complaints"><?php echo $complaints; ?></span>
        </li>
        <li><a href="<?php echo url('/sw-admin/dispatch'); ?>">Рассылка</a></li>
        <li class="list-divider"></li>
        <li><a href="#" data-toggle="admin-deep">Доп Настройки</a>
            <ul id="admin-deep" class="information-list hidden">
                <li class="list-divider"></li>
                <li><a href="<?php echo url('/sw-admin/options/banners'); ?>">Баннеры</a></li>
                <li><a href="<?php echo url('/sw-admin/options/present'); ?>">Подарки</a></li>
                <li><a href="<?php echo url('/sw-admin/options/rewards'); ?>">Виджеты</a></li>
                <li><a href="<?php echo url('/sw-admin/options/fonts'); ?>">Фоны</a></li>
                <li><a href="<?php echo url('/sw-admin/options/private'); ?>">Приват</a></li>
                <li><a href="<?php echo url('/sw-admin/options/disk'); ?>">Диск</a></li>
                <li><a href="<?php echo url('/sw-admin/options/adv'); ?>">Реклама</a></li>
                <li><a href="<?php echo url('/sw-admin/options/errors'); ?>">Ошибки</a></li>
            </ul>
        </li>
    </ul>
</section>