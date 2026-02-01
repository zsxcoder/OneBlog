<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 相册页面
 *
 * @package custom
 */
$this->need('header.php');
?>
<style>
/* 相册页面样式 */
.photos-container {
    padding: 20px;
}

.photos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    margin: 40px 0;
}

.photo-item {
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.photo-item:hover {
    transform: translateY(-5px);
}

.photo-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.photo-item:hover img {
    transform: scale(1.05);
}

@media (max-width: 768px) {
    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 15px;
    }
    
    .photo-item img {
        height: 150px;
    }
}

@media (max-width: 480px) {
    .photos-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 10px;
    }
    
    .photo-item img {
        height: 120px;
    }
}
</style>

<div class="main">
    <?php $this->need('module/head.php'); ?>
    
    <?php if ($this->is('category')): ?>
    <div class="category-header m blur" style="background-image: url('<?php $info = CatInfo($this->getDescription()); echo $info['img']; ?>');">
        <div class="category-info">
            <h1><?php $this->archiveTitle('%s', '', ''); ?></h1>
            <span><?php echo $info['info']; ?></span>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="photos-container blur">
        <?php if ($this->have()): ?>
        <div class="photos-grid">
            <?php while($this->next()): ?>
            <div class="photo-item">
                <a data-fancybox="gallery" data-caption="<?php $this->title(); ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php $this->date('M d, Y'); ?>&nbsp;&nbsp;&nbsp;&nbsp;©&nbsp;<?php echo $this->fields->author ? $this->fields->author() : $this->author(); ?>" href="<?php echo $this->fields->photo ? $this->fields->photo() : $this->fields->thumb(); ?>">
                    <img class="lazy-load" data-src="<?php echo $this->fields->thumb(); ?>" src="<?php echo $this->fields->thumb(); ?>">
                </a>
            </div>
            <?php endwhile; ?>
        </div>
        
        <div class="load blur" id="loadmore">
             <?php $this->pageLink('点击查看更多','next'); ?>
        </div>
        <?php else: ?>
        <div class="nodata blur">
            <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
            <span>暂无相关内容</span>
            <a href="<?php $this->options->siteUrl(); ?>">返回首页</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php $this->need('footer.php');?>
