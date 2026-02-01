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

<?php if ($memosImageEnabled): ?>
<link rel="stylesheet" href="<?php Helper::options()->pluginUrl('MemosImage/style.css'); ?>" type="text/css" />
<?php endif; ?>

<style>
#respond .rich-editor {
    min-height: 100px;
}
#memos-upload-area {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    margin: 10px 0;
    gap: 8px;
}
#memos-image-input {
    display: none;
}
</style>

<?php if ($memosImageEnabled && $this->user->hasLogin()): ?>
<script src="<?php Helper::options()->pluginUrl('MemosImage/upload.js'); ?>"></script>
<?php endif; ?>

<script>
var loginAction = "<?php echo $this->options->loginAction(); ?>";
var commentLikeUrl = "<?php Helper::options()->index('?commentLike=dz'); ?>";
<?php if ($memosImageEnabled): ?>
<?php $plugin = $this->options->plugin('MemosImage'); ?>
window.memosConfig = Object.assign({}, window.memosConfig || {}, {
    enabled: true,
    uploadMode: "<?php echo $plugin->uploadMode ?: 'local'; ?>",
    uploadUrl: "<?php Helper::options()->index('/action/memos-upload'); ?>",
    signUrl: "<?php Helper::options()->index('/action/memos-sign'); ?>"
});
<?php endif; ?>
</script>

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
document.addEventListener('DOMContentLoaded', function() {
    var publishBtn = document.getElementById('publish-button');
    var loginBtn = document.getElementById('login-button');

    if (publishBtn) {
        publishBtn.addEventListener('click', function() {
            var respond = document.getElementById('respond');
            if (respond) {
                respond.scrollIntoView({ behavior: 'smooth' });
                var editor = respond.querySelector('.rich-editor');
                if (editor) {
                    editor.focus();
                }
            }
        });
    }

    if (loginBtn) {
        loginBtn.addEventListener('click', function() {
            if (typeof layer !== 'undefined') {
                layer.open({
                    type: 2,
                    title: '登录',
                    area: ['400px', '450px'],
                    content: loginAction
                });
            } else {
                var loginForm = document.querySelector('.memos-form');
                if (loginForm) {
                    loginForm.style.display = 'flex';
                }
            }
        });
    }

    var commentForm = document.getElementById('comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            var textarea = commentForm.querySelector('textarea[name="text"]');
            var editor = commentForm.querySelector('.rich-editor');
            var memosImgs = commentForm.querySelector('input[name="memos_imgs"]');

            if (editor && editor.innerHTML) {
                textarea.value = editor.innerHTML;
            }

            if (memosImgs && !memosImgs.value && editor && editor.innerHTML.trim() === '') {
                e.preventDefault();
                alert('请输入内容或上传图片');
                return false;
            }
        });
    }

    if (typeof MemosUploader !== 'undefined') {
        MemosUploader.init();
    }
});
</script>

<?php $this->need('footer.php'); ?>
