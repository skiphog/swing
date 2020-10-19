<?php
/**
 * @var View $this
 */

use System\View\View;

$db = db();
$auth = auth();
?>
<?php $this->extend('layouts/layout'); ?>
<?php $this->start('style'); ?>
    <style>
      .main-page{padding:5px;}
      .section-users{display:table;width:100%;}
      .sw-table-cell,.section-users-user{display:table-cell;vertical-align:top;word-break:break-word;}
      .page-section-header{font-weight:700;border-bottom:1px dashed #e4e4e5;background-color:#ebebf3;font-size:20px;padding:10px;}
      .right-section-body{padding:5px;}
      .page-section-body{padding:10px;}
      .main-page-head-section,.main-page-section{margin-bottom:15px;}
      .main-page-section,.active-theme{border:1px solid #e4e4e5;}
      .user-link{display:block;width:70px;margin:0 auto;text-decoration:none;}
      .section-users-user{text-align:center;width:10%;}
      .pl-10{padding-left:10px;}
      .pr-10{padding-right:10px;}
      .border-right{border-right:1px dashed #e4e4e5;}
      .sw-media{display:table-row;}
      .sw-media,.sw-media-body{overflow:hidden;zoom:1;}
      .sw-media-left,.sw-media-body{display:table-cell;vertical-align:middle;padding-bottom:5px;}
      .sw-media-body{padding-left:5px;}
      .sw-main-avatar-group{width:50px;height:50px;background-repeat:no-repeat;background-position:center;background-size:cover;border:1px solid #e4e4e5;}
      .brulik{width:80px;}
      .active-theme{display:block;padding:10px;height:136px;overflow:hidden;transition:all .1s ease-out;}
      .active-theme:not(:last-child){margin-bottom:10px;}
      .active-theme,.active-theme:hover,.active-theme:active,.active-theme:visited,.active-theme:focus{color:#444;}
      .active-theme:hover{box-shadow:0 0 20px rgba(0,0,0,0.15);}
      .active-theme:active{box-shadow:0 1px 4px rgba(0,0,0,0.08);}
      .diary-user{color:#11638c;}
      .diary-title,.thread-title{font-weight:700;}
      .group-title{font-style:italic;}
      .sw-w-50{width:50%;}
      dl{margin:0;}
      dt{font-weight:700;}
      dd{margin:0 0 10px 10px;}
      .p-botom{background:url(/img/spritesheet-5.png) no-repeat;padding-left:22px;}
      .pb-2{background-position:-2px -3px;}
      .pb-3{background-position:-2px -75px;}
      .main-article{overflow:hidden;padding:10px;margin-bottom:20px;border-bottom:5px dashed #ccc;}
      .main-article-title{font-size:24px}
      .main-article-body::after{content:'';display:table;clear:both;}
      @media only screen and (min-width: 1200px) {
        .active-theme{height:116px;}
      }
    </style>
<?php $this->stop(); ?>

<?php $this->start('content'); ?>
    <div class="main-page"><h1>Свинг знакомства на Свинг-киске</h1><?php $auth->isUser() ? require __DIR__ . '/auths/index.php' : require __DIR__ . '/guests/index.php'; ?></div>
<?php $this->stop(); ?>

<?php $this->start('script'); ?><?php $this->stop(); ?>