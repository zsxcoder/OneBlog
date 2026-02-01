<?php
/**
 * 书籍详情页
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$id = isset($this->request->id) ? (int)$this->request->id : 0;
if (!$id) {
    $this->response->redirect($this->options->siteUrl);
}

$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$book = $db->fetchRow($db->select()->from($prefix . 'book_collection')->where('id = ?', $id));

if (!$book) {
    $this->response->redirect($this->options->siteUrl);
}

$options = Helper::options();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0, width=device-width"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars($book['title']); ?> - <?php $options->title(); ?></title>
<link href="https://cncdn.cc/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="//at.alicdn.com/t/c/font_3940454_lp08yxn46sl.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cncdn.cc/@fancyapps/fancybox/3.5.7/dist/jquery.fancybox.min.css" />
<link href="https://cncdn.cc/oneblog/3.6.5/main.css" rel="stylesheet"/>
<link href="https://cncdn.cc/oneblog/3.6.5/m.css" rel="stylesheet"/>
<script src="https://cncdn.cc/jquery/3.7.1/dist/jquery.min.js"></script>
<script src="https://cncdn.cc/@fancyapps/fancybox/3.5.7/dist/jquery.fancybox.min.js"></script>
<style>
:root {--theme-color: <?php echo $options->themeColor ?: '#ff5050'; ?>;}
<?php echo $options->CSS; ?>
</style>
<?php $hasLoaded = !empty($_COOKIE['jsLoaded']); ?>
<script>
var logoUrl = "<?php echo $options->logo ?: $options->themeUrl . '/static/img/logo.svg'; ?>";
var logoWhiteUrl = "<?php echo $options->logoWhite ?: $options->themeUrl . '/static/img/logoWhite.svg'; ?>";
(function() {
    var currentTheme = document.cookie.replace(/(?:(?:^|.*;\s*)eyeProtectMode\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    if (currentTheme === 'dark') {
        document.documentElement.classList.add('night');
    }
})();
</script>
</head>
<body>
<div id="global-loading" style="display:<?php echo $hasLoaded ? 'none' : 'flex'; ?>;">
    <div class="progress-loader"><div class="progress"></div></div>
</div>
<div id="main" style="display:<?php echo $hasLoaded ? '' : 'none'; ?>;">
<?php $this->need('module/head2.php'); ?>
<div class="main">
    <div class="page_thumb blur">
        <div class="post_bg lazy-load" data-src="<?php echo htmlspecialchars($book['cover'] ?: $options->themeUrl . '/static/img/book.jpg'); ?>"></div>
        <div class="pc">
            <i class="iconfont icon-nav menu-button"></i>
            <div class="page-head">
                <?php if ($options->logoStyle == 'text'): ?>
                <h1><a href="<?php $options->siteUrl(); ?>"><?php $options->title(); ?></a><span class="soul">生活志</span></h1>
                <?php else: ?>
                <a class="logo" href="<?php $options->siteUrl(); ?>">
                    <img src="<?php echo $options->logoWhite ? $options->logoWhite : $options->themeUrl . '/static/img/logoWhite.svg'; ?>">
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="m">
            <h1 class="page-head"><?php echo htmlspecialchars($book['title']); ?><span><?php echo htmlspecialchars($book['author']); ?></span></h1>
        </div>
    </div>
    <div class="book-detail">
        <div class="book-header">
            <div class="book-cover">
                <img src="<?php echo htmlspecialchars($book['cover'] ?: $options->themeUrl . '/static/img/bg.jpg'); ?>" alt="<?php echo htmlspecialchars($book['title']); ?>">
            </div>
            <div class="book-info">
                <h1><?php echo htmlspecialchars($book['title']); ?></h1>
                <p class="book-author"><span>作者：</span><?php echo htmlspecialchars($book['author']); ?></p>
                <?php if (!empty($book['publisher'])): ?>
                <p class="book-publisher"><span>出版社：</span><?php echo htmlspecialchars($book['publisher']); ?></p>
                <?php endif; ?>
                <?php if (!empty($book['pub_year'])): ?>
                <p class="book-year"><span>出版年份：</span><?php echo htmlspecialchars($book['pub_year']); ?></p>
                <?php endif; ?>
                <?php if ($book['rating'] > 0): ?>
                <p class="book-rating"><span>我的评分：</span>
                    <span class="rating-stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="<?php echo $i <= round($book['rating'] / 2) ? 'active' : 'inactive'; ?>">★</span>
                        <?php endfor; ?>
                    </span>
                    <span class="rating-num"><?php echo $book['rating']; ?>/10</span>
                </p>
                <?php endif; ?>
                <?php if (!empty($book['read_date'])): ?>
                <p class="book-read-date"><span>阅读日期：</span><?php echo htmlspecialchars($book['read_date']); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="book-tabs">
            <div class="tab-item">
                <a href="#book-note" class="selected">读书笔记</a>
                <a href="#book-summary">书籍简介</a>
            </div>
        </div>
        <div class="book-content">
            <div class="book-note" id="book-note">
                <?php if (!empty($book['note'])): ?>
                <div class="note-content">
                    <?php echo $book['note']; ?>
                </div>
                <?php else: ?>
                <div class="nodata">
                    <img src="<?php $options->themeUrl('static/img/nodata.svg'); ?>">
                    <span>博主暂未发布本书的读书笔记。</span>
                </div>
                <?php endif; ?>
            </div>
            <div class="book-summary" id="book-summary">
                <?php if (!empty($book['summary'])): ?>
                <div class="summary-content">
                    <?php echo $book['summary']; ?>
                </div>
                <?php else: ?>
                <div class="nodata">
                    <img src="<?php $options->themeUrl('static/img/nodata.svg'); ?>">
                    <span>博主很懒，暂未填写本书简介。</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<script src="<?php $options->themeUrl('static/js/main.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('.book-tabs .tab-item a').click(function(e) {
        e.preventDefault();
        $('.book-tabs .tab-item a').removeClass('selected');
        $(this).addClass('selected');
        var target = $(this).attr('href');
        $('.book-content > div').hide();
        $(target).show();
    });

    $('[data-fancybox]').fancybox({
        loop: true,
        buttons: ['zoom', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close']
    });
});
</script>
</body>
</html>
