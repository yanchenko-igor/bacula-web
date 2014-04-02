<?php

/*
  +-------------------------------------------------------------------------+
  | Copyright 2010-2014, Davide Franco			                          |
  |                                                                         |
  | This program is free software; you can redistribute it and/or           |
  | modify it under the terms of the GNU General Public License             |
  | as published by the Free Software Foundation; either version 2          |
  | of the License, or (at your option) any later version.                  |
  |                                                                         |
  | This program is distributed in the hope that it will be useful,         |
  | but WITHOUT ANY WARRANTY; without even the implied warranty of          |
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           |
  | GNU General Public License for more details.                            |
  +-------------------------------------------------------------------------+
 */

class View extends Smarty {
    public $title;
    protected $template_file;

    protected $model;

    private $language;
    private $charset;
    private $domaine;   
    
    public function __construct( $model ) {
    
        if(!is_null($model))
            $this->model = $model;
        else
            throw new Exception('Provided Model class (' . get_class($model) . ') is null');

        $this->init();
    }

    protected function init() {
        $this->compile_check = true;
        $this->debugging = false;
        $this->force_compile = true;

        $this->template_dir = TEMPLATES_DIR;
        $this->compile_dir  = VIEW_CACHE_DIR;

        // Setting up language translation
        $this->register_block('t', 'smarty_translate');
        $this->language = FileConfig::get_Value( 'language');
        $this->charset  = 'UTF-8';

        putenv("LANGUAGE=" . $this->language . '.' . $this->charset);
        putenv("LANG=" . $this->language . '.' . $this->charset);
        setlocale(LC_ALL, $this->language . '.' . $this->charset);

        bindtextdomain('messages', LOCALE_DIR);
        bind_textdomain_codeset('messages', $this->charset);
        textdomain($this->domaine);
    }

    public function render() {
        // Set page title
        $this->assign('page_name', $this->title );
        // Render the view 
        $this->display($this->template_file);
    }
}
?>
