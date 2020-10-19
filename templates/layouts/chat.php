<?php
$minchat = remember('minchat', static function () {
    $stmt = db()->query('select ch_nik, ch_text, ch_uid from chat order by ch_timestamp desc limit 5');
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $row['ch_text'] = hyperlink(smile($row['ch_text']));
        $data[] = $row;
    }

    return $data;
});
?>
<div class="header-chat"><marquee behavior="scroll" direction="up" height="130" width="157" scrollamount="2" onmouseover="this.stop()" onmouseout="this.start()"><?php if (!empty($minchat)) {foreach ($minchat as $value) { ?><a href="/user/<?php echo $value['ch_uid']; ?>"><?php echo e($value['ch_nik']); ?></a>:<br><?php $value['ch_text'] = preg_replace_callback('#{{(.+?)}}#', static function ($item) {return '<b style="color:' . (auth()->login === $item[1] ? '#F00' : '#747474') . '">' . e($item[1]) . '</b>';}, $value['ch_text']);echo nl2br($value['ch_text']); ?><br><?php }} ?></marquee></div><div class="header-enter-chat"><a href="<?php echo url('/chat'); ?>">Войти в чат</a></div>