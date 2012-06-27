<?php
/**
 * minify.php
 *
 * It minifies and caches JS and CSS files on the server and the browser
 *
 * Files created have been merged, minified and compressed to provide the fastest download
 *
 * * * MASTER * * *
 * It merges all the necessary JS or CSS files 
 * it return them to the browser in 1 big compress and minified file
 *
 * * * FILE * *
 * To process a single file
 *
 * HTACCESS
 * File is being "RewriteRule" in .htaccess
 * It's being accessed: /assets/cache/(css|js) to this file
 *
 *  # CACHE COMPRESSOR.
    # Get file from cache js|css. It puts together all the big js or css and serve in one big compress and minified file
    # @example /public/cache/js
    RewriteRule ^Assets/cache/(.+) public/applications/cache_compressor/cache.php?type=master&file=$1 [L]

    # Cache single JS or CSS request loaded for faster experience
    #RewriteRule ^(.*\.(css|js))$ public/applications/cache_compressor/cache.php?type=file&file=$1 [L,NC]

 *
 *
 * @since: Aug 11 2009
 * 
 * @updated May 22 2011
 */



//------------------------------------------------------------------------------

/**
 * The setup
 */

$BASE_PATH = "../..";

include($BASE_PATH."/Application/init.php");


// The location of the statc cache
define("CACHE_DIR",APPLICATION_TMP_PATH."/minified");
    if(!is_dir(CACHE_DIR))
        @mkdir(CACHE_DIR,0775,true);

define("JS_DIR",ASSETS_PATH."/js");

define("CSS_DIR",ASSETS_PATH."/css");

define("THIRDPARTY_DIR",ASSETS_PATH."/3rdParty");

//------------------------------------------------------------------------------



$Min = new AddOn\Minify\File(CACHE_DIR);


//------------------------------------------------------------------------------
/**
 * Arguments
 * @type: master|file
 * @file: (if @type==master => js|css) else it's the file path
 */

// Get the type: MASTER | FILE
$type = strtolower(trim($_REQUEST[type]));

// Remove leading and trailing slash
$file = strtolower(trim(preg_replace("/^\/|\/$/","",$_REQUEST[file])));


        // Precede the file with the base dir so we go to the real path on the server
        $filePath = BASE_PATH."/".$file;

        // We'll use it to get the extension of the file which is the type
        $_pathInfo = pathinfo($filePath);

          // Voila!
          $Min
            ->setType(strtolower($_pathInfo[extension]))
            ->add(array($filePath))
            ->exec();
