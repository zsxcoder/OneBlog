<?php
/**
 * 书单列表页
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');

$pageSize = 12;
$currentPage = max(1, intval($this->request->get('page', 1)));
$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$total = $db->fetchObject($db->select(['COUNT(id)' => 'count'])->from($prefix . 'book_collection'))->count;
$totalPages = ceil($total / $pageSize);
$offset = ($currentPage - 1) * $pageSize;

$books = $db->fetchAll($db->select()->from($prefix . 'book_collection')
    ->order($prefix . 'book_collection.order', Typecho_Db::SORT_ASC)
    ->order($prefix . 'book_collection.id', Typecho_Db::SORT_DESC)
    ->offset($offset)
    ->limit($pageSize));

$options = Helper::options();
$coverWidth = $options->plugin('ContentCollector')->bookCoverWidth ?: 120;
$coverHeight = $options->plugin('ContentCollector')->bookCoverHeight ?: 160;
?>
<div class="main">
    <?php $this->need('module/head2.php'); ?>
    <div class="page_thumb blur">
        <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : $options->themeUrl . '/static/img/book.jpg'; ?>"></div>
        <div class="pc">
            <i class="iconfont icon-nav menu-button"></i>
            <div class="page-head">
                <?php if ($options->logoStyle == 'text'): ?>
                <h1><a href="<?php $options->siteUrl(); ?>"><?php $options->title(); ?></a><span class="soul">生活志</span></h1>
                <?php else: ?>
                <a class="logo" href="<?php $options->siteUrl(); ?>">
                    <img src="<?php echo $options->logoWhite ? $options->logoWhite : $options->themeUrl . '/static/img/logoWhite.svg'; ?>">
                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="m">
            <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>书单</span></h1>
        </div>
    </div>
    <div class="page-title animated fadeIn pc">
        <h1><?php $this->title(); ?></h1>
    </div>
    <div class="book-contain blur animated fadeIn">
        <?php if (array_key_exists('ContentCollector', \Typecho\Plugin::export()['activated'])): ?>
        <div class="books" id="books">
            <?php foreach ($books as $book): ?>
            <a href="<?php echo Typecho\Common::url('book/' . $book['id'], $options->siteUrl); ?>" class="book">
                <div class="book-thumb lazy-load" data-src="<?php echo htmlspecialchars($book['cover'] ?: $options->themeUrl . '/static/img/bg.jpg'); ?>" style="width: <?php echo $coverWidth; ?>px; height: <?php echo $coverHeight; ?>px;"></div>
                <div class="book-name"><?php echo htmlspecialchars($book['title']); ?></div>
            </a>
            <?php endforeach; ?>
        </div>
        <div class="load" id="loadmore">
            <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>" class="next">点击查看更多</a>
            <?php else: ?>
            —&nbsp;&nbsp;&nbsp;暂无更多内容&nbsp;&nbsp;&nbsp;—
            <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="nodata">
            <img src="<?php $options->themeUrl('static/img/nodata.svg'); ?>">
            <span>暂未启用书单插件，请先安装并启用该插件。</span>
        </div>
        <?php endif; ?>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<?php $this->need('footer.php'); ?>
