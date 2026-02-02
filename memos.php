<?php
/**
 * 微语页面
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$export = Typecho_Plugin::export();
$memosImageEnabled = isset($export['activated']['MemosImage']);
?>
<meta name="csrf-token" content="<?php echo Helper::security()->getToken($this->request->getRequestUrl()); ?>">
<meta name="comment-url" content="<?php $this->commentUrl(); ?>">

<style>
.memos-img-grid {
    display: grid;
    gap: 8px;
    margin-top: 12px;
    grid-template-columns: repeat(3, 1fr);
}
.memos-img-grid.grid-1 {
    grid-template-columns: 1fr;
    max-width: 400px;
}
.memos-img-grid.grid-2 {
    grid-template-columns: repeat(2, 1fr);
    max-width: 400px;
}
.memos-img-grid.grid-1 img,
.memos-img-grid.grid-2 img {
    aspect-ratio: 16/10;
    object-fit: cover;
}
.memos-img-grid a {
    display: block;
    overflow: hidden;
    border-radius: 8px;
}
.memos-img-grid img {
    width: 100%;
    height: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    transition: transform 0.3s ease;
}
.memos-img-grid img:hover {
    transform: scale(1.05);
}
@media (max-width: 480px) {
    .memos-img-grid {
        gap: 4px;
    }
    .memos-img-grid.grid-1,
    .memos-img-grid.grid-2 {
        max-width: 100%;
    }
}
.memos-video {
    margin-top: 12px;
    position: relative;
    width: 100%;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    border-radius: 8px;
    background: #000;
}
.memos-video iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}
@media (max-width: 480px) {
    .memos-video {
        margin-top: 8px;
        border-radius: 4px;
    }
}
</style>

<div class="main">
<?php $this->need('module/head2.php'); ?>

<div class="page_thumb blur">
    <div class="post_bg lazy-load"
         data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : Helper::options()->themeUrl . '/static/img/memos.jpg'; ?>">
    </div>

    <div class="pc">
        <i class="iconfont icon-nav menu-button"></i>
        <div class="page-head">
            <?php if ($this->options->logoStyle == 'text') : ?>
                <h1>
                    <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
                    <span class="soul">生活志</span>
                </h1>
            <?php else : ?>
                <a class="logo" href="<?php $this->options->siteUrl(); ?>">
                    <img src="<?php echo $this->options->logoWhite ?: Helper::options()->themeUrl . '/static/img/logoWhite.svg'; ?>">
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="m">
        <h1 class="page-head">
            <?php $this->archiveTitle(' &raquo; ', ''); ?>
            <span>A fleeting inspiration</span>
        </h1>
    </div>

    <div class="memos-btn">
        <?php if ($this->user->hasLogin()) : ?>
            <button id="publish-button">发布</button>
        <?php else : ?>
            <button id="login-button">登录</button>
        <?php endif; ?>
    </div>
</div>

<!-- 微语列表 -->
<div id="comments" class="memos padding animated fadeIn blur">
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()) : ?>
        <ul class="comment-list">
            <?php while ($comments->next()) : ?>
                <?php MemosList($comments, $this->user); ?>
            <?php endwhile; ?>
        </ul>

        <?php $comments->pageNav('', ''); ?>

        <div class="load" id="load-more-comments">加载更多动态</div>

        <div id="loading-spinner" style="display: none;">
            <div class="spinner"></div><span>加载中...</span>
        </div>

        <div class="load" id="no-more" style="display: none;">
            —&nbsp;已加载全部数据&nbsp;—
        </div>
    <?php endif; ?>
</div>

<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>

<script>
    var loginAction = "<?php echo $this->options->loginAction(); ?>";
    var commentLikeUrl = "<?php Helper::options()->index('?commentLike=dz'); ?>";
    <?php if ($memosImageEnabled) : ?>
    <?php $plugin = $this->options->plugin('MemosImage'); ?>
    window.memosConfig = Object.assign({}, window.memosConfig || {}, {
        enabled: true,
        memosUseCos: "<?php echo $plugin->uploadMode ?: 'local'; ?>" === 'cos',
        memosUploadUrl: "<?php Helper::options()->index('/action/memos-upload'); ?>",
        memosSignUrl: "<?php Helper::options()->index('/action/memos-sign'); ?>"
    });
    <?php endif; ?>
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof jQuery !== 'undefined' && typeof jQuery.fancybox !== 'undefined') {
        jQuery('[data-fancybox^="memos-"]').fancybox({
        loop: true,
        buttons: [
            'slideShow',
            'fullScreen',
            'thumbs',
            'close'
        ],
        protect: true,
        caption: function(fancybox, slide) {
            return slide.caption || '';
        }
        });
    }
});
</script>

<?php $this->need('footer.php'); ?>