# get_image
WordPress - make it easy


-How to use -
First include this -func_get_image.php- to your project.

In your php code just type
get_image();


For more options :

get_image(array(

        'value' => '',          // URL | Object 
        
        'system' => '',         // Redux | Field | Url
        
        'id' => '',             // ID of the img tag [ < img id ='' /> ]
        
        'size' => '',           // the name of the size ID of the picture ]
        
        'wrap' => '',           // can be additional wrap to this image ( for example : <div></div> )
        
        'default_src' => '',    // the src link to the default image [ 3 conditional : 1 - link | 2 - null | 3 - name.file ]
        
        'alt' => '',            // string or null - if null the input get the name of the image
        
        'default_alt' => '',    // this is will be the alt of this image if the real one no exist or not found in the image
        
        'class' => '',          // string - adding this string to class in tag <img />
        
        'link' => '',           // if link is not empty the <img /> tag will have <a></a> tag wrapper.
        
        'link_id' => '',        // ID of the link tag [ < a id ='' /> ] that wrap the image tag.
        
        'link_title' => '',     // this is will be the title of your additional link.
        
        'link_class' => '',     // string - adding this string to class in tag <a></a>
        
        'link_target' => '',    // can get _blank | _self | _parent | _top
        
        'errorMsg' => 'false',  // error message trigger
        
    ));

