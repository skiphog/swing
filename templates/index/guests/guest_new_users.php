<?php
/**
 * @var array $users
 */

use App\Arrays\Genders;

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
                    <div><?php echo $user['city']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>