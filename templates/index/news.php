<?php

echo remember('news', static function () {
    $sql = 'select sid id, title, hometext, bodytext, `time` created_at from stories order by sid desc limit 10';
    $stmt = db()->query($sql);

    $articles = [];
    while ($row = $stmt->fetch()) {
        //$text = clearBB($row['hometext']);
        //$row['img'] = preg_match('~<img[^>]+src="(.+?)"~', $text, $matches) ? $matches[1] : 'img/party-defolt.jpg';
        $row['hometext'] = str_replace(
            '&nbsp;', ' ',
            strip_tags($row['hometext'], ['a', 'p', 'br', 'img', 'strong', 'b'])
        );
        $row['bodytext'] = !empty($row['hometext']);
        $articles[] = $row;
    }

    ob_start();
    ?>
    <section class="main-page-section">
        <div class="page-section-header">Статьи о свинге и свингерах</div>
        <div class="page-section-body main-page">
            <?php foreach ($articles as $article) : ?>
                <div class="main-article">
                    <h3 class="main-article-title">
                        <a href="/viewstory_<?php echo $article['id']; ?>"><?php echo $article['title']; ?></a>
                    </h3>
                    <div class="main-article-body">
                        <?php echo $article['hometext']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php return compress(ob_get_clean());
});
