<?php
/**
 * 相册页面
 * @package custom
 * 本页面依赖于ContentManager插件
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
$currentPage = max(1, intval($this->request->get('page', 1)));

// 从插件获取照片数据
$db = Typecho_Db::get();
$prefix = $db->getPrefix();
$photoTable = $prefix . 'contentmanager_photo';
$photos = $db->fetchAll($db->select()->from($photoTable)->order('date', Typecho_Db::SORT_DESC));
?>
<div class="main">
    <?php $this->need('module/head2.php');?>
    <!--背景图片+logo-->
    <div class="page_thumb blur">
        <!-- 背景图片容器 -->
        <div class="post_bg lazy-load" data-src="<?php echo $this->fields->thumb ? $this->fields->thumb : Helper::options()->themeUrl . '/static/img/photo.jpg'; ?>"></div>
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
            <h1 class="page-head"><?php $this->archiveTitle(' &raquo; ', ''); ?><span>Scenery along the way</span></h1> 
        </div>
    </div>
    <div class="page-title animated fadeIn pc">
        <h1><?php $this->title(); ?></h1>   
    </div>
    <div class="photo-contain blur animated fadeIn">
        <?php if (array_key_exists('ContentManager', Typecho_Plugin::export()['activated'])):?>
        <!--相册-->
        <div class="photos" id="photos">
            <?php foreach ($photos as $photo): ?>
            <div class="photo image-shadow">
                <a href="<?= $photo['rawUrl'] ?>" data-fancybox="gallery" 
                   data-caption="<?= $photo['title'] ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $photo['caption'] ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= date('M d, Y', $photo['date']) ?>">
                    <img class="lazy-load" data-src="<?= $photo['url'] ?>" />
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- 点击无限加载 -->
        <div class="load" id="loadmore">
            <?php if (count($photos) > 0): ?>
                —&nbsp;&nbsp;&nbsp;暂无更多内容&nbsp;&nbsp;&nbsp;—
            <?php else: ?>
                —&nbsp;&nbsp;&nbsp;暂无内容&nbsp;&nbsp;&nbsp;—
            <?php endif; ?>
        </div>
        <?php else:?>
    	<div class="nodata">
            <img src='<?php $this->options->themeUrl('static/img/nodata.svg'); ?>'></img>
            <span>暂未启用ContentManager插件，请先安装并启用该插件。</span>
        </div>
    	<?php endif;?>
    </div>
</div>
<a id="gototop" class="hidden"><i class="iconfont icon-up"></i></a>
</div>
<?php $this->need('footer.php'); ?>