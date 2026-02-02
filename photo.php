<?php
/**
 * 照片详情页
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
        <div class="post_bg lazy-load" data-src="<?php echo $this->fields->photo ? $this->fields->photo : showThumbnail($this); ?>"></div>
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
            <h1 class="page-head"><?php $this->title(); ?></h1>
        </div>
    </div>
    <div class="photo-detail">
        <div class="photo-view">
            <?php $displayImage = $this->fields->photo ? $this->fields->photo : showThumbnail($this); ?>
            <?php $caption = $this->title; ?>
            <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;' . $this->date('M d, Y'); ?>
            <?php if ($this->fields->author): ?>
            <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;© ' . htmlspecialchars($this->fields->author); ?>
            <?php else: ?>
            <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;© ' . htmlspecialchars($this->author()); ?>
            <?php endif; ?>
            <a href="<?php echo htmlspecialchars($displayImage); ?>" data-fancybox="photo" 
               data-caption="<?php echo $caption; ?>">
                <img src="<?php echo htmlspecialchars($displayImage); ?>" alt="<?php $this->title(); ?>">
            </a>
        </div>
        <div class="photo-info">
            <h1><?php $this->title(); ?></h1>
            <div class="post_meta">
                <span><?php $this->date('Y.m.d'); ?></span>
                <span><?php $firstCat = $this->categories[0]; ?><a href="<?php echo $firstCat['permalink']; ?>"><?php echo $firstCat['name']; ?></a></span>
                <span><?php get_post_view($this) ?>&nbsp;阅读</span>
            </div>
            <?php echo AutoLightbox($this->content); ?>
            <div class="cc-say" style="margin-bottom:30px">
                本摄影作品著作权归作者 [&nbsp;<span><?php echo $this->fields->author ? $this->fields->author : $this->author(); ?></span>&nbsp;] 享有，未经作者书面授权，禁止以任何目的、任何形式转载或使用，本声明具有法律效力，作者保留法律范围内的一切权利。
            </div>
        </div>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<script src="<?php $this->options->themeUrl('static/js/main.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('[data-fancybox]').fancybox({
        loop: true,
        buttons: ['zoom', 'slideShow', 'fullScreen', 'download', 'thumbs', 'close'],
        caption: function(fancybox, slide) {
            return slide.opts.$orig.data('caption') || '';
        }
    });
});
</script>
</body>
</html>
