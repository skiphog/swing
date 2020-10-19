<?php
/**
 * @var array  $data
 * @var string $date
 */

use App\Arrays\VipSmiles;

?>
<ul class="information-list information-list-users information-list-small"><?php foreach ($data as $users) : ?><li class="information-list-city"><?php echo e($users[0]['region_title']); ?> - <?php echo count($users); ?></li><?php foreach ($users as $user) : ?><li><a class="hover-tip s-<?php echo $user['assistant']; ?> m-<?php echo $user['moderator']; ?> a-<?php echo $user['admin']; ?>" href="/user/<?php echo $user['id']; ?>"><?php echo genderIcon($user['gender'], 16, 16); ?><?php if ($date === substr($user['birthday'], 5)) : ?> <img src="/img/cake.svg" width="20" height="20" alt="birthday"><?php endif; ?><?php if (strtotime($user['vip_time']) - $_SERVER['REQUEST_TIME'] >= 0) : ?> <img src="<?php echo VipSmiles::$array[$user['vipsmile']]; ?>" alt=""><?php endif; ?><span> <?php echo e($user['login']); ?></span></a> <a href="/privat/<?php echo $user['id']; ?>" data-privat="<?php echo $user['id']; ?>"><img src="/img/privat.gif" width="15" height="15" alt=""></a></li><?php endforeach; ?><?php endforeach; ?></ul>