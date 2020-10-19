<?php
/**
* @var array $diaries
*/
?>
<?php foreach($diaries as $diary) : ?>
    <a class="active-theme" href="/diaries/<?php echo $diary['diary_id']; ?>">
        <div class="diary-user"><?php echo genderIcon($diary['user_gender']); ?> <?php echo e($diary['user_login']); ?></div>
        <div class="diary-title"><?php echo e($diary['diary_title']); ?></div>
        <div>
            <?php echo limit(clearBB($diary['diary_text']), 200); ?>
        </div>
    </a>
<?php endforeach; ?>