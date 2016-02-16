<?php
/*########################################
************Get Image Function************
            ------------------
****#Name : get_image
****Plugin Name: Get Image
****Plugin URL: http://nivnoiman.com/
****Author: Niv Noiman
****Author URL: http://nivnoiman.com/
****Version: 1.4.0
######################
****#Text Domain : NN-Funciton
######################
****#Get / Input : array
######################
    get_image(array(
        'value' => '',          // URL | Object
        'system' => '',         // Redux | Field / ACF | Url
        'style' =>              // Image Style - STRING
        'id' => '',             // ID of the img tag [ < img id ='' /> ]
        'default' =>'false'     // by default is false. true will show your default image if you have error with your value.
        'size' => '',           // the name of the size ID of the picture ]
        'placehold',            // placehold.it Size [ string : e.g - 300X500 ]
        'wrap' => '',           // can be additional wrap to this image ( for example : <div></div> )
        'default_src' => '',    // the src link to the default image [ 3 conditional : 1 - link | 2 - null | 3 - name.file ]
        'alt' => '',            // string or null - if null the input get the name of the image
        'default_alt' => '',    // this is will be the alt of this image if the real one no exist or not found in the image array
        'class' => '',          // string - adding this string to class in tag <img />
        'link' => '',           // if link is not empty the <img /> tag will have <a></a> tag wrapper.
        'link_id' => '',        // ID of the link tag [ < a id ='' /> ] that wrap the image tag.
        'link_title' => '',     // this is will be the title of your additional link.
        'link_class' => '',     // string - adding this string to class in tag <a></a>
        'link_target' => '',    // can get _blank | _self | _parent | _top
        'errorMsg' => 'false',  // error message trigger
    ));
######################
****#Return / Output :
######################
    1. <img id class src alt/>
    2. <a id class href target>
        <img id class src alt/>
       </a>
######################
************Get Image Function************
########################################*/

function get_image(array $init){

/*################ START - General ################*/

    // Define
    if( !defined('THEME') ){
        define( 'THEME',                        get_template_directory_uri() );
    }
    if( !defined('THEME_ROOT') ){
        define( 'THEME_ROOT',                   get_stylesheet_directory() );
    }
    if( !defined('THEME_DIRECTORY_PATH') ){
        define( 'THEME_DIRECTORY_PATH',         '/images/' );
    }

    if( !defined('DEFAULT_SRC_FILE') ){
        define( 'DEFAULT_SRC_FILE',             'default.png' );
    }
    if( !defined('DEFAULT_SRC_LINK') ){
        define( 'DEFAULT_SRC_LINK',             ''  );
    }
    if( !defined('DEFAULT_PLACEHOLD') ){
        define( 'DEFAULT_PLACEHOLD',            '350x150'  );
    }
    if( !defined('DEFAULT_SIZE') ){
        define( 'DEFAULT_SIZE',                 ''  );
    }

    if( !defined('DEFAULT_IMG_CLASS') ){
        define( 'DEFAULT_IMG_CLASS',            'nn-image'  );
    }
    if( !defined('DEFAULT_IMG_ALT') ){
        define( 'DEFAULT_IMG_ALT',              __('Image','NN-Funciton')  );
    }
    if( !defined('DEFAULT_LINK_TARGET') ){
        define( 'DEFAULT_LINK_TARGET',          '_self'  );
    }

    if( !defined('DEFAULT_LINK_TITLE') ){
        define( 'DEFAULT_LINK_TITLE',           __('Image Link','NN-Funciton')  );
    }
    if( !defined('DEFAULT_LINK_CLASS') ){
        define( 'DEFAULT_LINK_CLASS',           'nn-image-link'  );
    }
    if( !defined('ERROR_VALUE_NULL') ){
        define( 'ERROR_VALUE_NULL',             __('Error - System: "value" need to be announced','NN-Funciton') );
    }
    if( !defined('WARRNING_FIELD_IS_EMPTY') ){
        define( 'WARRNING_FIELD_IS_EMPTY',      __('Warrning - System: "Field Array" is not exist','NN-Funciton') );
    }

    if( !defined('WARRNING_REDUX_IS_EMPTY') ){
        define( 'WARRNING_REDUX_IS_EMPTY',      __('Warrning - System: "Redux Array" is not exist','NN-Funciton') );
    }
    if( !defined('WARRNING_THUMBNAIL_IS_EMPTY') ){
        define( 'WARRNING_THUMBNAIL_IS_EMPTY',  __('Warrning - System: "Thumbnail Object" is empty','NN-Funciton') );
    }


    // Vars
    $errorFlag = false; //error flag
    $errorMsg = ''; //error content
    $init_errorMsg = false; //error message trigger
    $imageStyle = '';
    $imageSrc = '';

    $init['errorMsg'] = '';
    $init['style'] = isset( $init['style'] ) ? $init['style'] : '';
    $init['link'] = isset( $init['link'] ) ? $init['link'] : '';
    $init['link_id'] = isset( $init['link_id'] ) ? $init['link_id'] : '';
    $init['wrap'] = isset( $init['wrap'] ) ? $init['wrap'] : '';


    if( $init['errorMsg'] ){
        $init_errorMsg = $init['errorMsg'];
    }


    $isRedux = false; // System -> Redux Flag
    $isField = false; // System -> Field Flag
    $isThumbnail = false; // System -> Thumbnail Flag
    $isUrl = false; // System -> Url Flag

    $defaultFlag = false;

    $goDefault = false; // if is true print all default.
    $printThumbnail = false; // if is true print the thumbnail of this page.

/*################ END - General ################*/

/*################ START - Define all options and vars ################*/

/************IMAGE DEFINE************
####################################*/


    if( $init['style'] ){
        $imageStyle = 'style="' . $init['style'] . '"';
    }

    // Default Image Define
    if( empty( $init['default'] ) ){
        $defaultFlag = false;
    } else{
        $defaultFlag = $init['default'];
    }

    // Image Size Define
    if( empty( $init['size'] ) ){
        $imageSize = DEFAULT_SIZE;
    } else{
        $imageSize = $init['size'];
    }

    // Image Default Size PlaceHold.it
    if( empty( $init['placehold'] ) ){
        $defaultImageSize = DEFAULT_PLACEHOLD;
    } else{
        $defaultImageSize = $init['placehold'];
    }

    // Image Default Src Define
    if( empty( $init['default_src'] ) ){ // if default src not exist

        $defaultPath = THEME_ROOT.THEME_DIRECTORY_PATH.DEFAULT_SRC_FILE;
        if( file_exists( $defaultPath ) ){
            $imageDefaultSrc = THEME.THEME_DIRECTORY_PATH.DEFAULT_SRC_FILE;
        } else{
            if( DEFAULT_SRC_LINK == '' )
                $imageDefaultSrc = 'http://placehold.it/'.$defaultImageSize;
            else
                $imageDefaultSrc = DEFAULT_SRC_LINK;
        }

    }
    else{  // if default src exist
        if( filter_var( $init['default_src'],FILTER_VALIDATE_URL ) ){ // if default src is link
            $imageDefaultSrc = $init['default_src'];
        } else{ // if default src is file
            $defaultPath = THEME_ROOT.THEME_DIRECTORY_PATH.$init['default_src'];
            if( file_exists( $defaultPath ) ){
                $imageDefaultSrc = THEME.THEME_DIRECTORY_PATH.$init['default_src'];
            } else{
                if( DEFAULT_SRC_LINK == '' )
                    $imageDefaultSrc = 'http://placehold.it/'.$defaultImageSize;
                else
                    $imageDefaultSrc = DEFAULT_SRC_LINK;
            }
        }

    }

    // Image Default Alt Define
    if( empty( $init['default_alt'] ) ){
        $imageAlt = DEFAULT_IMG_ALT;
    } else{
        $imageAlt = $init['default_alt'];
    }

    // Image Class Define
    if( empty( $init['class'] ) ){
        $imageClass = DEFAULT_IMG_CLASS;
    } else{
        $imageClass = DEFAULT_IMG_CLASS.' '.$init['class'];
    }

/************LINK DEFINE************
####################################*/

    // Link Target Define
    if( empty( $init['link_target'] ) ){
        $linkTarget = DEFAULT_LINK_TARGET;
    } else{
        if( preg_match( '/(\_self|\_blank|\_parent|\_top)$/', $init['link_target'] ) ){ // check if link target is one of the regular targets
            $linkTarget = $init['link_target'];
        } else{
            $linkTarget = DEFAULT_LINK_TARGET;
        }
    }

    // Link Class Define
    if( empty( $init['link_class'] ) ){
        $linkClass = DEFAULT_LINK_CLASS;
    } else{
        $linkClass = DEFAULT_LINK_CLASS.' '.$init['link_class'];
    }

    // Link Title Define
    if( empty( $init['link_title'] ) ){
        $linkTitle = DEFAULT_LINK_TITLE;
    } else{
        $linkTitle = $init['link_title'];
    }


/*################ END - Define all options and vars ################*/


/*################ START ################*/

    if( empty( $init['value'] ) ){ // if Value is empty
        if( isset( $init['system'] ) && $init['system'] == 'thumbnail' ){
            $printThumbnail = true;
        }
        else{
            $goDefault = true;
        }
    } else{ // START -if Value is not empty

        if( !filter_var( $init['value'],FILTER_VALIDATE_URL ) && !is_numeric( $init['value'] ) ){ // START - If Value is NOT Link

            switch( $init['system'] ){
                case 'redux':
                    $isRedux = true;
                break;

                case 'field':
                case 'acf':
                    $isField = true;
                break;

                case 'thumbnail':
                    $isThumbnail = true;
                    $printThumbnail = true;
                break;

                case 'url':
                    $isUrl = true;
                break;

                default: // default is Default image
                    $goDefault = true;
                break;
            }
            if( $isField ){
                // Check if this Field Object is exists
                if( is_array( $init['value'] ) ){ // exists

                    $imageField = $init['value'];

                    if( $imageSize == '' || is_array( $imageSize )){ // if Size not announced
                        $imageSrc = $imageField['url'];
                    } else{ // if Size announced
                        if( $imageField['sizes'][$imageSize] )
                            $imageSrc = $imageField['sizes'][$imageSize];
                        else
                            $imageSrc = $imageField['url'];

                    }

                } else{ // not exists
                    $goDefault = true;
                    $errorMsg .= '<p class="nn-warrning">'.WARRNING_FIELD_IS_EMPTY.'</p>';
                }
            }

            else if( $isRedux ){
                // Check if this Redux Object is exists
                if( is_array( $init['value'] ) ){ // exists

                    $imageRedux = $init['value'];

                    if( $imageSize == ''){ // if Size not announced
                        $imageSrc = $imageRedux['url'];
                    } else{ // if Size announced
                        $imageSrc_size = wp_get_attachment_image_src( $imageRedux['id'],$imageSize);
                        if( $imageSrc_size[0] )
                            $imageSrc = $imageSrc_size[0];
                        else
                            $imageSrc = $imageRedux['url'];
                    }

                } else{ // not exists
                    $goDefault = true;
                    $errorMsg .= '<p class="nn-warrning">'.WARRNING_REDUX_IS_EMPTY.'</p>';
                }

            }

            else if( $isThumbnail ){

                // Check if this Thumbnail Object is exists
                if( $init['value'] == new stdClass() ){ // not exists

                    $goDefault = true;
                    $errorMsg .= '<p class="nn-warrning">'.WARRNING_THUMBNAIL_IS_EMPTY.'</p>';
                }
                else{ // exists

                }

            }

            else if( $isUrl ){
                $imageSrc = $init['value'];
            }

        } // END - If Value is NOT Link
        else if( is_numeric( $init['value'] ) ){ // START - If Value is ID

            $imageField = wp_get_attachment_image_src($init['value'], $imageSize, false);
            //$imageFieldObject = wp_get_attachment_metadata($init['value']);

            $imageSrc  = $imageField[0];
        }
        else{ // START - If Value is Link

            $isUrl = true;
            $imageSrc = $init['value'];
        } // END - If Value is Link

        if( empty( $init['alt'] ) ){ // if Image Function Alt is NULL
            if( empty( $imageField['alt'] ) ){ // if Image Field Alt is NULL

                if( empty( $imageField['title'] ) ){ // if Image Field Title is NULL and Image Field Alt is NULL
                    $imageAlt = $imageAlt;
                } else{ // if Image Field Title exist
                    $imageAlt = $imageField['title'];
                }
            } else{ // if Image Field Alt exist
                $imageAlt = $imageField['alt'];
            }
        } else{ // if Image Function Alt is exist
            $imageAlt = $init['alt'];
        }

    }  // END -if Value is not empty

    if( $printThumbnail ){ // if printThumbnail is true use thumbnail function
        if ( has_post_thumbnail() ) {
            return the_post_thumbnail();
        } else{
            $goDefault = true;
        }

    }
    if( !$goDefault ){ // if goDefault is false result -> As planned

        if( empty( $init['id'] ) ){
            $result = '<img src="'.$imageSrc.'" class="'.$imageClass.'" alt="'.$imageAlt.'" '.$imageStyle.' />';
        } else{
            $result = '<img id="'.$init['id'].'" src="'.$imageSrc.'" class="'.$imageClass.'" alt="'.$imageAlt.'" '.$imageStyle.' />';
        }

    } // END - if goDefault is false
    else{ // if goDefault is true result -> default

        if( empty( $init['id'] ) ){
            $result = '<img src="'.$imageDefaultSrc.'" class="'.$imageClass.'" alt="'.$imageAlt.'" '.$imageStyle.' />';
        }
        else{
            $result = '<img id="'.$init['id'].'" src="'.$imageDefaultSrc.'" class="'.$imageClass.'" alt="'.$imageAlt.'" '.$imageStyle.' />';
        }

    } // END - if goDefault is true

    if( $init['link'] ){ // if link exist
        if( $init['link_id'] ){
            $start_link = '<a id="'.$init['link_id'].'" href="'.$init['link'].'" class="'.$linkClass.'" title="'.$linkTitle.'" target="'.$linkTarget.'">';
        } else{
            $start_link = '<a href="'.$init['link'].'" class="'.$linkClass.'" title="'.$linkTitle.'" target="'.$linkTarget.'">';
        }
        $end_link = '</a>';

        $result = $start_link.$result.$end_link;

    }

    if( $init['wrap'] ){ // if there Wrapper to this image tag

        $string = explode('></',$init['wrap'] );
        $step = 0;
        $new_string = '';

        foreach( $string as $value ){

            if( count($string) > $step){
              $new_string .= $value.'>';
              $new_string .= $result.'</';
              $result = '';
            }
            if( count($string) == ( $step+1 )){
              $new_string = substr($new_string, 0, -3);
            }
            $step++;
        };

        $result = $new_string;
    }

    if( $init_errorMsg ){ // if error message flat is true
        return $errorMsg;
    }
    else{ // if error message flat is false
        if( !$errorFlag ){
            if(  ( !$defaultFlag ) && ( $imageSrc == '' ) ){ // if default flag is false and you need to go default
                return '';
            } else{
                return $result;
            }
        }
    }

/*################ END ################*/

};
