<?php

namespace TypechoPlugin\ContentManager;

use Typecho\Db\Exception;
use Typecho\Plugin\PluginInterface;
use Typecho\Widget\Helper\Form;
use Typecho\Db;
use Utils\Helper;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Typecho内容扩展插件，支持🎬电影、📚书籍、🛍️我的好物的管理。电影、书籍支持从豆瓣导入信息，本地化存储。
 *
 * @package ContentManager
 * @author Shawn
 * @version 1.1.0
 * @link https://shawnzeng.com
 */
class Plugin implements PluginInterface
{
    public static $bookPanel = 'ContentManager/manage-books.php';
    public static $moviePanel = 'ContentManager/manage-movies.php';
    public static $goodPanel = 'ContentManager/manage-goods.php';

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return string
     * @throws Exception
     */
    public static function activate()
    {
        $db = Db::get();
        $prefix = $db->getPrefix();

        // 检查是否已有书籍表
        $sql = "SHOW TABLES LIKE '{$prefix}books'";
        $result = $db->fetchRow($sql);
        if (!$result) {
            // 创建书籍表，并添加 description 字段
            $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}books` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `title` VARCHAR(255) NOT NULL,
                `author` VARCHAR(255) NOT NULL,
                `publisher` VARCHAR(255) NOT NULL,
                `subtitle` VARCHAR(255),
                `origin_title` VARCHAR(255),
                `translator` VARCHAR(255),
                `pubdate` VARCHAR(255) NOT NULL,
                `cover_url` VARCHAR(255) NOT NULL,
                `douban_id` VARCHAR(255),
                `read_date` DATE NOT NULL,
                `rating` FLOAT NOT NULL,
                `description` TEXT,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->query($sql);
        } else {
            // 检查 description 字段是否存在
            $sql = "SHOW COLUMNS FROM `{$prefix}books` LIKE 'description'";
            $descriptionField = $db->fetchRow($sql);
            if (!$descriptionField) {
                // 如果 description 字段不存在，则添加该字段
                $sql = "ALTER TABLE `{$prefix}books` ADD `description` TEXT";
                $db->query($sql);
            }
            // 检查 read_date 字段是否存在
            $sql = "SHOW COLUMNS FROM `{$prefix}books` LIKE 'read_date'";
            $readDateField = $db->fetchRow($sql);
            if (!$readDateField) {
                $sql = "ALTER TABLE `{$prefix}books` ADD `read_date` DATE NOT NULL AFTER `douban_id`";
                $db->query($sql);
            }
        }

        // 检查是否已有电影表
        $sql = "SHOW TABLES LIKE '{$prefix}movies'";
        $result = $db->fetchRow($sql);
        if (!$result) {
            // 创建电影表
            $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}movies` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `year` VARCHAR(20),
                `directors` TEXT NOT NULL,
                `actors` TEXT NOT NULL,
                `genres` TEXT NOT NULL,
                `image_url` VARCHAR(255) NOT NULL,
                `douban_id` VARCHAR(255),
                `watch_date` DATE NOT NULL,
                `rating` FLOAT NOT NULL,
                `description` TEXT,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->query($sql);
        } else {
            // 检查 description 字段是否存在
            $sql = "SHOW COLUMNS FROM `{$prefix}movies` LIKE 'description'";
            $descriptionField = $db->fetchRow($sql);
            if (!$descriptionField) {
                // 如果 description 字段不存在，则添加该字段
                $sql = "ALTER TABLE `{$prefix}movies` ADD `description` TEXT";
                $db->query($sql);
            }
            // 检查 watch_date 字段是否存在
            $sql = "SHOW COLUMNS FROM `{$prefix}movies` LIKE 'watch_date'";
            $readDateField = $db->fetchRow($sql);
            if (!$readDateField) {
                $sql = "ALTER TABLE `{$prefix}movies` ADD `watch_date` DATE NOT NULL AFTER `douban_id`";
                $db->query($sql);
            }
            // 检查 year 字段是否存在
            $sql = "SHOW COLUMNS FROM `{$prefix}movies` LIKE 'year'";
            $readDateField = $db->fetchRow($sql);
            if (!$readDateField) {
                $sql = "ALTER TABLE `{$prefix}movies` ADD `year` VARCHAR(20) AFTER `name`";
                $db->query($sql);
            }
        }

        // 检查是否已有物品表
        $sql = "SHOW TABLES LIKE '{$prefix}goods'";
        $result = $db->fetchRow($sql);
        if (!$result) {
            // 创建物品表
            $sql = "CREATE TABLE IF NOT EXISTS `{$prefix}goods` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                `brand` VARCHAR(255) NOT NULL,
                `category` VARCHAR(255) NOT NULL,
                `price` FLOAT,
                `specification` VARCHAR(255),
                `image_url` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
            $db->query($sql);
        }

        Helper::addPanel(3, self::$bookPanel, '书籍', '管理书籍', 'administrator');
        Helper::addPanel(3, self::$moviePanel, '电影', '管理电影', 'administrator');
        Helper::addPanel(3, self::$goodPanel, '好物', '管理我的好物', 'administrator');
        Helper::addAction('books-edit','ContentManager_Action');
        Helper::addAction('movies-edit','ContentManager_Action');
        Helper::addAction('goods-edit','ContentManager_Action');
        // 注册内容解析钩子
        \Typecho\Plugin::factory('Widget_Abstract_Contents')->contentEx_66 = __CLASS__ .'::parseContentShortcode';
        // 插件激活时加载CSS
        \Typecho\Plugin::factory('Widget_Archive')->header = __CLASS__ . '::addCss';

        return _t('ContentManager 插件已激活');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     */
    public static function deactivate()
    {
        Helper::removePanel(3, self::$bookPanel);
        Helper::removePanel(3, self::$moviePanel);
        Helper::removePanel(3, self::$goodPanel);
        Helper::removeAction('books-edit');
        Helper::removeAction('movies-edit');
        Helper::removeAction('goods-edit');
        return _t('ContentManager 插件已禁用');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Form $form 配置面板
     * @return void
     */
    public static function config(Form $form)
    {
        /*
        $doubanApiKey = new Form\Element\Text(
            'doubanApiKey',
            null,
            '0ab215a8b1977939201640fa14c66bab',
            _t('豆瓣API Key'),
            _t('豆瓣API Key，如果有其他Key也可以填写，没有则使用插件作者提供的Key，请勿随便更改'),
        );
        $form->addInput($doubanApiKey);
        $defaultBg = new Form\Element\Text(
            'defaultBg',
            null,
            '#ededed',
            _t('默认背景色'),
            _t('默认各卡片的背景色'),
        );
        $form->addInput($defaultBg);
        $darkBg = new Form\Element\Text(
            'darkBg',
            null,
            'hsla(0,0%,100%,.1)',
            _t('深色模式背景色'),
            _t('深色模式各卡片的背景色'),
        );
        $form->addInput($darkBg);
        */
    }

    public static function personalConfig(Form $form)
    {
    }

    /**
     * 加载 CSS 文件
     */
    public static function addCss()
    {
        $cssUrl = Helper::options()->pluginUrl . '/ContentManager/contentmanager.css';
        echo '<link rel="stylesheet" type="text/css" href="' . $cssUrl . '" />';
    }


    /**
     * 电影和书籍短代码解析
     * @param $content
     * @param $widget
     * @param $last
     * @return array|string|string[]|null
     * @throws Exception
     */
    public static function parseContentShortcode($content, $widget, $last)
    {

        // 匹配电影短代码 [movie id=1,2,3]
        $content = preg_replace_callback('/\[movie id=([\d,]+)\]/', function($matches) {
            $ids = explode(',', $matches[1]);
            $db = Db::get();
            $prefix = $db->getPrefix();
            $html = '';

            foreach ($ids as $movieId) {
                $movieId = trim($movieId);

                // 获取电影信息
                $query = $db->select()->from($prefix . 'movies')->where('id = ?', $movieId);
                $movie = $db->fetchRow($query);

                if ($movie) {
                    $html .= self::buildMovieHtml($movie);
                } else {
                    $html .= '<p>未找到电影信息</p>';
                }
            }

            return $html;
        }, $content);

        // 匹配书籍短代码 [book id=1,2,3]
        $content = preg_replace_callback('/\[book id=([\d,]+)\]/', function($matches) {
            $ids = explode(',', $matches[1]);
            $db = Db::get();
            $prefix = $db->getPrefix();
            $html = '';

            foreach ($ids as $bookId) {
                $bookId = trim($bookId);

                // 获取书籍信息
                $query = $db->select()->from($prefix . 'books')->where('id = ?', $bookId);
                $book = $db->fetchRow($query);

                if ($book) {
                    $html .= self::buildBookHtml($book);
                } else {
                    $html .= '<p>未找到书籍信息</p>';
                }
            }

            return $html;
        }, $content);

        // 匹配好物短代码 [good id=1,2,3] 和 [good list]
        $content = preg_replace_callback('/\[good(?: id=([\d,]+)| list)\]/', function($matches) {
            $db = Db::get();
            $prefix = $db->getPrefix();
            $html = '';

            if (isset($matches[1])) {
                $ids = explode(',', $matches[1]);
                if (count($ids) == 1) {
                    $html .= '<div class="good-list good-list-single">';
                } else {
                    $html .= '<div class="good-list">';
                }

                foreach ($ids as $goodId) {
                    $goodId = trim($goodId);

                    // 获取好物信息
                    $query = $db->select()->from($prefix . 'goods')->where('id = ?', $goodId);
                    $good = $db->fetchRow($query);

                    if ($good) {
                        $html .= self::buildGoodHtml($good);
                    } else {
                        $html .= '<p>未找到好物信息</p>';
                    }
                }
                $html .= '</div>';
            } else {
                // 获取所有好物信息
                $query = $db->select()->from($prefix . 'goods')->order($prefix . 'goods.id', \Typecho\Db::SORT_ASC);
                $goods = $db->fetchAll($query);
                if (!empty($goods)) {
                    $html .= '<div class="good-list">';
                    foreach ($goods as $good) {
                        $html .= self::buildGoodHtml($good);
                    }
                    $html .= '</div>';
                } else {
                    $html .= '<p>未找到好物信息</p>';
                }
            }

            return $html;
        }, $content);


        return $content;
    }

    private static function buildMovieHtml($movie): string
    {
        $rating = floatval($movie['rating']);
        $ratingPercentage = ($rating / 10) * 100;
        $description = !empty($movie['description']) ? '<div class="movie-description"><strong>简介：</strong>' . htmlspecialchars($movie['description']) . '</div>' : '';

        return sprintf(
            '<div class="movie-item">
                        <img src="%s" alt="%s" class="movie-img" referrerpolicy="no-referrer" />
                        <div class="movie-info"></div>
                            <h3 class="movie-name">%s</h3>
                            <div class="movie-rating"><strong>评分：</strong>
                                <div class="rating">
                                    <div class="stars-outer">
                                        <div class="stars-inner" style="width:%s%%;"></div>
                                    </div>
                                </div>
                                %s
                            </div>
                            <span class="movie-directors"><strong>导演：</strong>%s</span>
                            <span class="movie-actors"><strong>演员：</strong>%s</span>
                            <span class="movie-genres"><strong>分类：</strong>%s</span>
                            %s
                        </div>
                    </div>',
            htmlspecialchars($movie['image_url']),
            htmlspecialchars($movie['name']),
            htmlspecialchars($movie['name']),
            htmlspecialchars($ratingPercentage),
            htmlspecialchars($movie['rating']),
            htmlspecialchars($movie['directors']),
            htmlspecialchars($movie['actors']),
            htmlspecialchars($movie['genres']),
            $description
        );
    }

    private static function buildBookHtml($book): string
    {
        $rating = floatval($book['rating']);
        $ratingPercentage = ($rating / 10) * 100;
        $subtitle = !empty($book['subtitle']) ? '<span class="book-subtitle"><strong>副标题：</strong>' . htmlspecialchars($book['subtitle']) . '</span>' : '';
        $originTitle = !empty($book['origin_title']) ? '<span class="book-origin-title"><strong>原作名：</strong>' . htmlspecialchars($book['origin_title']) . '</span>' : '';
        $translator = !empty($book['translator']) ? '<span class="book-translator"><strong>译者：</strong>' . htmlspecialchars($book['translator']) . '</span>' : '';
        $description = !empty($book['description']) ? '<div class="book-description"><strong>简介：</strong>' . htmlspecialchars($book['description']) . '</div>' : '';

        return sprintf(
            '<div class="book-item" id="bool-item-%s">
                        <img src="%s" alt="%s" class="book-img" referrerpolicy="no-referrer" />
                        <div class="book-info">
                            <h3 class="book-title">%s</h3>
                            %s
                            <div class="book-rating"><strong>评分：</strong>
                                <div class="rating">
                                    <div class="stars-outer">
                                        <div class="stars-inner" style="width:%s%%;"></div>
                                    </div>
                                </div>
                                %s
                            </div>
                            <span class="book-author"><strong>作者：</strong>%s</span>
                            <span class="book-publisher"><strong>出版社：</strong>%s</span>
                            %s
                            %s
                            <span class="book-pubdate"><strong>出版年：</strong>%s</span>
                            %s
                        </div>
                    </div>',
            htmlspecialchars($book['id']),
            htmlspecialchars($book['cover_url']),
            htmlspecialchars($book['title']),
            htmlspecialchars($book['title']),
            $subtitle,
            htmlspecialchars($ratingPercentage),
            htmlspecialchars($book['rating']),
            htmlspecialchars($book['author']),
            htmlspecialchars($book['publisher']),
            $originTitle,
            $translator,
            htmlspecialchars($book['pubdate']),
            $description
        );
    }

    private static function buildGoodHtml($good)
    {
        $price = !empty($good['price']) ? '￥' . htmlspecialchars($good['price']) : '';
        $specification = !empty($good['specification']) ? htmlspecialchars($good['specification']) : '';
        if ($price && $specification) {
            $price = ' / ' . $price;
        }

        return sprintf(
            '<div class="good-item">
                        <div class="good-img">
                            <img src="%s" alt="%s" />
                        </div>
                        <div class="good-meta">
                            <div class="good-brand good-category">%s · %s</div>
                            <div class="good-name">%s
                                <span class="good-price good-specification">%s%s</span>
                            </div> 
                        </div>
                    </div>',
            htmlspecialchars($good['image_url']),
            htmlspecialchars($good['name']),
            htmlspecialchars($good['brand']),
            htmlspecialchars($good['category']),
            htmlspecialchars($good['name']),
            $specification,
            $price
        );
    }

}
