<div class="footer pc">
    <div class="navigation"><!--底部菜单导航-->
        <?php if ($menu = CustomMenu()): ?>
        <?php echo $menu['noIcon']; ?>
        <?php endif; ?>
        <!--如果需要仅在网站底部额外增加页面路径，则按照以下格式增加即可：
        <a href="/archives">归档</a>
        -->
        
    </div>
    <div class="copyright">
        Copyright&copy;<?php if (!empty($this->options->Webtime)): echo $this->options->Webtime().'-'; ?><?php endif; ?><?php echo date('Y'); ?>&nbsp;&nbsp;All Rights Reserved.&nbsp;&nbsp;Load：<?php echo timer_stop();?><br>
        <span id="site-age"></span><br>
        <span id="voyager-distance"></span><br>
            <?php if (!empty($this->options->WA)): ?>
                <img src="<?php $this->options->themeUrl('/static/img/beian.png'); ?>"/><a href="https://beian.mps.gov.cn" rel="nofollow noreferrer" target="_blank"><?php $this->options->WA(); ?></a>&nbsp;&nbsp;
            <?php endif; ?>
            <?php if (!empty($this->options->ICP)): ?>
                <a href="https://beian.miit.gov.cn/" target="_blank" rel="nofollow noreferrer"><?php $this->options->ICP(); ?></a><br>
            <?php endif; ?>
            Theme by <a id="copyright-pc" href="https://oneblog.net/oneblog" title="自豪地使用OneBlog主题" target="_blank">OneBlog</a> V<?php echo parseThemeVersion();?>         
            <div class="switch">
                <span>夜间模式</span>
                <input type="checkbox" id="night2" class="night-toggle">
                <label for="night2" class="switchBtn"></label>
            </div>
    </div>
    <div class="contact">
        <?php if (!empty($this->options->QQ)): ?>
        <a id="qq" title="QQ"><iconify-icon icon="ri:qq-fill" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Weixin)): ?>
        <a id="wxmp" title="微信公众号"><iconify-icon icon="logos:wechat" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Email)): ?>
        <a id="tomail" title="博主邮箱"><iconify-icon icon="material-symbols:mail" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Github)): ?>
        <a href="<?php $this->options->Github();?>" target="_blank" title="Github"><iconify-icon icon="mdi:github" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Telegram)): ?>
        <a href="<?php $this->options->Telegram();?>" target="_blank" title="Telegram"><iconify-icon icon="mdi:telegram" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Mastodon)): ?>
        <a href="<?php $this->options->Mastodon();?>" target="_blank" title="Mastodon"><iconify-icon icon="simple-icons:mastodon" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->X)): ?>
        <a href="<?php $this->options->X();?>" target="_blank" title="X"><iconify-icon icon="logos:x" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->Twitter)): ?>
        <a href="<?php $this->options->Twitter();?>" target="_blank" title="Twitter"><iconify-icon icon="logos:twitter" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
        <?php if (!empty($this->options->BiliBili)): ?>
        <a href="<?php $this->options->BiliBili();?>" target="_blank" title="BiliBili"><iconify-icon icon="logos:bilibili" width="22" height="22"></iconify-icon></a>
        <?php endif; ?>
    </div>
</div>


<?php $this->footer();?>
<script src="https://cncdn.cc/jquery/3.7.1/dist/jquery.min.js"></script><!--基础依赖放在最前面-->
<script src="https://cncdn.cc/@fancyapps/fancybox/3.5.7/dist/jquery.fancybox.min.js"></script><!--图片灯箱效果-->
<script src="https://cncdn.cc/layer/3.1.1/layer.js"></script>
<?php if ($this->is('index')):?>
<script src="https://cncdn.cc/swiper/8.3.2/swiper-bundle.min.js"></script>
<script>
var bannerSwitch = '<?= $this->options->switch === 'on' ? 'on' : 'off' ?>';
</script>
<?php endif;?>
<?php if ($this->is('post') || $this->is('page')): ?>
<?php if ($this->options->BeCode == 'on'):?>
<!--代码高亮逻辑-->
<script src="https://cncdn.cc/highlightjs/cdn-release/11.11.1/build/highlight.min.js"></script>
<script defer>
document.addEventListener('DOMContentLoaded', function () {
    const codeBlocks = document.querySelectorAll('pre code');
    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                hljs.highlightElement(entry.target);
                observer.unobserve(entry.target); 
            }
        });
    }, {
        rootMargin: '0px',
        threshold: 0.1 // 当代码块进入视口 10% 时触发，减少资源占用
    });

    codeBlocks.forEach(function (codeBlock) {
        observer.observe(codeBlock); 
        codeBlock.style.filter = 'none'; // 显示高亮后的代码块
    });
});
</script>
<?php endif;?>

<!--表情支持-->
<script src="<?php $this->options->themeUrl('/static/js/emoji.js'); ?>"></script>

<?php if ($this->options->GeetestID): ?>
<!--人机验证-->
<script src="https://static.geetest.com/v4/gt4.js"></script>
<?php endif;?>
<!--评论无限加载js-->
<script src="<?php $this->options->themeUrl('/static/js/comments.js?v=3.6.5'); ?>"></script>
<?php endif;?>

<script src="<?php $this->options->themeUrl('/static/js/main.js?v=3.6.5'); ?>"></script><!--主题js-->

<!-- 版权信息 -->
<div id="copyright-info" style="display: none;">
<p>开源不易，请尊重作者版权，保留基本的版权信息。</p>
</div>

<script type="text/javascript">
$(document).on('click', '#qq', function() {layer.msg('<?php $this->options->QQ();?>',{time:4000});});    	
$(document).on('click', '#wxmp', function() {layer.open({type: 1,title: false,closeBtn: 0,shadeClose: true,skin: 'layui-layer-nobg',area: ['auto'], content: '<img id= "mywxmp" style="width:20rem;height:20rem;display:block;" src="<?php $this->options->Weixin();?>">'});});
$(document).on('click', '#tomail', function() {layer.msg('联系邮箱：<?php $this->options->Email();?>',{time:4000});});    	
</script>
<!--自定义JS代码-->
<?php if (!empty($this->options->JS)): ?>
<?php $this->options->JS();?>
<?php endif; ?>
<script>
// 建站时间计算（精确到秒）
function updateSiteAge() {
    // 建站时间，可根据实际情况修改
    const siteStartDate = new Date('2026-01-06 12:00:00');
    const now = new Date();
    const diff = now - siteStartDate;
    
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((diff % (1000 * 60)) / 1000);
    
    document.getElementById('site-age').textContent = `建站时间：${days}天 ${hours}小时 ${minutes}分 ${seconds}秒`;
}

// 旅行者一号距离计算
function updateVoyagerDistance() {
    // 旅行者一号发射日期：1977年9月5日
    const launchDate = new Date('1977-09-05 12:56:00'); // 发射时间（UTC）
    const speedPerYear = 350000000; // 每年飞行距离（公里）
    
    const now = new Date();
    const diff = now - launchDate;
    const totalYears = diff / (1000 * 60 * 60 * 24 * 365.25);
    
    const currentDistance = totalYears * speedPerYear;
    const formattedDistance = currentDistance.toLocaleString('zh-CN', {maximumFractionDigits: 2});
    
    document.getElementById('voyager-distance').textContent = `旅行者一号距离地球：${formattedDistance} 公里`;
}

// 初始化并定时更新
updateSiteAge();
updateVoyagerDistance();
setInterval(updateSiteAge, 1000); // 每秒更新建站时间
setInterval(updateVoyagerDistance, 1000); // 每秒更新旅行者一号距离
</script>
</div>
</body>
</html>