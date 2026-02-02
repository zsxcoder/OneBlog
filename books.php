<?php
/**
 * 书单页面
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
    <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : Helper::options()->themeUrl . '/static/img/book.jpg';?>"></div>
    
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
        <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>My Book Collection</span></h1> 
    </div>
</div>

<!--页面标题-->
<div class="page-title animated fadeIn pc">
    <h1><?php $this->title(); ?></h1>   
</div>

<!--书单统计信息-->
<div class="books-stats padding blur">
    <div class="stats-item">
        <span class="stats-number" id="total-books">0</span>
        <span class="stats-label">本书籍</span>
    </div>
    <div class="stats-item">
        <span class="stats-number" id="finished-books">0</span>
        <span class="stats-label">已读完</span>
    </div>
    <div class="stats-item">
        <span class="stats-number" id="reading-books">0</span>
        <span class="stats-label">阅读中</span>
    </div>
    <div class="stats-item">
        <span class="stats-number" id="total-pages">0</span>
        <span class="stats-label">总页数</span>
    </div>
</div>

<!--书单分类标签-->
<div class="books-categories padding blur">
    <span class="category-tag active" data-category="all">全部</span>
    <span class="category-tag" data-category="read">已读完</span>
    <span class="category-tag" data-category="reading">在读</span>
    <span class="category-tag" data-category="want">想读</span>
</div>

<!--书单列表-->
<div class="books-list padding blur">
    <div class="books-grid" id="books-grid">
        <!--书籍将通过JS动态加载-->
    </div>
    <div class="load-more-container">
        <button id="load-more-books" class="load-more gradient-card">
            加载更多
        </button>
    </div>
</div>

<style>
/* 书单页面样式 */
.books-stats {
    display: flex;
    justify-content: space-around;
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

.books-categories {
    display: flex;
    flex-wrap: wrap;
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

.books-list {
    margin-bottom: 20px;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.5rem;
    margin: 1rem 0;
}

.book-card {
    background: var(--c-bg);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 0 0 1px var(--c-bg-soft);
    transition: all 0.3s ease;
    cursor: pointer;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.book-cover {
    position: relative;
    width: 100%;
    padding-top: 140%;
    overflow: hidden;
}

.book-cover img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.book-card:hover .book-cover img {
    transform: scale(1.05);
}

.book-status {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    color: #fff;
}

.book-status.read {
    background: #52c41a;
}

.book-status.reading {
    background: #faad14;
}

.book-status.want {
    background: #1890ff;
}

.book-info {
    padding: 1rem;
}

.book-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--c-text);
    margin-bottom: 0.5rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.book-author {
    font-size: 0.875rem;
    color: var(--c-text-3);
    margin-bottom: 0.5rem;
}

.book-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-bottom: 0.5rem;
}

.rating-stars {
    color: #faad14;
}

.rating-score {
    font-size: 0.875rem;
    color: var(--c-text-2);
    margin-left: 0.5rem;
}

.book-description {
    font-size: 0.8125rem;
    color: var(--c-text-3);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.book-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 0.25rem;
    margin-top: 0.75rem;
}

.book-tag {
    font-size: 0.75rem;
    padding: 0.125rem 0.5rem;
    background: var(--c-bg-soft);
    color: var(--c-text-2);
    border-radius: 4px;
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
    .books-stats {
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .stats-item {
        flex: 1 1 40%;
    }
    
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    
    .book-info {
        padding: 0.75rem;
    }
    
    .book-title {
        font-size: 0.875rem;
    }
    
    .book-description {
        -webkit-line-clamp: 2;
    }
}
</style>

<script>
// 书单数据配置
const BookConfig = {
    // 书籍数据配置
    booksPerPage: 6,
    totalBooks: 0,
    currentPage: 1,
    currentCategory: 'all'
};

// 书单数据（示例数据，可根据实际情况修改）
const booksData = [
    {
        id: 1,
        title: "活着",
        author: "余华",
        cover: "https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=400",
        status: "read",
        rating: 5,
        pages: 200,
        url: "https://book.douban.com/subject/4913064/",
        description: "讲述了农村人福贵悲惨的人生遭遇。福贵本是个阔少爷，可他嗜赌如命，终于赌光了家业，一贫如洗。",
        tags: ["小说", "中国文学", "人生"]
    },
    {
        id: 2,
        title: "百年孤独",
        author: "加西亚·马尔克斯",
        cover: "https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=400",
        status: "read",
        rating: 5,
        pages: 360,
        url: "https://book.douban.com/subject/10572108/",
        description: "一个家族七代人的故事，涵盖了百年间布恩迪亚家族的兴衰荣辱，以及马孔多小镇的建立、发展与毁灭。",
        tags: ["小说", "魔幻现实主义", "拉美文学"]
    },
    {
        id: 3,
        title: "人类简史",
        author: "尤瓦尔·赫拉利",
        cover: "https://images.unsplash.com/photo-1589829085413-56de8ae18c73?w=400",
        status: "reading",
        rating: 4,
        pages: 400,
        url: "https://book.douban.com/subject/26353653/",
        description: "从十万年前有生命迹象开始到21世纪资本、科技交织的人类发展史，探讨了人类如何成为地球主宰。",
        tags: ["历史", "人类学", "科普"]
    },
    {
        id: 4,
        title: "追风筝的人",
        author: "卡勒德·胡赛尼",
        cover: "https://images.unsplash.com/photo-1512820790803-83ca734da794?w=400",
        status: "read",
        rating: 5,
        pages: 280,
        url: "https://book.douban.com/subject/1770782/",
        description: "关于友谊、背叛和救赎的故事。12岁的阿富汗富家少爷阿米尔与仆人哈桑情同手足，却因风筝比赛发生了一件悲惨的事。",
        tags: ["小说", "友情", "救赎"]
    },
    {
        id: 5,
        title: "代码大全",
        author: "史蒂夫·麦康奈尔",
        cover: "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=400",
        status: "reading",
        rating: 4,
        pages: 800,
        url: "https://book.douban.com/subject/1150604/",
        description: "软件构建领域的权威指南，涵盖了软件开发的各个方面，从代码组织到算法选择，从测试到维护。",
        tags: ["技术", "编程", "软件开发"]
    },
    {
        id: 6,
        title: "解忧杂货店",
        author: "东野圭吾",
        cover: "https://images.unsplash.com/photo-1476275466078-4007374efbbe?w=400",
        status: "want",
        rating: 0,
        pages: 300,
        url: "https://book.douban.com/subject/25862578/",
        description: "一家神奇的杂货店，只要写下烦恼投进店前卷帘门的投信口，第二天就会在店后的牛奶箱里得到回答。",
        tags: ["小说", "悬疑", "治愈"]
    },
    {
        id: 7,
        title: "思考，快与慢",
        author: "丹尼尔·卡尼曼",
        cover: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400",
        status: "want",
        rating: 0,
        pages: 420,
        url: "https://book.douban.com/subject/10785583/",
        description: "诺贝尔经济学奖得主丹尼尔·卡尼曼的著作，介绍了人类思维的两种模式：快速、直觉的系统1和缓慢、理性的系统2。",
        tags: ["心理学", "经济学", "思维"]
    },
    {
        id: 8,
        title: "三体",
        author: "刘慈欣",
        cover: "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=400",
        status: "read",
        rating: 5,
        pages: 350,
        url: "https://book.douban.com/subject/6518605/",
        description: "地球文明与三体文明的首次接触，揭开了宇宙文明兴衰的宏大序幕。",
        tags: ["科幻", "中国文学", "宇宙"]
    },
    {
        id: 9,
        title: "设计心理学",
        author: "唐纳德·诺曼",
        cover: "https://images.unsplash.com/photo-1558655146-9f40138edfeb?w=400",
        status: "read",
        rating: 4,
        pages: 280,
        url: "https://book.douban.com/subject/1384277/",
        description: "本书强调优秀的设计应该以用户为中心，介绍了产品设计中的人因工程学原理。",
        tags: ["设计", "用户体验", "心理学"]
    },
    {
        id: 10,
        title: "月亮与六便士",
        author: "毛姆",
        cover: "https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=400",
        status: "read",
        rating: 5,
        pages: 320,
        url: "https://book.douban.com/subject/1851857/",
        description: "一个证券交易所经纪人放弃优渥生活，追求艺术梦想的故事，探讨了理想与现实、艺术与生活的冲突。",
        tags: ["小说", "理想", "艺术"]
    },
    {
        id: 11,
        title: "原则",
        author: "瑞·达利欧",
        cover: "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=400",
        status: "reading",
        rating: 4,
        pages: 500,
        url: "https://book.douban.com/subject/27608224/",
        description: "桥水基金创始人分享的生活和工作原则，涵盖个人成长、团队管理、投资决策等多个方面。",
        tags: ["商业", "管理", "人生"]
    },
    {
        id: 12,
        title: "围城",
        author: "钱钟书",
        cover: "https://images.unsplash.com/photo-1524578271613-d550eacf6090?w=400",
        status: "want",
        rating: 0,
        pages: 380,
        url: "https://book.douban.com/subject/1008148/",
        description: "以讽刺的笔法描写了抗战初期中国知识分子的众生相，展现了人生的种种困境和矛盾。",
        tags: ["小说", "中国文学", "讽刺"]
    }
];

// 状态管理
let allBooks = [];
let displayedBooks = [];
let start = 0;
let hasMoreBooks = true;

// 从PHP预获取的书单数据
const phpBooksData = <?php 
    // 获取自定义字段中的书单数据
    $booksJson = $this->fields->books ? $this->fields->books : '[]';
    echo $booksJson;
?>;

// 初始化
document.addEventListener('DOMContentLoaded', function() {
    // 合并数据
    allBooks = [...phpBooksData.length > 0 ? phpBooksData : booksData];
    
    // 初始化统计数据
    initStats();
    
    // 初始化分类筛选
    initCategories();
    
    // 初始加载书籍
    loadBooks();
    
    // 事件监听
    document.getElementById('load-more-books').addEventListener('click', loadMoreBooks);
});

// 初始化统计数据
function initStats() {
    const totalBooks = allBooks.length;
    const finishedBooks = allBooks.filter(book => book.status === 'read').length;
    const readingBooks = allBooks.filter(book => book.status === 'reading').length;
    const totalPages = allBooks.reduce((sum, book) => sum + (book.pages || 0), 0);
    
    animateNumber('total-books', totalBooks);
    animateNumber('finished-books', finishedBooks);
    animateNumber('reading-books', readingBooks);
    animateNumber('total-pages', totalPages);
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
            
            BookConfig.currentCategory = this.dataset.category;
            BookConfig.currentPage = 1;
            start = 0;
            displayedBooks = [];
            
            loadBooks();
        });
    });
}

// 加载书籍
function loadBooks() {
    const booksGrid = document.getElementById('books-grid');
    if (!booksGrid) return;
    
    // 清空当前显示
    booksGrid.innerHTML = '';
    
    // 根据分类筛选（仅按阅读状态筛选）
    let filteredBooks = allBooks;
    if (BookConfig.currentCategory !== 'all') {
        filteredBooks = allBooks.filter(book => book.status === BookConfig.currentCategory);
    }
    
    BookConfig.totalBooks = filteredBooks.length;
    
    // 加载更多书籍
    const newDisplayed = filteredBooks.slice(start, start + BookConfig.booksPerPage);
    displayedBooks = [...displayedBooks, ...newDisplayed];
    
    // 渲染书籍
    newDisplayed.forEach((book, index) => {
        renderBook(book, index, booksGrid);
    });
    
    // 更新起始位置
    start += BookConfig.booksPerPage;
    
    // 检查是否有更多书籍
    hasMoreBooks = start < filteredBooks.length;
    
    // 更新加载更多按钮状态
    const loadMoreBtn = document.getElementById('load-more-books');
    if (loadMoreBtn) {
        if (hasMoreBooks) {
            loadMoreBtn.style.display = 'inline-block';
            loadMoreBtn.disabled = false;
        } else {
            loadMoreBtn.style.display = 'none';
            loadMoreBtn.disabled = true;
        }
    }
    
    // 如果没有书籍，显示空状态
    if (filteredBooks.length === 0) {
        booksGrid.innerHTML = `
            <div class="nodata" style="grid-column: 1 / -1;">
                <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
                <span>暂无书籍记录</span>
            </div>
        `;
    }
}

// 加载更多书籍
function loadMoreBooks() {
    loadBooks();
}

// 渲染书籍卡片
function renderBook(book, index, container) {
    const bookCard = document.createElement('div');
    bookCard.className = 'book-card new-item';
    bookCard.style.setProperty('--delay', index * 0.05 + 's');
    
    // 状态文本映射
    const statusText = {
        'read': '已读完',
        'reading': '在读',
        'want': '想读'
    };
    
    // 渲染评分
    let ratingHTML = '';
    if (book.rating > 0) {
        const stars = '★'.repeat(book.rating) + '☆'.repeat(5 - book.rating);
        ratingHTML = `
            <div class="book-rating">
                <span class="rating-stars">${stars}</span>
                <span class="rating-score">${book.rating}.0</span>
            </div>
        `;
    }
    
    // 渲染标签
    const tagsHTML = book.tags && book.tags.length > 0 
        ? `<div class="book-tags">${book.tags.map(tag => `<span class="book-tag">${tag}</span>`).join('')}</div>`
        : '';
    
    // 书籍链接
    const bookUrl = book.url || '';
    const bookLink = bookUrl ? `href="${bookUrl}" target="_blank"` : '';
    
    bookCard.innerHTML = `
        <div class="book-cover">
            <img src="${book.cover}" alt="${book.title}" onerror="this.src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'">
            <span class="book-status ${book.status}">${statusText[book.status] || book.status}</span>
        </div>
        <div class="book-info">
            <h3 class="book-title">${bookUrl ? `<a ${bookLink}>${book.title}</a>` : book.title}</h3>
            <div class="book-author">${book.author}</div>
            ${ratingHTML}
            <p class="book-description">${book.description || '暂无描述'}</p>
            ${tagsHTML}
        </div>
    `;
    
    // 如果有URL，添加点击事件
    if (bookUrl) {
        bookCard.style.cursor = 'pointer';
        bookCard.addEventListener('click', function() {
            window.open(bookUrl, '_blank');
        });
    }
    
    container.appendChild(bookCard);
}
</script>

<!--页面说明文字-->
<div class="post_content padding animated fadeIn blur">
    <h4 class="link-request">
        <span>#</span>
        书单说明
    </h4>
    <?php echo AutoLightbox($this->content);?>
</div>

<?php $this->need('comments.php'); ?>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>

<?php $this->need('footer.php'); ?>
