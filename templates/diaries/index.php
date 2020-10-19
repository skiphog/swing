<?php
/**
 * @var View   $this
 * @var array  $diaries
 * @var string $paginate
 */

use System\View\View;

$auth = auth();

?>

<?php $this->extend('layouts/layout') ?>

<?php $this->start('title') ?>Дневники<?php $this->stop() ?>
<?php $this->start('description') ?>Дневники свингеров. Рассказы и реальные истории.<?php $this->stop() ?>

<?php $this->start('style'); ?>
    <style>
      .diaries {
        margin: 20px 0;
      }
      .diaries-column {
        margin-bottom: 20px;
        padding: 10px;
        border: 1px solid #ccc;
      }

      .diaries-meta {
        margin-bottom: 10px;
      }

      .diaries-meta-info {
        padding-left: 10px;
      }

      .diaries-meta-d {
        font-style: italic;
      }

      .diaries-meta-d-humans {
        font-weight: 700;
        font-size: .9em;
        color: #8c8686;
      }

      .diaries-title {
        font-size: 24px;
        margin: 0 0 5px 0;
      }

      .diaries-body .new-imgart, .diaries-body .ug-imgart {
        display: block;
        max-width: 400px;
      }

      .diaries-body {
        margin-bottom: 10px;
      }
      .table-cell {
        display: table-cell;
        vertical-align: middle;
      }
      .ava-50 {
        width: 50px;
        height: 50px;
      }
      .p-botom {
        background: url(/img/spritesheet-5.png) no-repeat;
        padding-left: 22px;
        cursor: help;
      }

      .pb-1 {
        background-position: -2px -3px;
      }

      .pb-2 {
        background-position: -2px -52px;
      }
    </style>
<?php $this->stop(); ?>

<?php $this->start('content') ?>
    <h1>Дневники</h1>
<?php echo $paginate; ?>
    <div class="diaries">
        <?php foreach ($diaries as $diary) : ?>
            <article class="diaries-column">
                <h2 class="diaries-title"><a href="/diaries/<?php echo $diary['id_di']; ?>"><?php echo e($diary['title_di']); ?></a></h2>
                <div class="diaries-meta">
                    <div class="table-cell">
                        <img class="avatar ava-50" src="/avatars/user_thumb/<?php echo avatar($auth, $diary['pic1'], $diary['photo_visibility']); ?>" width="50" height="50" alt="avatar">
                    </div>
                    <div class="table-cell diaries-meta-info">
                        <a class="hover-tip" href="/user/<?php echo $diary['id']; ?>" title="Автор дневника">
                            <?php echo genderIcon($diary['gender']); ?>
                            <?php echo e($diary['login']) ?>
                        </a>
                        <br>
                        <span class="diaries-meta-d"><?php echo ($date = swDate($diary['data_di']))->format('d-m-Y'); ?></span> |
                        <span class="diaries-meta-d-humans"><?php echo $date->humans(); ?></span>
                    </div>
                </div>

                <div class="diaries-body"><?php echo nl2br(smile(parseBB($diary['text_di']))); ?></div>
                <div class="diaries-info">
                    <span class="p-botom pb-1" title="Просмотры"><?php echo $diary['v_count']; ?></span>
                    <span class="p-botom pb-2" title="Лайки"><?php echo $diary['likes']; ?></span>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php echo $paginate; ?>

<?php $this->stop() ?>