<?php
/**
 * @var PDO  $db
 * @var Auth $auth
 */

use App\Arrays\Genders;
use App\Models\Users\Auth;

$users = remember('new_users_for_auth', static function () {
    $sql = 'select id, login, photo_visibility, pic1, gender, city, region_id, birthday, regdate 
        from users 
        where status = 1 and pic1 <> \'net-avatara.jpg\' 
    order by id desc limit 8';

    return db()->query($sql)->fetchAll();
}, 3600);
?>
<section class="main-page-section">
    <div class="page-section-header">Новые анкеты</div>
    <div class="page-section-body">
        <div class="section-users">
            <?php foreach ($users as $user) : ?>
                <div class="section-users-user">
                    <a class="user-link" href="/user/<?php echo $user['id']; ?>">
                        <img class="avatar" src="/avatars/user_thumb/<?php echo $user['pic1']; ?>" width="70" height="70" alt="">
                    </a>
                    <img src="/img/newred.gif" width="34" height="15" alt="new">
                    <div><?php echo Genders::$gender[$user['gender']]; ?></div>
                    <div><?php echo dateAge($user['birthday']); ?></div>
                    <div class="c-<?php echo regionComp($auth->region_id, $user['region_id']); ?>"><?php echo $user['city']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>