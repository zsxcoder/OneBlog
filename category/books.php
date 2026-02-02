<?php
/**
 * 书单列表页面
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');
?>
<div class="main">
    <?php $this->need('module/head2.php'); ?>
    <div class="page_thumb blur">
        <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : $this->options->themeUrl . '/static/img/book.jpg'; ?>"></div>
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
            <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>书单</span></h1>
        </div>
    </div>
    <div class="page-title animated fadeIn pc">
        <h1><?php $this->archiveTitle(' &raquo; ', ''); ?></h1>
    </div>
    <div class="post_content animated fadeIn">
        <div class="books" id="books">
            <?php while($this->next()): ?>
            <a href="<?php $this->permalink() ?>" class="book">
                <div class="book-thumb lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : $this->options->themeUrl . '/static/img/bg.jpg'; ?>">
                </div>
                <div class="book-name"><?php echo $this->title ? $this->title : '请填写书名'; ?></div>
            </a>
            <?php endwhile; ?>
        </div>
        <div class="pageload" id="no_more">
            <?php $this->pageNav('', ''); ?>
        </div>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<?php $this->need('footer.php'); ?>
