<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * 书单页面
 *
 * @package custom
 */
$this->need('header.php');
?>
<style>
/* 书单页面样式 */
.books-container {
    padding: 20px;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 30px;
    margin: 40px 0;
}

.book-item {
    display: block;
    text-decoration: none;
    color: #333;
    transition: transform 0.3s ease;
}

.book-item:hover {
    transform: translateY(-5px);
}

.book-thumb {
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin-bottom: 12px;
}

.book-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-item:hover .book-thumb img {
    transform: scale(1.05);
}

.book-name {
    font-size: 1rem;
    font-weight: 500;
    text-align: center;
    line-height: 1.4;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}

@media (max-width: 768px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 20px;
    }
    
    .book-thumb {
        height: 200px;
    }
}

@media (max-width: 480px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .book-thumb {
        height: 180px;
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
    
    <div class="books-container blur">
        <?php if ($this->have()): ?>
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
