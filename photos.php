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
    
    <div class="category-header m blur" style="background-image: url('<?php $this->options->themeUrl('/static/img/photo.jpg'); ?>');">
        <div class="category-info">
            <h1>相册</h1>
            <span>记录生活中的美好瞬间</span>
        </div>
    </div>
    
    <div class="photos-container blur">
        <?php 
        // 从插件数据表获取照片
        try {
            $db = Typecho_Db::get();
            $prefix = $db->getPrefix();
            $photos = $db->fetchAll($db->select()->from($prefix . 'content_manager_photos')->order('created', Typecho_Db::SORT_DESC));
            
            if (!empty($photos)): 
        ?>
        <div class="photos-grid">
            <?php foreach ($photos as $photo): ?>
            <div class="photo-item">
                <a data-fancybox="gallery" data-caption="<?php echo $photo['title']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo date('M d, Y', $photo['created']); ?>&nbsp;&nbsp;&nbsp;&nbsp;©&nbsp;<?php echo $photo['description']; ?>" href="<?php echo $photo['image']; ?>">
                    <img class="lazy-load" data-src="<?php echo $photo['image']; ?>" src="<?php echo $photo['image']; ?>">
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="nodata blur">
            <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
            <span>暂无照片</span>
        </div>
        <?php endif; 
        } catch (Exception $e) {
            echo '<div class="nodata blur">
                <span>加载失败: ' . htmlspecialchars($e->getMessage()) . '</span>
            </div>';
        }
        ?>
    </div>
</div>

<?php $this->need('footer.php');?>
