<?php
/**
 * CodeIgnighter layout support library
 *  with Twig like inheritance blocks
 *
 * v 1.0
 *
 *
 * @author Constantin Bosneaga
 * @email  constantin@bosneaga.com
 * @url    http://a32.me/
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Layout {
    private $obj;
    private $layout_view;
    private $title = '';
    private $header = '';
    private $footer = '';
    private $meta_list = array(),$css_list = array(),$post_css_list = array(), $pre_js_list = array(), $js_list = array(), $post_js_list = array();
    private $block_list, $block_new, $block_replace = false;
    private $pre_code_for_header=array();
    
    function Layout() {
        $this->obj =& get_instance();
        $this->layout_view = "layouts/default.php";
        // Grab layout from called controller
        if (isset($this->obj->layout_view)) $this->layout_view = $this->obj->layout_view;
    }

    function view($view, $data = null, $return = false) {
        // Render template
        $data['content_for_layout'] = $this->obj->load->view($view, $data, true);
        $data['title_for_layout'] = $this->title;
        $data['header_for_layout'] = $this->header;
        $data['footer_for_layout'] = $this->footer;
        $data['pre_code_for_header']=  $this->pre_code_for_header;
        
        $data['meta_for_layout'] = '';
        foreach ($this->meta_list as $property=>$content)
            $data['meta_for_layout'] .='<meta property="'.$property.'" content="'.$content.'" />';
        // Render resources
        $data['pre_js_for_layout'] = '';
        foreach ($this->pre_js_list as $v)
            $data['pre_js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);
        $data['js_for_layout'] = '';
        foreach ($this->js_list as $v)
            $data['js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);
        $data['post_js_for_layout'] = '';
        foreach ($this->post_js_list as $v)
            $data['post_js_for_layout'] .= sprintf('<script type="text/javascript" src="%s"></script>', $v);
        $data['css_for_layout'] = '';
        foreach ($this->css_list as $v)
            $data['css_for_layout'] .= sprintf('<link rel="stylesheet" type="text/css"  href="%s" />', $v);
        $data['post_css_for_layout'] = '';
        foreach ($this->post_css_list as $v)
            $data['post_css_for_layout'] .= sprintf('<link rel="stylesheet" type="text/css"  href="%s" />', $v);

        // Render template
        $this->block_replace = true;
        $output = $this->obj->load->view($this->layout_view, $data, $return);

        return $output;
    }

    /**
     * Set page title
     *
     * @param $title
     */
    function title($title) {
        $this->title = $title;
    }
    /**
     * Adds doc meta in head section
     * @param $item
     * @param $value
     */
    function meta($property,$content) {
        $this->meta_list[$property] = $content;
    }
     /**
     * Set page header
     *
     * @param $header
     */
    function header($header,$data = null) {
        $this->header = $this->obj->load->view($header, $data, true);
    }
      /**
     * Set page footer
     *
     * @param $footer
     */
    function footer($footer,$data = null) {
        $this->footer = $this->obj->load->view($footer, $data, true);
    }
    /**
     * Adds Javascript resource to current page
     * @param $item
     * @paran boolean $is_url(if complete url true)
     */
    function preJs($item,$is_url=FALSE) {
        $this->pre_js_list[] = ($is_url)?$item:base_url($item);
    }
    /**
     * Adds Javascript resource to current page
     * @param $item
     * @paran boolean $is_url(if complete url true)
     */
    function js($item,$is_url=FALSE) {
        $this->js_list[] = ($is_url)?$item:base_url($item);
    }
    /**
     * Adds Javascript resource to current page
     * @param $item
     * @paran boolean $is_url(if complete url true)
     */
    function postJs($item,$is_url=FALSE) {
        $this->post_js_list[] = ($is_url)?$item:base_url($item);
    }
    /**
     * Adds CSS resource to current page
     * @param $item
     * @paran boolean $is_url(if complete url true)
     */
    function css($item,$is_url=FALSE) {
        $this->css_list[] = ($is_url)?$item:base_url($item);
    }
    /**
     * Adds CSS resource to current page
     * @param $item
     * @paran boolean $is_url(if complete url true)
     */
    function postCss($item,$is_url=FALSE) {
        $this->post_css_list[] = ($is_url)?$item:base_url($item);
    }
    /**
     * Twig like template inheritance
     *
     * @param string $name
     */
    function block($name = '') {
        if ($name != '') {
            $this->block_new = $name;
            ob_start();
        } else {
            if ($this->block_replace) {
                // If block was overriden in template, replace it in layout
                if (!empty($this->block_list[$this->block_new])) {
                    ob_end_clean();
                    echo $this->block_list[$this->block_new];
                }
            } else {
                $this->block_list[$this->block_new] = ob_get_clean();
            }
        }
    }
    function pre_code_for_header($code=null)
    {
        $this->pre_code_for_header[]=$code;
    }

}
