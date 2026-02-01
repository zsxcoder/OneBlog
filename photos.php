<?php
/**
 * 相册页面
 * @package custom
 * 本页面依赖于ContentCollector插件
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

$this->need('header.php');

$currentPage = max(1, intval($this->request->get('page', 1)));
$pageSize = 12;
$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$total = $db->fetchObject($db->select(['COUNT(id)' => 'count'])->from($prefix . 'photo_collection'))->count;
$totalPages = ceil($total / $pageSize);
$offset = ($currentPage - 1) * $pageSize;

$photos = $db->fetchAll($db->select()->from($prefix . 'photo_collection')
    ->order($prefix . 'photo_collection.order', Typecho_Db::SORT_ASC)
    ->order($prefix . 'photo_collection.id', Typecho_Db::SORT_DESC)
    ->offset($offset)
    ->limit($pageSize));

$options = Helper::options();
$photoColumn = $options->plugin('ContentCollector')->photoColumn ?: 4;
?>
<div class="main">
    <?php $this->need('module/head2.php');?>
    <div class="page_thumb blur">
        <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : $options->themeUrl . '/static/img/photo.jpg'; ?>"></div>
        <div class="pc">
            <i class="iconfont icon-nav menu-button"></i>
            <div class="page-head">
                <?php if ($options->logoStyle == 'text') {?>
                <h1><a href="<?php $options->siteUrl(); ?>"><?php $options->title(); ?></a><span class="soul">生活志</span></h1>
                <?php }else{ ?>
                <a class="logo" href="<?php $options->siteUrl(); ?>">
                    <img src="<?php echo $options->logoWhite ? $options->logoWhite : $options->themeUrl . '/static/img/logoWhite.svg'; ?>">
                </a>
                <?php }?>
            </div>
        </div>
        <div class="m">
            <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>Scenery along the way</span></h1> 
        </div>
    </div>
    <div class="page-title animated fadeIn pc">
        <h1><?php $this->title(); ?></h1>   
    </div>
    <div class="photo-contain blur animated fadeIn">
        <?php if (array_key_exists('ContentCollector', \Typecho\Plugin::export()['activated'])):?>
        <div class="photos" id="photos" style="grid-template-columns: repeat(<?php echo $photoColumn; ?>, 1fr);">
            <?php foreach ($photos as $photo): ?>
            <div class="photo image-shadow">
                <?php $displayImage = !empty($photo['thumb']) ? $photo['thumb'] : $photo['image']; ?>
                <?php $caption = $photo['title']; ?>
                <?php if (!empty($photo['author'])): ?>
                <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;© ' . htmlspecialchars($photo['author']); ?>
                <?php endif; ?>
                <?php if (!empty($photo['location'])): ?>
                <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;' . htmlspecialchars($photo['location']); ?>
                <?php endif; ?>
                <?php if (!empty($photo['taken_at'])): ?>
                <?php $caption .= '&nbsp;&nbsp;&nbsp;&nbsp;' . date('M d, Y', strtotime($photo['taken_at'])); ?>
                <?php endif; ?>
                <a href="<?php echo htmlspecialchars($photo['image']); ?>" data-fancybox="gallery" 
                   data-caption="<?php echo $caption; ?>">
                    <img class="lazy-load" data-src="<?php echo htmlspecialchars($displayImage); ?>" alt="<?php echo htmlspecialchars($photo['title']); ?>">
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="load" id="loadmore">
            <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>" class="next">点击查看更多</a>
            <?php else: ?>
            —&nbsp;&nbsp;&nbsp;暂无更多内容&nbsp;&nbsp;&nbsp;—
            <?php endif; ?>
        </div>
        <?php else:?>
    	<div class="nodata">
            <img src='<?php $options->themeUrl('static/img/nodata.svg'); ?>'>
            <span>暂未启用相册插件，请先安装并启用该插件。</span>
        </div>
    	<?php endif;?>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<?php $this->need('footer.php'); ?>
