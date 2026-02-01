<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 相册页面
 *
 * @package custom
 */
$this->need('header.php');
?>
<style>
@import url('<?php $this->options->themeUrl('static/css/books-photos.css'); ?>');
</style>

<div class="page-banner" style="background-image:url('<?php echo $this->getPageRow()['description'] ? $this->getPageRow()['description'] : $this->options->themeUrl . '/static/img/photo.jpg';?>')">
    <div class="banner-content">
        <h1><?php $this->archiveTitle(' &raquo; ', ''); ?></h1>
        <p><?php echo $this->options->slogan ? $this->options->slogan : '自豪地使用OneBlog主题';?></p>
    </div>
</div>

<div class="container">
    <div class="content">
        <div class="photos-grid">
            <?php while($this->next()): ?>
            <div class="photo-item">
                <a data-fancybox="gallery" data-caption="<?php $this->title(); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php $this->date('M d, Y'); ?>&nbsp;&nbsp;&nbsp;&nbsp;©&nbsp;<?php echo $this->fields->author ? $this->fields->author() : $this->author(); ?>" href="<?php echo $this->fields->photo ? $this->fields->photo() : $this->fields->thumb(); ?>">
                    <img class="lazy-load" data-src="<?php echo $this->fields->thumb(); ?>" src="<?php echo $this->fields->thumb(); ?>">
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        
        <div class="pagination">
            <?php $this->pageLink('加载更多','next'); ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php');?>
