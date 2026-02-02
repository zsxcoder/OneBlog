<?php
/**
 * 书籍详情页
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0, width=device-width"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php $this->title(); ?> - <?php $this->options->title(); ?></title>
<link href="https://cncdn.cc/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<link href="//at.alicdn.com/t/c/font_3940454_lp08yxn46sl.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://cncdn.cc/@fancyapps/fancybox/3.5.7/dist/jquery.fancybox.min.css" />
<link href="https://cncdn.cc/oneblog/3.6.5/main.css" rel="stylesheet"/>
<link href="https://cncdn.cc/oneblog/3.6.5/m.css" rel="stylesheet"/>
<link href="<?php $this->options->themeUrl('static/css/book-photo.css'); ?>" rel="stylesheet"/>
<script src="https://cncdn.cc/jquery/3.7.1/dist/jquery.min.js"></script>
<script src="https://cncdn.cc/@fancyapps/fancybox/3.5.7/dist/jquery.fancybox.min.js"></script>
<style>
:root {--theme-color: <?php echo $this->options->themeColor ?: '#ff5050'; ?>;}
<?php echo $this->options->CSS; ?>
</style>
<?php $hasLoaded = !empty($_COOKIE['jsLoaded']); ?>
<script>
var logoUrl = "<?php echo $this->options->logo ?: $this->options->themeUrl . '/static/img/logo.svg'; ?>";
var logoWhiteUrl = "<?php echo $this->options->logoWhite ?: $this->options->themeUrl . '/static/img/logoWhite.svg'; ?>";
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
        <div class="post_bg lazy-load" data-src="<?php echo showThumbnail($this); ?>"></div>
        <div class="pc">
            <i class="iconfont icon-nav menu-button"></i>
            <div class="page-head">
                <?php if ($this->options->logoStyle == 'text'): ?>
                <h1><a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a><span class="soul">生活志</span></h1>
                <?php else: ?>
                <a class="logo" href="<?php $this->options->siteUrl(); ?>">
                    <img src="<?php echo $this->options->logoWhite ? $this->options->logoWhite : $this->options->themeUrl . '/static/img/logoWhite.svg'; ?>">
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="m">
            <h1 class="page-head"><?php $this->title(); ?><span><?php echo $this->fields->author ? $this->fields->author : $this->author(); ?></span></h1>
        </div>
    </div>
    <div class="book-detail">
        <div class="book-header">
            <div class="book-cover">
                <img src="<?php echo showThumbnail($this); ?>" alt="<?php $this->title(); ?>">
            </div>
            <div class="book-info">
                <h1><?php $this->title(); ?></h1>
                <p class="book-author"><span>作者：</span><?php echo $this->fields->author ? $this->fields->author : $this->author(); ?></p>
                <?php if ($this->fields->bookYear): ?>
                <p class="book-year"><span>出版日期：</span><?php echo htmlspecialchars($this->fields->bookYear); ?></p>
                <?php endif; ?>
                <?php if ($this->fields->bookCat): ?>
                <p class="book-cat"><span>书籍分类：</span><?php echo htmlspecialchars($this->fields->bookCat); ?></p>
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
                <?php if ($this->fields->note): ?>
                <div class="note-content">
                    <?php echo AutoLightbox($this->fields->note); ?>
                </div>
                <?php else: ?>
                <div class="nodata">
                    <img src="<?php $this->options->themeUrl('static/img/nodata.svg'); ?>">
                    <span>博主暂未发布本书的读书笔记。</span>
                </div>
                <?php endif; ?>
            </div>
            <div class="book-summary" id="book-summary">
                <?php if (trim($this->content)): ?>
                <div class="summary-content">
                    <?php echo AutoLightbox($this->content); ?>
                </div>
                <?php else: ?>
                <div class="nodata">
                    <img src="<?php $this->options->themeUrl('static/img/nodata.svg'); ?>">
                    <span>博主很懒，暂未填写本书简介。</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<script src="<?php $this->options->themeUrl('static/js/main.js'); ?>"></script>
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
