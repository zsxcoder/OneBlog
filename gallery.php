<?php
/**
 * 相册页面
 *
 * @package custom
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php'); ?>

<div class="main">
<?php $this->need('module/head2.php');?>

<!--背景图片+页面标题-->
<div class="page_thumb blur">
    <!-- 背景图片容器 -->
    <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : Helper::options()->themeUrl . '/static/img/photo.jpg';?>"></div>
    
    <div class="pc">
        <!-- 新增的菜单按钮 -->
        <i class="iconfont icon-nav menu-button"></i>
        <div class="page-head">
            <?php if ($this->options->logoStyle == 'text') {?>
            <h1><a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title();?></a><span class="soul">生活志</span></h1>
            <?php }else{ ?>
            <a class="logo" href="<?php $this->options->siteUrl(); ?>">
                <img src="<?php echo $this->options->logoWhite ? $this->options->logoWhite : Helper::options()->themeUrl . '/static/img/logoWhite.svg'; ?>">
            </a>
            <?php }?>
        </div>
    </div>
    <div class="m">
        <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>My Gallery</span></h1> 
    </div>
</div>

<!--页面标题-->
<div class="page-title animated fadeIn pc">
    <h1><?php $this->title(); ?></h1>   
</div>

<!--照片统计-->
<div class="gallery-stats padding blur">
    <div class="stats-item">
        <span class="stats-number" id="total-photos">0</span>
        <span class="stats-label">张照片</span>
    </div>
    <div class="stats-item">
        <span class="stats-number" id="total-albums">0</span>
        <span class="stats-label">个相册</span>
    </div>
</div>

<!--相册分类-->
<div class="gallery-categories padding blur">
    <span class="category-tag active" data-category="all">全部</span>
    <span class="category-tag" data-category="life">生活</span>
    <span class="category-tag" data-category="travel">旅行</span>
    <span class="category-tag" data-category="scenery">风景</span>
    <span class="category-tag" data-category="food">美食</span>
    <span class="category-tag" data-category="other">其他</span>
</div>

<!--照片网格-->
<div class="gallery-container padding blur">
    <div class="gallery-grid" id="gallery-grid">
        <!--照片将通过JS动态加载-->
    </div>
    <div class="load-more-container">
        <button id="load-more-photos" class="load-more gradient-card">
            加载更多
        </button>
    </div>
</div>

<style>
/* 相册页面样式 */
.gallery-stats {
    display: flex;
    justify-content: center;
    gap: 3rem;
    margin: 1rem 0;
    padding: 1.5rem;
}

.stats-item {
    text-align: center;
    padding: 1rem;
}

.stats-number {
    display: block;
    font-size: 2rem;
    font-weight: bold;
    color: var(--theme-color);
}

.stats-label {
    display: block;
    font-size: 0.875rem;
    color: var(--c-text-3);
    margin-top: 0.5rem;
}

.gallery-categories {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem;
    margin: 1rem 0;
    padding: 1rem;
}

.category-tag {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    background: var(--c-bg-soft);
    color: var(--c-text-2);
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.category-tag:hover,
.category-tag.active {
    background: var(--theme-color);
    color: #fff;
}

.gallery-container {
    margin-bottom: 20px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1rem;
    margin: 1rem 0;
}

.gallery-item {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    cursor: pointer;
    aspect-ratio: 1;
    background: var(--c-bg-soft);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.gallery-item .overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
    padding: 1rem;
}

.gallery-item:hover .overlay {
    opacity: 1;
}

.gallery-item .overlay .photo-title {
    color: #fff;
    font-size: 0.875rem;
    font-weight: 500;
}

.gallery-item .overlay .photo-date {
    color: rgba(255,255,255,0.7);
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.gallery-item .category-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    color: #fff;
    background: rgba(0,0,0,0.5);
}

.gallery-item .photo-description {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem 1rem 1rem;
    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
    color: #fff;
    font-size: 0.8125rem;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}

.gallery-item:hover .photo-description {
    transform: translateY(0);
}

.load-more-container {
    text-align: center;
    margin: 1rem 0;
}

.load-more-container .load-more {
    border-radius: 8px;
    box-shadow: 0 0 0 1px var(--c-bg-soft);
    background-color: var(--ld-bg-card);
    border: none;
    box-shadow: .1em .2em .5rem var(--ld-shadow);
    display: inline-block;
    font-size: .875rem;
    height: 42px;
    padding: .75rem;
    width: 200px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.load-more-container .load-more:hover:not(:disabled) {
    color: var(--c-text);
}

.load-more-container .load-more:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.nodata {
    text-align: center;
    padding: 3rem 1rem;
    color: var(--c-text-3);
}

.nodata img {
    width: 120px;
    height: 120px;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* 响应式设计 */
@media (max-width: 768px) {
    .gallery-stats {
        gap: 2rem;
    }
    
    .gallery-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 0.5rem;
    }
    
    .gallery-item .overlay {
        opacity: 1;
        background: linear-gradient(to top, rgba(0,0,0,0.6), transparent 30%);
    }
    
    .gallery-item .photo-description {
        transform: translateY(0);
        padding: 1.5rem 0.75rem 0.75rem;
    }
}
</style>

<script>
// 相册配置
const GalleryConfig = {
    photosPerPage: 8,
    totalPhotos: 0,
    currentPage: 1,
    currentCategory: 'all'
};

// 相册数据（示例数据，可根据实际情况修改）
const photosData = [
    {
        id: 1,
        title: "城市夜景",
        cover: "https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=600",
        fullUrl: "https://images.unsplash.com/photo-1519501025264-65ba15a82390?w=1600",
        date: "2025-01-15",
        category: "scenery",
        description: "城市灯火阑珊的夜晚"
    },
    {
        id: 2,
        title: "山间日出",
        cover: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600",
        fullUrl: "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1600",
        date: "2025-01-10",
        category: "scenery",
        description: "清晨的山间日出景观"
    },
    {
        id: 3,
        title: "海边日落",
        cover: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600",
        fullUrl: "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1600",
        date: "2025-01-05",
        category: "travel",
        description: "海边日落时分的美景"
    },
    {
        id: 4,
        title: "咖啡时光",
        cover: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=600",
        fullUrl: "https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1600",
        date: "2025-01-01",
        category: "food",
        description: "一杯咖啡，一段悠闲时光"
    },
    {
        id: 5,
        title: "猫咪日常",
        cover: "https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=600",
        fullUrl: "https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=1600",
        date: "2024-12-28",
        category: "life",
        description: "家里的小猫咪"
    },
    {
        id: 6,
        title: "森林小径",
        cover: "https://images.unsplash.com/photo-1448375240586-882707db888b?w=600",
        fullUrl: "https://images.unsplash.com/photo-1448375240586-882707db888b?w=1600",
        date: "2024-12-25",
        category: "scenery",
        description: "森林里的小径"
    },
    {
        id: 7,
        title: "圣诞装饰",
        cover: "https://images.unsplash.com/photo-1512389142860-9c449e58a543?w=600",
        fullUrl: "https://images.unsplash.com/photo-1512389142860-9c449e58a543?w=1600",
        date: "2024-12-24",
        category: "life",
        description: "温馨的圣诞装饰"
    },
    {
        id: 8,
        title: "日式料理",
        cover: "https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=600",
        fullUrl: "https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=1600",
        date: "2024-12-20",
        category: "food",
        description: "精致的日式料理"
    },
    {
        id: 9,
        title: "雪山之巅",
        cover: "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?w=600",
        fullUrl: "https://images.unsplash.com/photo-1454496522488-7a8e488e8606?w=1600",
        date: "2024-12-15",
        category: "travel",
        description: "攀登雪山的壮丽景色"
    },
    {
        id: 10,
        title: "樱花盛开",
        cover: "https://images.unsplash.com/photo-1522383225653-ed111181a951?w=600",
        fullUrl: "https://images.unsplash.com/photo-1522383225653-ed111181a951?w=1600",
        date: "2024-12-10",
        category: "scenery",
        description: "春天樱花盛开的时节"
    },
    {
        id: 11,
        title: "烘焙时光",
        cover: "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600",
        fullUrl: "https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1600",
        date: "2024-12-05",
        category: "food",
        description: "自己烘焙的面包"
    },
    {
        id: 12,
        title: "城市街角",
        cover: "https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=600",
        fullUrl: "https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?w=1600",
        date: "2024-12-01",
        category: "other",
        description: "城市街角的日常"
    }
];

// 状态管理
let allPhotos = [];
let displayedPhotos = [];
let start = 0;
let hasMorePhotos = true;

// 从PHP预获取的照片数据
const phpPhotosData = <?php 
    // 获取自定义字段中的相册数据
    $photosJson = $this->fields->photos ? $this->fields->photos : '[]';
    echo $photosJson;
?>;

// 分类名称映射
const categoryNames = {
    'all': '全部',
    'life': '生活',
    'travel': '旅行',
    'scenery': '风景',
    'food': '美食',
    'other': '其他'
};

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    // 合并数据
    allPhotos = [...phpPhotosData.length > 0 ? phpPhotosData : photosData];
    
    // 初始化统计数据
    initStats();
    
    // 初始化分类筛选
    initCategories();
    
    // 初始加载照片
    loadPhotos();
    
    // 事件监听
    document.getElementById('load-more-photos').addEventListener('click', loadMorePhotos);
});

// 初始化统计数据
function initStats() {
    const totalPhotos = allPhotos.length;
    const albums = new Set(allPhotos.map(p => p.category)).size;
    
    animateNumber('total-photos', totalPhotos);
    animateNumber('total-albums', albums);
}

// 数字动画
function animateNumber(elementId, targetNumber) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    let currentNumber = 0;
    const duration = 1000;
    const increment = targetNumber / (duration / 16);
    
    function update() {
        currentNumber += increment;
        if (currentNumber < targetNumber) {
            element.textContent = Math.floor(currentNumber);
            requestAnimationFrame(update);
        } else {
            element.textContent = targetNumber;
        }
    }
    
    update();
}

// 初始化分类筛选
function initCategories() {
    const categoryTags = document.querySelectorAll('.category-tag');
    categoryTags.forEach(tag => {
        tag.addEventListener('click', function() {
            categoryTags.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            GalleryConfig.currentCategory = this.dataset.category;
            GalleryConfig.currentPage = 1;
            start = 0;
            displayedPhotos = [];
            
            loadPhotos();
        });
    });
}

// 加载照片
function loadPhotos() {
    const galleryGrid = document.getElementById('gallery-grid');
    if (!galleryGrid) return;
    
    // 清空当前显示
    galleryGrid.innerHTML = '';
    
    // 根据分类筛选
    let filteredPhotos = allPhotos;
    if (GalleryConfig.currentCategory !== 'all') {
        filteredPhotos = allPhotos.filter(photo => photo.category === GalleryConfig.currentCategory);
    }
    
    GalleryConfig.totalPhotos = filteredPhotos.length;
    
    // 加载更多照片
    const newDisplayed = filteredPhotos.slice(start, start + GalleryConfig.photosPerPage);
    displayedPhotos = [...displayedPhotos, ...newDisplayed];
    
    // 渲染照片
    newDisplayed.forEach((photo, index) => {
        renderPhoto(photo, index, galleryGrid);
    });
    
    // 更新起始位置
    start += GalleryConfig.photosPerPage;
    
    // 检查是否有更多照片
    hasMorePhotos = start < filteredPhotos.length;
    
    // 更新加载更多按钮状态
    const loadMoreBtn = document.getElementById('load-more-photos');
    if (loadMoreBtn) {
        if (hasMorePhotos) {
            loadMoreBtn.style.display = 'inline-block';
            loadMoreBtn.disabled = false;
        } else {
            loadMoreBtn.style.display = 'none';
            loadMoreBtn.disabled = true;
        }
    }
    
    // 如果没有照片，显示空状态
    if (filteredPhotos.length === 0) {
        galleryGrid.innerHTML = `
            <div class="nodata" style="grid-column: 1 / -1;">
                <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
                <span>暂无照片记录</span>
            </div>
        `;
    }
}

// 加载更多照片
function loadMorePhotos() {
    loadPhotos();
}

// 渲染照片卡片
function renderPhoto(photo, index, container) {
    const galleryItem = document.createElement('div');
    galleryItem.className = 'gallery-item new-item';
    galleryItem.style.setProperty('--delay', index * 0.05 + 's');
    
    const fullUrl = photo.fullUrl || photo.cover;
    const categoryName = categoryNames[photo.category] || photo.category;
    
    galleryItem.innerHTML = `
        <img src="${photo.cover}" alt="${photo.title}" onerror="this.src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'">
        <span class="category-badge">${categoryName}</span>
        <div class="overlay">
            <div>
                <div class="photo-title">${photo.title}</div>
                <div class="photo-date">${photo.date || ''}</div>
            </div>
        </div>
        ${photo.description ? `<div class="photo-description">${photo.description}</div>` : ''}
    `;
    
    // 使用 jQuery 和 FancyBox 打开大图
    galleryItem.addEventListener('click', function() {
        const caption = `${photo.title}${photo.date ? '<br><small>' + photo.date + '</small>' : ''}${photo.description ? '<br><small>' + photo.description + '</small>' : ''}`;
        
        // 创建隐藏的 a 标签，使用 FancyBox
        const $link = $('<a>', {
            href: fullUrl,
            'data-fancybox': 'gallery',
            'data-caption': caption
        });
        
        $('body').append($link);
        $link[0].click();
        $link.remove();
    });
    
    container.appendChild(galleryItem);
}
</script>

<!--页面说明文字-->
<div class="post_content padding animated fadeIn blur">
    <h4 class="link-request">
        <span>#</span>
        相册说明
    </h4>
    <?php echo AutoLightbox($this->content);?>
</div>

<?php $this->need('comments.php'); ?>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>

<?php $this->need('footer.php'); ?>
