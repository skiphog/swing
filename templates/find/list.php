<?php
/**
 * @var \Crudch\View\View $this
 */
$dbh = db();
$auth = auth();
?>

<?php $this->extend('layouts/layout'); ?>

<?php $this->start('title'); ?>Поиск знакомств<?php $this->stop(); ?>
<?php $this->start('description'); ?>Поиск знакомств с парами, мужчинами и женщинами.<?php $this->stop(); ?>

<?php $this->start('style'); ?>
<style>
    .td-padding > td {
        padding: 5px 0 ;
    }
    .f-img{
        opacity: 0.8;
    }
    .f-img:hover{
        opacity: 1;
    }
    .find-input {
        cursor: pointer;
    }
</style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
<table  rules="none" cellspacing="0" cellpadding="0" style="border-collapse: collapse; border: 0 solid black;" width="100%">
    <tr>
        <td height="40" align="center" bgcolor="#cad9ff"><b>Виртуальная служба знакомств</b></td>
    </tr>
    <tr>
        <td>
            <?php require __DIR__ . '/form.php';?>
        </td>
    </tr>
    <tr>
            <td>


                <table border=0 width=100%>
                    <tr>
                        <td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff>
                            <a href="<?php echo url('/viewdiary_132'); ?>">
                                <img src="/img/vip.gif" border="0" >
                            </a>
                            <b>5 случайных VIP пользователей</b> <a href="<?php echo url('/viewdiary_132'); ?>">подробнее</a>
                        </td>
                    </tr>
                    <?php

                    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
   u.real_status, u.visibility, u.hot_time, u.regdate,
   u.vip_time, u.now_status, u.hot_text,
   u.vipsmile, u.admin, u.moderator, u.city,
   u.login, u.fname, u.gender, u.about, u.job,
   ut.last_view
from users u
   join users_timestamps ut on ut.id = u.id
where u.vip_time >= addtime(timestamp(date(NOW())), \'23:59:59\') and u.status = 1
order by RAND() desc
limit 5';

                    $sth = db()->query($sql);?>
                    <tr>
                        <td colspan="6" align="center" valign="center">
                            <?php while ($row = $sth->fetchObject(\App\Models\Users\RowUser::class)) {?>
                                <?php displayUser($auth, $row); ?>
                            <?php }?>
                        </td>
                    </tr>
                </table>


                <table border=0 width=100%>
                    <tr>
                        <td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff><b>TOP 5 рейтинга</b> (рейтинг по количеству баллов)</td>
                    </tr>
                    <?php

                    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
   u.real_status, u.visibility, u.hot_time, u.regdate,
   u.vip_time, u.now_status, u.hot_text,
   u.vipsmile, u.admin, u.moderator, u.city,
   u.login, u.fname, u.gender, u.about, u.job,
   ut.last_view
from users u
   join users_timestamps ut on ut.id = u.id and ut.last_view >= date_sub(NOW(), interval 30 day)
where u.status = 1
order by u.rate desc
limit 5';

                    $sth = db()->query($sql);?>
                    <tr>
                        <td colspan="6" align="center" valign="center">
                            <?php while ($row = $sth->fetchObject(\App\Models\Users\RowUser::class)) {?>
                                <?php displayUser($auth, $row); ?>
                            <?php }?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan=6 align=center valign=center height=35 bgcolor=#cad9ff><b>Новые анкеты на сайте</b></td>
                    </tr>
                    <?php
                    $sql = 'select u.id, u.birthday, u.pic1, u.photo_visibility,
   u.real_status, u.visibility, u.hot_time, u.regdate,
   u.vip_time, u.now_status, u.hot_text,
   u.vipsmile, u.admin, u.moderator, u.city,
   u.login, u.fname, u.gender, u.about, u.job,
   ut.last_view
from users u
   join users_timestamps ut on ut.id = u.id
where u.status = 1
order by u.regdate desc
limit 20';

                    $sth = db()->query($sql);?>
                    <tr>
                        <td colspan="6" align="center" valign="center">
                            <?php while ($row = $sth->fetchObject(\App\Models\Users\RowUser::class)) {?>
                                <?php displayUser($auth, $row); ?>
                            <?php } ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
</table>
<?php $this->stop(); ?>