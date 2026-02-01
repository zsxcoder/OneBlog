<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 书单页面
 *
 * @package custom
 */
$this->need('header.php');
?>
<style>
@import url('<?php $this->options->themeUrl('static/css/books-photos.css'); ?>');
</style>

<div class="page-banner" style="background-image:url('<?php echo $this->getPageRow()['description'] ? $this->getPageRow()['description'] : $this->options->themeUrl . '/static/img/bg.jpg';?>')">
    <div class="banner-content">
        <h1><?php $this->archiveTitle(' &raquo; ', ''); ?></h1>
        <p><?php echo $this->options->slogan ? $this->options->slogan : '自豪地使用OneBlog主题';?></p>
    </div>
</div>

<div class="container">
    <div class="content">
        <div class="books-grid">
            <?php while($this->next()): ?>
            <a href="<?php $this->permalink() ?>" class="book-item">
                <div class="book-thumb">
                    <img class="lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : $this->options->themeUrl . '/static/img/bg.jpg'; ?>" src="<?php echo $this->fields->thumb ? $this->fields->thumb : $this->options->themeUrl . '/static/img/bg.jpg'; ?>">
                </div>
                <div class="book-name"><?php echo $this->title ? $this->title : '请填写书名'; ?></div>
            </a>
            <?php endwhile; ?>
        </div>
        
        <div class="pagination">
            <?php $this->pageLink('加载更多','next'); ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php');?>
