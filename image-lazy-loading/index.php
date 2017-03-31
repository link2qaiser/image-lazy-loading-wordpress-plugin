<?php
   /*
   Plugin Name: Images Lazy Loading
   Plugin URI: http://dixeam.com
   Description: Too many images make site slow, this plugin load the images when scroll down.
   Version: 1.2
   Author: Dixeam Developer- Qaiser
   Author URI: http://dixeam.com
   License: GPL2
   */
// returns the content of $GLOBALS['post']
// if the page is called 'debug'
if ( ! is_admin() ) {
   function load_lazy_images($content) {

      $pattern = '/srcset="(.*?)"/';
      if (preg_match_all($pattern, $content, $matchess)) {
         foreach($matchess[0] as $key=>$val){
            $content    =     str_replace($val, '', $content);
         }
      }

      $pattern = '/<img(.*?)>/';
      $images  = "";
      if (preg_match_all($pattern, $content, $matchess)) {
         foreach($matchess[0] as $key=>$val){            
            $images     .=     $val.'\n';
         }
      }
      $images = ' <noscript>'.$images.'</noscript>';

      $pattern = '/src="(.*?)"/';
      if (preg_match_all($pattern, $content, $matchess)) {
         $srcs = $matchess;
         $matchess    =  array();
         foreach($srcs[0] as $key=>$val){
            $src        =     str_replace('src="', "", $val);
            $src        =     str_replace('"', "", $src);
            $content    =     str_replace($val, 'data-original="'.$src.'"', $content);
        }
     }
     return $content.$images;
   }
   add_filter( 'the_content', 'load_lazy_images' );
   function load_lazy_js() {
      echo '<script type="text/javascript" src="'.plugin_dir_url(__FILE__).'lazyload.js"></script>';
      echo '<script></script>';
   }
   add_action( 'wp_footer', 'load_lazy_js' );
}