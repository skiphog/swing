<?php

echo remember('new_threads', static function () {
    $sql = 'select c.ugthread_id t_id, count(*) cnt, t.ugt_title t_title, t.ugt_descr t_text, g.ug_title g_title, g.ug_avatar avatar 
      from ugcomments c 
        join ugthread t on t.ugthread_id = c.ugthread_id 
        join ugroup g on g.ugroup_id = t.ugroup_id 
      where c.ugc_date > date_sub(now(), interval 1 day) 
        and c.ugc_dlt != 1 
        and g.ugroup_id != 32 
        and g.ug_dlt != 1 
        and t.ugt_dlt != 1 
        and g.ug_hidden != 1 
      group by c.ugthread_id 
      order by cnt desc 
    limit 6';

    $threads = db()->query($sql)->fetchAll();

    if (6 !== $count = count($threads)) {
        $limit = 6 - $count;
        $not_in = empty($threads) ?
            '' :
            ' and t.ugthread_id not in(' . implode(',', array_column($threads, 't_id')) . ')';

        $sql = /** @lang */
            "select t.ugthread_id t_id, t.ugt_title t_title, t.ugt_descr t_text, g.ug_title g_title, g.ug_avatar avatar 
          from ugthread t 
            join ugroup g on t.ugroup_id = g.ugroup_id 
          where g.ugroup_id != 32 {$not_in}
            and t.ugt_descr != ''
            and g.ug_dlt != 1 
            and t.ugt_dlt != 1 
            and g.ug_hidden != 1
          order by t.ugthread_id desc 
        limit {$limit}";

        $dop_threads = db()->query($sql)->fetchAll();
        $threads = array_merge($threads, $dop_threads);
    }

    [$left_threads, $right_threads] = array_chunk($threads, 3);
    ob_start();
    ?>
    <section class="main-page-section">
        <div class="page-section-header">Активные темы</div>
        <div class="page-section-body main-page">
            <div class="sw-table-cell sw-w-50 pr-10 border-right">
                <?php echo render('/index/particles/threads', ['threads' => $left_threads]); ?>
            </div>
            <div class="sw-table-cell sw-w-50 pl-10">
                <?php echo render('/index/particles/threads', ['threads' => $right_threads]); ?>
            </div>
        </div>
    </section>
    <?php return compress(ob_get_clean());
}, 1800);
