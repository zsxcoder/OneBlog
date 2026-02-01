<?php
/**
 * 照片详情页
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$id = isset($this->request->id) ? (int)$this->request->id : 0;
if (!$id) {
    $this->response->redirect($this->options->siteUrl);
}

$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$photo = $db->fetchRow($db->select()->from($prefix . 'photo_collection')->where('id = ?', $id));

if (!$photo) {
    $this->response->redirect($this->options->siteUrl);
}

$options = Helper::options();
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0, width=device-width"/>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?php echo htmlspecialchars($photo['title']); ?> - <?php $options->title(); ?></title>
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
        <div class="post_bg lazy-load" data-src="<?php echo htmlspecialchars($photo['image']); ?>"></div>
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
            <h1 class="page-head"><?php echo htmlspecialchars($photo['title']); ?></h1>
        </div>
    </div>
    <div class="photo-detail">
        <div class="photo-view">
            <a href="<?php echo htmlspecialchars($photo['image']); ?>" data-fancybox="photo" 
               data-caption="<?php echo htmlspecialchars($photo['title']); ?>
               <?php if (!empty($photo['author'])): ?> © <?php echo htmlspecialchars($photo['author']); ?><?php endif; ?>
               <?php if (!empty($photo['location'])): ?> · <?php echo htmlspecialchars($photo['location']); ?><?php endif; ?>
               <?php if (!empty($photo['taken_at'])): ?> · <?php echo date('Y.m.d', strtotime($photo['taken_at'])); ?><?php endif; ?>">
                <img src="<?php echo htmlspecialchars($photo['image']); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
            </a>
        </div>
        <div class="photo-info">
            <h1><?php echo htmlspecialchars($photo['title']); ?></h1>
            <?php if (!empty($photo['author'])): ?>
            <p class="photo-author"><span>摄影：</span><?php echo htmlspecialchars($photo['author']); ?></p>
            <?php endif; ?>
            <?php if (!empty($photo['location'])): ?>
            <p class="photo-location"><span>地点：</span><?php echo htmlspecialchars($photo['location']); ?></p>
            <?php endif; ?>
            <?php if (!empty($photo['taken_at'])): ?>
            <p class="photo-date"><span>时间：</span><?php echo htmlspecialchars(date('Y年m月d日 H:i', strtotime($photo['taken_at']))); ?></p>
            <?php endif; ?>
            <?php if (!empty($photo['description'])): ?>
            <div class="photo-description">
                <p><?php echo nl2br(htmlspecialchars($photo['description'])); ?></p>
            </div>
            <?php endif; ?>
            <div class="photo-copyright">
                <p>本摄影作品著作权归作者 <?php if (!empty($photo['author'])): ?>[&nbsp;<?php echo htmlspecialchars($photo['author']); ?>&nbsp;]<?php else: ?>[&nbsp;博主&nbsp;]<?php endif; ?> 享有。</p>
                <p>未经作者书面授权，禁止以任何目的、任何形式转载或使用。</p>
            </div>
        </div>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<script src="<?php $options->themeUrl('static/js/main.js'); ?>"></script>
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
