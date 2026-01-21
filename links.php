<?php
/**
 * 友情链接
 *
 * @package custom
 */
 
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php'); ?>

<div class="main">
<?php $this->need('module/head2.php');?>

<!--背景图片+logo-->
<div class="page_thumb blur">
    <!-- 背景图片容器 -->
    <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : Helper::options()->themeUrl . '/static/img/friend.jpg';?>"></div>
    
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
        <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>My online friends</span></h1> 
    </div>
</div>
<div class="page-title animated fadeIn pc">
    <h1><?php $this->title(); ?></h1>   
</div>
<?php if (array_key_exists('Links', Typecho_Plugin::export()['activated'])):?>
    <div class="links padding blur">
        <?php Links_Plugin::output("
			<li class='link'>
				<a href='{url}' target='_blank'>
				    <img src='{image}' alt='{name}'/>
				    <div class='link-info'>
				        <h3>{name}</h3>
				        <span class='lite-black' title='{description}'>{description}</span>
				    </div>
				</a>
			</li>
		 ", 0); ?>
    </div>
    <?php else:?>
	<div class="nodata blur">
        <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
        <span>暂未启用Links插件，请先安装并启用该插件。</span>
    </div>
	<?php endif;?>
	
	<!-- 友链朋友圈 -->
	<?php
	// 友链朋友圈配置
	$fcConfig = array(
		'api_url' => $this->options->FriendCircleAPI ? $this->options->FriendCircleAPI : 'https://fc.mcyzsx.top/',
		'page_size' => $this->options->FriendCirclePageSize ? $this->options->FriendCirclePageSize : 15,
		'error_img' => 'https://fastly.jsdelivr.net/gh/willow-god/Friend-Circle-Lite@latest/static/favicon.ico'
	);
	
	// 服务器端获取API数据，避免跨域问题
	$apiData = array();
	$apiUrl = rtrim($fcConfig['api_url'], '/') . '/all.json';
	
	try {
		// 初始化cURL
		$ch = curl_init();
		
		// 设置cURL选项
		curl_setopt($ch, CURLOPT_URL, $apiUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		
		// 执行请求
		$response = curl_exec($ch);
		
		// 检查错误
		if (curl_errno($ch)) {
			throw new Exception(curl_error($ch));
		}
		
		// 关闭cURL
		curl_close($ch);
		
		// 解析JSON数据
		$apiData = json_decode($response, true);
	} catch (Exception $e) {
		// 错误处理
		error_log('友链朋友圈API请求失败: ' . $e->getMessage());
		$apiData = array(
			'statistical_data' => array(),
			'article_data' => array()
		);
	}
	?>
	
	<div class="friend-circle padding animated fadeIn blur">
		<h4 class="link-request">
			<span>#</span>
			友链朋友圈
		</h4>
		<div class="page-fcircle">
			<div class="article-list">
				<!-- 随机文章区域 -->
				<div class="random-article">
					<div class="random-container-title">随机钓鱼</div>
					<a href="#" class="article-item" id="random-article-link">
						<div class="article-container gradient-card">
							<div class="article-author" id="random-author"></div>
							<div class="article-title" id="random-title"></div>
							<div class="article-date" id="random-date"></div>
						</div>
					</a>
					<button class="refresh-btn gradient-card" id="refresh-btn">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"><path fill="currentColor" d="M12 20q-3.35 0-5.675-2.325T4 12t2.325-5.675T12 4q1.725 0 3.3.712T18 6.75V4h2v7h-7V9h4.2q-.8-1.4-2.187-2.2T12 6Q9.5 6 7.75 7.75T6 12t1.75 4.25T12 18q1.925 0 3.475-1.1T17.65 14h2.1q-.7 2.65-2.85 4.325T12 20"/></svg>
					</button>
				</div>

				<!-- 文章列表区域 -->
				<div class="articles-list" id="articles-list">
					<!-- 文章将通过JS动态加载 -->
				</div>

				<div class="load-more-container">
					<!-- 加载更多按钮 -->
					<button id="load-more" class="load-more gradient-card">
						再来亿点
					</button>
				</div>

				<!-- 作者模态框 -->
				<div id="modal" class="modal">
					<div class="modal-content">
						<div class="modal__header">
							<img id="modal-author-avatar" src="">
							<a id="modal-author-name-link" href="" target="_blank"></a>
						</div>
						
						<div id="modal-articles-container">
							<!-- 作者文章将通过JS动态加载 -->
						</div>
						
						<img id="modal-bg" src="">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<style>
		/* 友链朋友圈样式 */
		.friend-circle {
			margin-bottom: 20px;
		}
		
		.page-fcircle {
			animation: float-in .2s backwards;
		}
		
		.article-list .random-article {
			align-items: center;
			display: flex;
			flex-direction: row;
			gap: 10px;
			justify-content: space-between;
			margin: 1rem 0;
		}
		
		.article-list .random-article .random-container-title {
			font-size: 1.2rem;
			white-space: nowrap;
		}
		
		.article-list .random-article .article-item {
			flex: 1;
			min-width: 0;
		}
		
		.article-list .random-article .article-item .article-container {
			min-width: 0;
		}
		
		.article-list .random-article .article-item .article-container .article-title {
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
		}
		
		.article-list .random-article .refresh-btn {
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 8px;
			box-shadow: 0 0 0 1px var(--c-bg-soft);
			color: var(--c-text-2);
			cursor: pointer;
			flex-shrink: 0;
			height: 2.5rem;
			width: 2.5rem;
			border: none;
			background: transparent;
			transition: all 0.2s ease;
		}
		
		.article-list .random-article .refresh-btn:hover:not(:disabled) {
			color: var(--c-text);
		}
		
		.article-list .random-article .refresh-btn:disabled {
			opacity: 0.6;
			cursor: not-allowed;
		}
		
		.article-list .articles-list {
			display: flex;
			flex-direction: column;
			gap: .5rem;
		}
		
		.article-item {
			align-items: center;
			display: flex;
			gap: 10px;
			width: 100%;
		}
		
		.article-item.new-item {
			animation: float-in .2s var(--delay) backwards;
		}
		
		.article-item .article-image {
			border-radius: 50%;
			box-shadow: 0 0 0 1px var(--c-bg-soft);
			display: flex;
			flex-shrink: 0;
			height: 2rem;
			overflow: hidden;
			width: 2rem;
			cursor: pointer;
		}
		
		.article-item .article-image img {
			height: 100%;
			object-fit: cover;
			opacity: .8;
			transition: all 0.2s;
			width: 100%;
		}
		
		.article-item .article-image:hover img {
			opacity: 1;
			transform: scale(1.05);
		}
		
		.article-item .article-container {
			border-radius: 8px;
			box-shadow: 0 0 0 1px var(--c-bg-soft);
			align-items: center;
			display: flex;
			gap: 5px;
			height: 2.5rem;
			overflow: hidden;
			padding: 10px;
			width: 100%;
			cursor: pointer;
			transition: all 0.2s ease;
		}
		
		.article-item .article-container:hover .article-title {
			color: var(--c-text);
		}
		
		.article-item .article-container .article-author {
			color: var(--c-text-3);
			font-size: .85rem;
			flex-shrink: 0;
		}
		
		.article-item .article-container .article-title {
			color: var(--c-text-2);
			flex: 1;
			font-size: .9375rem;
			overflow: hidden;
			text-overflow: ellipsis;
			white-space: nowrap;
			transition: color 0.2s;
		}
		
		.article-item .article-container .article-date {
			color: var(--c-text-3);
			font-family: var(--font-monospace);
			font-size: .75rem;
			flex-shrink: 0;
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
		
		/* 模态框样式 */
		.modal {
			align-items: center;
			backdrop-filter: blur(20px);
			-webkit-backdrop-filter: blur(20px);
			display: none;
			justify-content: center;
			inset: 0;
			position: fixed;
			z-index: 100;
		}
		
		.modal.modal-open {
			display: flex;
		}
		
		.modal .modal-content {
			background-color: var(--c-bg-a50);
			border-radius: 12px;
			box-shadow: 0 0 0 1px var(--c-bg-soft);
			max-height: 80vh;
			max-width: 500px;
			overflow-y: auto;
			padding: 1.25rem;
			position: relative;
			width: 90%;
		}
		
		.modal .modal-content .modal__header {
			align-items: center;
			border-bottom: 1px solid var(--c-bg-soft);
			display: flex;
			gap: 15px;
			margin-bottom: 20px;
			padding-bottom: 15px;
		}
		
		.modal .modal-content .modal__header img {
			border-radius: 50%;
			height: 50px;
			object-fit: cover;
			width: 50px;
		}
		
		.modal .modal-content .modal__header a {
			border-radius: 8px;
			color: var(--c-text-2);
			padding: 8px;
			text-decoration: none;
			transition: all .3s;
		}
		
		.modal .modal-content .modal__header a:hover {
			background: var(--c-bg-soft);
			color: var(--c-text);
		}
		
		.modal .modal-content #modal-articles-container .modal-article {
			animation: float-in .3s var(--delay) backwards;
			color: var(--c-text-2);
			padding: 0 0 1rem 1.25rem;
			position: relative;
		}
		
		.modal .modal-content #modal-articles-container .modal-article:not(:last-child) {
			border-bottom: 1px solid var(--c-bg-soft);
			padding-bottom: 1rem;
			margin-bottom: 1rem;
		}
		
		.modal .modal-content #modal-articles-container .modal-article .modal-article-title {
			color: var(--c-text-2);
			display: block;
			line-height: 1.4;
			text-decoration: none;
			transition: color .3s;
		}
		
		.modal .modal-content #modal-articles-container .modal-article .modal-article-title:hover {
			color: var(--c-text);
		}
		
		.modal .modal-content #modal-articles-container .modal-article .modal-article-date {
			color: var(--c-text-3);
			font-family: var(--font-monospace);
			font-size: .875rem;
			margin-top: .3rem;
		}
		
		.modal .modal-content #modal-bg {
			border-radius: 50%;
			bottom: 1.25rem;
			filter: blur(5px);
			height: 128px;
			opacity: .6;
			overflow: hidden;
			pointer-events: none;
			position: absolute;
			right: 1.25rem;
			width: 128px;
			z-index: 1;
		}
		
		/* 图标类 */
		.icon-refresh {
			display: inline-block;
			width: 1em;
			height: 1em;
		}
		
		.icon-refresh svg {
			width: 100%;
			height: 100%;
			fill: currentColor;
		}
		
		.icon-link {
			display: inline-block;
			width: 1em;
			height: 1em;
		}
		
		.icon-link svg {
			width: 100%;
			height: 100%;
			fill: currentColor;
		}
		
		/* 动画 */
		@keyframes float-in {
			0% {
				opacity: 0;
				transform: translateY(20px);
			}
			100% {
				opacity: 1;
				transform: translateY(0);
			}
		}
		
		/* 响应式设计 */
		@media (max-width: 768px) {
			.random-article .random-container-title {
				display: none;
			}
			
			.article-item .article-container {
				flex-wrap: wrap;
				height: auto;
			}
			
			.article-item .article-container .article-author {
				flex-grow: 1;
			}
			
			.article-item .article-container .article-title {
				flex-basis: 100%;
				order: 3;
				white-space: normal;
			}
		}
	</style>
	
	<script>
		// 配置选项
		const UserConfig = {
			private_api_url: '<?php echo $fcConfig['api_url']; ?>',
			page_turning_number: <?php echo $fcConfig['page_size']; ?>,
			error_img: '<?php echo $fcConfig['error_img']; ?>'
		};
		
		// 状态管理
		let allArticles = [];
		let displayedArticles = [];
		let stats = {
			friends_num: 0,
			active_num: 0,
			article_num: 0,
			last_updated_time: ''
		};
		let start = 0;
		let hasMoreArticles = true;
		let randomArticle = null;
		let showModal = false;
		let currentAuthor = '';
		let currentAuthorAvatar = '';
		let authorOrigin = '';
		let authorArticles = [];
		
		// DOM 元素
		let articlesList, loadMoreBtn, refreshBtn, randomArticleLink, randomAuthor, randomTitle, randomDate, modal;
		
		// 从PHP预获取的API数据
		const apiData = <?php echo json_encode($apiData); ?>;
		
		// 初始化
		document.addEventListener('DOMContentLoaded', function() {
			// 获取DOM元素
			articlesList = document.getElementById('articles-list');
			loadMoreBtn = document.getElementById('load-more');
			refreshBtn = document.getElementById('refresh-btn');
			randomArticleLink = document.getElementById('random-article-link');
			randomAuthor = document.getElementById('random-author');
			randomTitle = document.getElementById('random-title');
			randomDate = document.getElementById('random-date');
			modal = document.getElementById('modal');
			
			initializeFC();
			
			// 事件监听
			if (loadMoreBtn) {
				loadMoreBtn.addEventListener('click', loadMoreArticles);
				console.log('加载更多按钮事件绑定成功');
			}
			
			if (refreshBtn) {
				refreshBtn.addEventListener('click', displayRandomArticle);
				console.log('刷新按钮事件绑定成功');
			}
			
			if (randomArticleLink) {
				randomArticleLink.addEventListener('click', function(e) {
					e.preventDefault();
					openRandomArticle();
				});
				console.log('随机文章链接事件绑定成功');
			}
			
			if (modal) {
				modal.addEventListener('click', function(e) {
					if (e.target.id === 'modal') {
						hideModal();
					}
				});
				console.log('模态框事件绑定成功');
			}
		});
		
		// 友链圈初始化
		function initializeFC() {
			// 直接使用预获取的数据
			processArticles(apiData);
		}
		
		// 加载更多文章
		function loadMoreArticles() {
			// 由于数据已经预加载，这里只需要更新显示
			// 检查是否有更多文章
			if (start < allArticles.length) {
				const newDisplayed = allArticles.slice(
					start,
					start + UserConfig.page_turning_number
				);
				displayedArticles = [...displayedArticles, ...newDisplayed];
				
				// 渲染新文章
				renderArticles(newDisplayed);
				
				// 更新起始位置
				start += UserConfig.page_turning_number;
				
				// 检查是否有更多文章
				hasMoreArticles = start < allArticles.length;
				
				// 更新加载更多按钮状态
				if (loadMoreBtn) {
					if (hasMoreArticles) {
						loadMoreBtn.style.display = 'inline-block';
						loadMoreBtn.disabled = false;
					} else {
						loadMoreBtn.style.display = 'none';
						loadMoreBtn.disabled = true;
					}
				}
			}
		}
		
		// 处理文章数据
		function processArticles(data) {
			// 更新统计数据
			if (data.statistical_data) {
				stats.friends_num = data.statistical_data.friends_num || 0;
				stats.active_num = data.statistical_data.active_num || 0;
				stats.article_num = data.statistical_data.article_num || 0;
				stats.last_updated_time = data.statistical_data.last_updated_time || '';
			}
			
			// 合并新旧文章
			const newArticles = data.article_data || [];
			const mergedArticles = [...allArticles, ...newArticles];
			allArticles = mergedArticles;
			
			// 更新显示的列表
			const newDisplayed = mergedArticles.slice(
				start,
				start + UserConfig.page_turning_number
			);
			displayedArticles = [...displayedArticles, ...newDisplayed];
			
			// 渲染新文章
			renderArticles(newDisplayed);
			
			// 更新起始位置
			start += UserConfig.page_turning_number;
			
			// 检查是否有更多文章
			hasMoreArticles = start < mergedArticles.length;
			
			// 更新加载更多按钮状态
			if (loadMoreBtn) {
				if (hasMoreArticles) {
					loadMoreBtn.style.display = 'inline-block';
					loadMoreBtn.disabled = false;
				} else {
					loadMoreBtn.style.display = 'none';
					loadMoreBtn.disabled = true;
				}
			}
			
			// 显示随机文章
			if (!randomArticle) {
				displayRandomArticle();
			}
		}
		
		// 渲染文章
		function renderArticles(articles) {
			if (!articlesList) return;
			
			articles.forEach((article, index) => {
				const articleItem = document.createElement('div');
				articleItem.className = 'article-item new-item';
				articleItem.style.setProperty('--delay', index * 0.05 + 's');
				
				const articleImage = document.createElement('div');
				articleImage.className = 'article-image';
				articleImage.onclick = function() {
					showAuthorArticles(article.author, article.avatar, article.link);
				};
				
				const img = document.createElement('img');
				img.src = avatarOrDefault(article.avatar);
				img.onerror = handleAvatarError;
				articleImage.appendChild(img);
				
				const articleContainer = document.createElement('div');
				articleContainer.className = 'article-container gradient-card';
				articleContainer.onclick = function() {
					openArticle(article.link);
				};
				
				const articleAuthor = document.createElement('div');
				articleAuthor.className = 'article-author';
				articleAuthor.textContent = article.author;
				
				const articleTitle = document.createElement('div');
				articleTitle.className = 'article-title';
				articleTitle.textContent = article.title;
				
				const articleDate = document.createElement('div');
				articleDate.className = 'article-date';
				articleDate.textContent = formatDate(article.created);
				
				articleContainer.appendChild(articleAuthor);
				articleContainer.appendChild(articleTitle);
				articleContainer.appendChild(articleDate);
				
				articleItem.appendChild(articleImage);
				articleItem.appendChild(articleContainer);
				
				articlesList.appendChild(articleItem);
			});
		}
		
		// 格式化日期
		function formatDate(dateString) {
			return dateString ? dateString.substring(0, 10) : '';
		}
		
		// 显示随机文章
		function displayRandomArticle() {
			if (allArticles.length > 0) {
				const randomIndex = Math.floor(Math.random() * allArticles.length);
				randomArticle = allArticles[randomIndex];
				
				if (randomAuthor) randomAuthor.textContent = randomArticle.author;
				if (randomTitle) randomTitle.textContent = randomArticle.title;
				if (randomDate) randomDate.textContent = formatDate(randomArticle.created);
			}
		}
		
		// 头像加载处理
		function avatarOrDefault(avatar) {
			return avatar || UserConfig.error_img;
		}
		
		function handleAvatarError(event) {
			event.target.src = UserConfig.error_img;
		}
		
		// 打开文章链接
		function openArticle(link) {
			window.open(link, '_blank');
		}
		
		// 打开随机文章
		function openRandomArticle() {
			if (randomArticle) {
				window.open(randomArticle.link, '_blank');
			}
		}
		
		// 显示作者文章模态框
		function showAuthorArticles(author, avatar, link) {
			currentAuthor = author;
			currentAuthorAvatar = avatar;
			authorOrigin = new URL(link).origin;
			authorArticles = allArticles
				.filter(article => article.author === author)
				.slice(0, 4);
			
			// 填充模态框内容
			const modalAuthorAvatar = document.getElementById('modal-author-avatar');
			const modalAuthorNameLink = document.getElementById('modal-author-name-link');
			const modalArticlesContainer = document.getElementById('modal-articles-container');
			const modalBg = document.getElementById('modal-bg');
			
			if (modalAuthorAvatar) {
				modalAuthorAvatar.src = avatarOrDefault(currentAuthorAvatar);
				modalAuthorAvatar.onerror = handleAvatarError;
			}
			
			if (modalAuthorNameLink) {
				modalAuthorNameLink.href = authorOrigin;
				modalAuthorNameLink.textContent = currentAuthor;
			}
			
			if (modalArticlesContainer) {
				modalArticlesContainer.innerHTML = '';
				authorArticles.forEach((article, index) => {
					const modalArticle = document.createElement('div');
					modalArticle.className = 'modal-article';
					modalArticle.style.setProperty('--delay', index * 0.1 + 's');
					
					const modalArticleTitle = document.createElement('a');
					modalArticleTitle.className = 'modal-article-title';
					modalArticleTitle.href = article.link;
					modalArticleTitle.target = '_blank';
					modalArticleTitle.textContent = article.title;
					
					const modalArticleDate = document.createElement('div');
					modalArticleDate.className = 'modal-article-date';
					modalArticleDate.textContent = '📅' + formatDate(article.created);
					
					modalArticle.appendChild(modalArticleTitle);
					modalArticle.appendChild(modalArticleDate);
					modalArticlesContainer.appendChild(modalArticle);
				});
			}
			
			if (modalBg) {
				modalBg.src = avatarOrDefault(currentAuthorAvatar);
				modalBg.onerror = handleAvatarError;
			}
			
			showModal = true;
			if (modal) {
				modal.classList.add('modal-open');
			}
			document.body.classList.add('overflow-hidden');
		}
		
		// 隐藏模态框
		function hideModal() {
			showModal = false;
			if (modal) {
				modal.classList.remove('modal-open');
			}
			document.body.classList.remove('overflow-hidden');
		}
	</script>
	
    <div class="post_content padding animated fadeIn blur">
        <h4 class="link-request">
            <span>#</span>
            友链要求
        </h4>
        <?php echo AutoLightbox($this->content);?>
    </div>
    <?php $this->need('comments.php'); ?>
    <a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<?php $this->need('footer.php'); ?>