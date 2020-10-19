<?php

/**
 * @param \App\Models\Users\Auth    $myrow
 * @param \App\Models\Users\RowUser $user
 */
function displayUser(App\Models\Users\Auth $myrow, App\Models\Users\RowUser $user)
{ ?>
    <table class="border-box" border="0" width="100%" style="margin:10px 0;background:<?php echo $user->getBackground(); ?>">
    <tr>
        <td width="90" valign="top" align="center">
            <a href="/user/<?php echo $user->id; ?>">
                <div class="border-box avatar"
                        style="background-image:url(<?php echo avatar($myrow, $user->pic1, $user->photo_visibility); ?>)">
                </div>
            </a>
            <div>
                <?php if ($user->isOnline()): ?>
                    <div class="u-online">В сети</div>
                <?php else: ?>
                    <div style="background:rgba(255, 255, 255, 0.1)">
                        <?php echo \App\Arrays\Genders::$old[$user->gender]; ?> на сайте<br>
                        <b><?php echo $user->last_view->getHumansPerson(); ?></b>
                    </div>
                <?php endif; ?>
            </div>
        </td>
        <td align="left" valign="top" style="padding-left:5px;">
            <div style="position:relative;padding:2px;background:rgba(255, 255, 255, 0.5);font-size:1.2em;">
                <?php echo genderIcon($user->gender); ?>
                <?php if ($user->isNewbe()): ?>
                    <img src="/img/newred.gif" width="34" height="15" alt="Newbe">
                <?php endif; ?>

                <?php if ($user->isVip()): ?>
                    <a href="<?php echo url('/viewdiary_132'); ?>">
                        <img src="<?php echo \App\Arrays\VipSmiles::$array[$user->vipsmile]; ?>">
                    </a><?php endif; ?>

                <?php if ($user->isBirthday()): ?>
                    <img src="/img/dr.gif" width="19" height="23" alt="birthday">
                <?php endif; ?>

                <?php if ($user->isReal()): ?>
                    <img src="/img/real.gif" width="20" height="20" alt="real" title="Реальная анкета">
                <?php endif; ?>

                <a href="/user/<?php echo $user->id; ?>">
                    <span style="font-weight:700"
                            class="m-<?php echo $user->moderator; ?> a-<?php echo $user->admin; ?>">
                        <?php echo html($user->login); ?>
                    </span>
                </a>
                <?php echo $user->birthday->getHumansShort(); ?> /
                <span class="u-city-<?php echo (int)(mb_strtolower($myrow->city) === mb_strtolower($user->city)); ?>">
                    <strong><?php echo html($user->city); ?></strong>
                </span>

                <?php if($myrow->id !== $user->id && $myrow->isUser()) : ?>
                    <!--<div style="position:absolute;top:calc(50% - 8px);right:5px">
                        <a href="<?php /*echo url('/privat_' . $user->id); */?>"
                                <?php /*if(empty($_SESSION['is_mobile'])) : */?>
                                    onclick="return openPrivate(this.href, <?php /*echo $user->id; */?>);"
                                <?php /*endif; */?>
                        >
                            <img src="/img/messag2.jpg" width="122" height="16" alt="отправить сообщение">
                        </a>
                    </div>-->
                <?php endif; ?>
            </div>

            <div style="margin:5px 0">
                <?php echo html(limit($user->about, 160, ' ...')); ?>
            </div>

            <?php if ($user->isHot()): ?>
                <div class="border-box" style="background:#fff;font-size:16px;padding:5px;">
                    <span style="color:#f00;">Срочно познакомимся:</span>
                    <?php echo html(limit($user->hot_text, 250, ' ...')); ?>
                </div>
            <?php elseif ($user->isNowStatus()): ?>
                <div>
                    <b>Настроение</b>: <?php echo html(limit($user->now_status, 250, ' ...')); ?>
                </div>
            <?php endif; ?></td>
    </tr>
    </table>
<?php }
