<?php
/**
 * @var PDO                    $db
 * @var \App\Models\Users\Auth $auth
 */

$a_count = remember('a_count', static fn() => (int)db()->query('select count(*) from users')->fetchColumn());

$g_online = remember('visitors', static function () {
    try {
        return random_int(100, 300);
    } catch (Exception $e) {
        return 100;
    }
}, 600);


if (isProduction()) {
    $stat_users = remember('online_users', static function () {
        $sql = 'select u.country_id, u.region_id, r.title region_title, ut.id, u.login, u.gender, u.admin, 
                u.moderator, u.assistant, u.vipsmile, u.birthday, u.vip_time, u.job
            from users_timestamps ut join users u on u.id = ut.id join regions r on u.region_id = r.id
            where ut.last_view > DATE_SUB(NOW(), interval 600 second)
        order by u.region_id, u.id desc';

        return db()->query($sql)->fetchAll(PDO::FETCH_GROUP);
    }, 300);
} else {
    $stat_users = require __DIR__ . '/../../assets/resources/online.php';
}

$t_online = $g_online + ($u_online = array_reduce($stat_users, static fn($c, $i) => $c + count($i), 0));

if ($auth->isUser()) {
    $stat_region_users = $stat_other_users = [];

    if (array_key_exists($auth->country_id, $stat_users)) {
        foreach ($stat_users[$auth->country_id] as $stat_region) {
            if ($auth->region_id === (int)$stat_region['region_id']) {
                $stat_region_users[] = $stat_region;
                continue;
            }
            $stat_other_users[$stat_region['region_id']][] = $stat_region;
        }
        unset($stat_users[$auth->country_id]);
    }

    foreach ($stat_users as $stat_country) {
        foreach ($stat_country as $stat_country_user_item) {
            $stat_other_users[$stat_country_user_item['region_id']][] = $stat_country_user_item;
        }
    }
}

$date = date('m-d');
?>
<section class="information" id="user-stat">
    <h3 class="information-header">Статистика</h3>
    <ul class="information-list">
        <li>Всего анкет: <strong><?php echo formatNumber($a_count); ?></strong></li>
        <li>
            <a href="<?php echo url('/users/online'); ?>">Всего онлайн:</a>
            <strong><?php echo $t_online; ?></strong>
        </li>
        <li>Гостей - <strong><?php echo $g_online; ?></strong></li>
        <li>Пользователей - <strong><?php echo $u_online ?></strong></li>
    </ul>
    <?php if ($auth->isUser()) {

        if (!empty($stat_region_users)) {
            echo render('layouts/partials/users_stat_region', [
                'users' => $stat_region_users,
                'date'  => $date,
            ]);
        }

        if (!empty($stat_other_users)) {
            echo render('layouts/partials/users_stat_other', [
                'data' => $stat_other_users,
                'date'  => $date,
            ]);
        }

    } else {
        echo render('layouts/partials/users_stat', [
            'data' => $stat_users,
            'date'  => $date,
        ]);
    }
    ?>
</section>