CodeIgniter Simple Template Library
===================================

This template library for Codeigniter lets you build simples templates using partial. This library loads a template file that uses partial views. It also creates a menu from the configuration file.

Installation
------------

Copy the files to the corresponding folder in your application folder.

Configuration
-------------

In your template.php config file you can change following configuration parameters (optional):

    $config['template'] = 'default template filename';
    $config['template_folder'] = 'template folder from views folder';
    $config['menu_translation'] = 'lang file from language folder used to translate menu names';
    $config['menus'] = array(
            'menu_id' => array(
                    'partial/view/path/used' => array(
                            'name' => 'Menu name',
                            'link' => 'App URI link'
                        );
                    ...
                ),
            ....
        );

The 'partial/view/path/used', actualy could be anything but it's used to activate the current menu by matching this value to the view passed to view method of the Template object. 

If you prefer, you can autoload the library by adjusting your autoload.php file and add 'template' to the $autoload['libraries'] array.
    
Template file
--------------

Template file is loaded using the 'template' config value as filename into the 'template_folder' folder.

You can have a template like this:

    <head>
        <title><?php echo $page_title; ?></title>
    </head>
    <body>
        <?php echo $menus['menu_id'] ?>
        <?php echo $main_content; ?>
    </body>


$main_content value will be the redered view passed through view template method:
    
    $this->template->view('path/main_content_view',$some_data);

Partial views
-------------

Partial views will have the data passed through the view template method available. The same way as the view Codeigniter method.

