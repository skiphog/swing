<?php
/**
* @var array $users
*/
?>
<ul class="mordalenta">
    <?php foreach ($users as $user) : ?>
        <li class="mordalenta-item">
            <img class="mordalenta-item-avatar" src="/avatars/user_thumb/<?php echo $user['pic1']; ?>" alt="">
            <div class="mordalenta-item-info"></div>
        </li>
    <?php endforeach; ?>
</ul>