<?php

echo remember('new_diaries', static function () {
    $sql = 'select d.id_di diary_id, d.title_di diary_title, d.text_di diary_text, 
           u.id user_id, u.login user_login, u.gender user_gender 
        from diary d 
            join users u on u.id = d.user_di 
        where d.main = 1 and d.deleted = 0 
    order by d.id_di desc limit 8';

    [$left_diaries, $right_diaries] = array_chunk(db()->query($sql)->fetchAll(), 4);
    ob_start();
    ?>
    <section class="main-page-section">
        <div class="page-section-header">Новые дневники</div>
        <div class="page-section-body main-page">
            <div class="sw-table-cell sw-w-50 pr-10 border-right">
                <?php echo render('/index/particles/diaries', ['diaries' => $left_diaries]); ?>
            </div>
            <div class="sw-table-cell sw-w-50 pl-10">
                <?php echo render('/index/particles/diaries', ['diaries' => $right_diaries]); ?>
            </div>
        </div>
    </section>
    <?php return compress(ob_get_clean());
});
