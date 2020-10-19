<?php
/**
 * @var PDO  $db
 * @var Auth $auth
 */

use App\Models\Users\Auth;

?>
<div class="right-section">
    <div class="right-section-header"> ---</div>
    <div class="right-section-body">
        <img class="border-box" style="display:block;width:172px;margin:0 auto" src="/img/promotion/1d91f379f3d0dabb6d0f836e71c3f736.jpg" alt="adi">
    </div>
</div>

<?php
$sql = 'select id, title, begin_date, city 
  from `events`
  where end_date > now() 
  and `status` = 1 
order by begin_date';

$parties = $db->query($sql)->fetchAll();
?>

<div class="right-section">
    <div class="right-section-header">Анонсы вечеринок</div>
    <div class="right-section-body">
        <?php foreach($parties as $party) : ?>
            <a class="active-theme">
                <?php echo e($party['title']); ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>
