<?php
/**
 * 页面上展示GitHub代码库
 * 
 * @package GitHubShow 
 * @author HyanCat
 * @version 0.0.1
 * @link http://hyancat.com
 */
class GitHubShow_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     * 
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('GitHubShow_Plugin', 'addFooter');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->contentEx = array('GitHubShow_Plugin', 'parse');
        Typecho_Plugin::factory('Widget_Abstract_Contents')->excerptEx = array('GitHubShow_Plugin', 'parse');
        return "插件启用成功，请在设置里面查看用法";
    }
    
    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     * 
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}
    
    /**
     * 获取插件配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
        $name = new Typecho_Widget_Helper_Form_Element_Radio('text', NULL, '',
            _t('使用说明：<br>文章中使用 [github show="JeffreyZhao/jscex"/] 即可嵌入该git.'));
        $form->addInput($name);
    }
    
    /**
     * 个人用户的配置面板
     * 
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}
    
    /**
     * 引入js
     */
    public static function addFooter()
    {
        echo '<script type="text/javascript" src="' . Helper::options()->pluginUrl . '/GitHubShow/js/jquery.githubRepoWidget.js"></script>' . "\n";
    }

    /**
     * 解析内容
     * @access public
     * @param  [type] $text       [description]
     * @param  [type] $widget     [description]
     * @param  [type] $lastResult [description]
     * @return void
     */
    public static function parse($text, $widget, $lastResult)
    {
        $text = empty($lastResult) ? $text : $lastResult;        
        $partten = '/\[github show="(.*)"\]/';
        if ($widget instanceof Widget_Archive) {
            preg_match_all($partten, $text, $matches);
            if (count($matches[1]) > 0) {
                $text = preg_replace($partten, '<div class="github-widget" data-repo="$1"></div>', $text);
            }
        }
        return $text;
    }

}
