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

.book-author {
    font-size: 0.9rem;
    color: #666;
    text-align: center;
    margin-top: 5px;
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
    
    <div class="category-header m blur" style="background-image: url('<?php $this->options->themeUrl('/static/img/bg.jpg'); ?>');">
        <div class="category-info">
            <h1>书单</h1>
            <span>记录读过的每一本书</span>
        </div>
    </div>
    
    <div class="books-container blur">
        <?php 
        // 从插件数据表获取书籍
        try {
            $db = Typecho_Db::get();
            $prefix = $db->getPrefix();
            $books = $db->fetchAll($db->select()->from($prefix . 'content_manager_books')->order('created', Typecho_Db::SORT_DESC));
            
            if (!empty($books)): 
        ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
            <div class="book-item">
                <div class="book-thumb">
                    <img class="lazy-load" data-src="<?php echo $book['cover']; ?>" src="<?php echo $book['cover']; ?>">
                </div>
                <div class="book-name"><?php echo $book['title']; ?></div>
                <div class="book-author"><?php echo $book['author']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="nodata blur">
            <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
            <span>暂无书籍</span>
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
