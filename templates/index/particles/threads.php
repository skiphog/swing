<?php
/**
* @var array $threads
*/
?>
<?php foreach($threads as $thread) : ?>
    <a class="active-theme" href="/viewugthread_<?php echo $thread['t_id']; ?>">
        <div class="group-title"><?php echo e($thread['g_title']); ?></div>
        <div class="thread-title"><?php echo e($thread['t_title']); ?></div>
        <div>
            <div class="sw-media-left">
                <div class="sw-main-avatar-group" style="background-image: url(<?php echo url('/avatars/group_thumb/' . $thread['avatar']); ?>)"></div>
            </div>
            <div class="sw-media-body"><?php echo limit(clearBB($thread['t_text']), 150); ?></div>
        </div>
    </a>
<?php endforeach; ?>