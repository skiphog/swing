<?php
/**
 * @var PDO                     $db
 * @var \App\Models\Users\Auth $auth
 */
?>
<section class="information">
<?php if ($auth->isUser()) : ?>
    <h3 class="information-header">Профиль</h3>
    <div class="information-login"><?php echo e($auth->login); ?></div>
    <ul class="information-list">
        <li><a href="<?php echo url('/user/' . $auth->id); ?>">Анкета</a></li>
        <li><a href="<?php echo url('/my/albums'); ?>">Фотографии</a></li>
        <li><a href="<?php echo url('/my/friends'); ?>">Друзья</a> <?php echo $auth->cntFriends(); ?></li>
        <li><a href="<?php echo url('/my/diaries'); ?>">Дневники</a></li>
        <li>
            <a href="<?php echo url('/my/dialogs?getNewMessage'); ?>">Сообщения
                <span class="cnt count-mes"><?php echo $auth->cntMessage(); ?></span>
            </a>
        </li>
        <li>
            <a href="<?php echo url('/my/dialogs?getNewNotification'); ?>">Уведомления
                <span class="cnt count-nof"><?php echo $auth->cntNotify(); ?></span>
            </a>
        </li>
        <li>
            <a href="<?php echo url('/my/guests'); ?>">Гости
                <span class="cnt count-guest"><?php echo $auth->cntGuest(); ?></span>
            </a>
        </li>
        <li><a href="<?php echo url('/my/groups'); ?>">Группы</a></li>
        <li><a href="<?php echo url('/my/groups/activity'); ?>">Новости</a></li>
        <li class="list-divider"></li>
        <li><a href="<?php echo url('/travel'); ?>">Cвинг в путешествии</a></li>
        <li class="list-divider"></li>
        <li><a href="<?php echo url('/donate'); ?>">Поддержать сайт</a></li>
        <?php if ($auth->isSuperUser()) : ?>
            <li><a href="<?php echo url('/services'); ?>">Магазин / Сервисы</a></li>
        <?php endif; ?>
    </ul>
    <?php echo remember('advert_3', static function () use ($db){
        $sql = 'select url, target, img from advert_baner where `status` = 1 and position = 3 order by date_start desc';
        $stmt = $db->query($sql);
        if(!$stmt->rowCount()) {
            return '';
        }
        ob_start(); ?>
        <div class="information-deep">
            <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <a class="information-important" href="<?php echo $row['url']; ?>" target="<?php echo $row['target']; ?>">
                    <img class="border-box" src="/<?php echo $row['img']; ?>" width="170" alt="">
                </a>
            <?php endwhile; ?>
        </div>
        <?php return compress(ob_get_clean());
    }); ?>

    <?php if(false !== $widgets = cache()->get('vo')) : ?>
        <div class="information-deep">
            <?php foreach($widgets as $widget) : ?>
                <a class="information-widget border-box" href="<?php echo $widget['linko']; ?>">
                    <div class="information-widget-first">
                        <img src="<?php echo $widget['imageo']; ?>" width="29" height="29" alt="widget">
                    </div>
                    <div class="information-widget-second"><?php echo $widget['texto']; ?></div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <div class="information-exit">
        <a href="<?php echo url('/api/auth/quit'); ?>" onclick="return confirm('Выйти с сайта?')">Выход</a>
    </div>
<?php else : ?>
    <h3 class="information-header">Авторизация</h3>
    <ul class="information-list">
        <li><a href="<?php echo url('/auth/registration'); ?>">Зарегистрироваться</a></li>
        <li><a href="<?php echo url('/auth/repass'); ?>">Восстановить пароль</a></li>
        <li class="list-divider"></li>
        <li><a href="<?php echo url('/auth/login'); ?>">Войти</a></li>
    </ul>
<?php endif; ?>
</section>