<?php
function theme_preprocess_html(&$variables) {
    // Add conditional stylesheets for IE
    drupal_add_css(path_to_theme() . '/css/ie.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'lte IE 7', '!IE' => FALSE), 'preprocess' => FALSE));
    drupal_add_css(path_to_theme() . '/css/ie6.css', array('group' => CSS_THEME, 'browsers' => array('IE' => 'IE 6', '!IE' => FALSE), 'preprocess' => FALSE));

    //Add external .js and .css
    drupal_add_js('https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', 'external');
    drupal_add_js('https://code.jquery.com/jquery-migrate-1.2.1.min.js', 'external');

    drupal_add_css('https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array('type' => 'external'));
}

/**
 * Preprocess function for page.tpl.php
 * Een voorbeeld om random slogans af te printen
 * in de header
 */

function theme_preprocess_page(&$variables) {
    $slogans = array(
        t('een eertse slogan.'),
        t('een tweede slogan zomaar wat'),
        t('en een derde slogan weet het niet meer')
    );
    $slogan = $slogans[array_rand($slogans)];
    $variables['site_slogan'] = $slogan;
    //kpr($variables);

    //om enkel op de frontpage een aangepast css te laden
    if ($variables['is_front'] = TRUE) {
        drupal_add_css(path_to_theme(). '/css/front.css' , array('group' => CSS_THEME, ));
        }
}



function theme_form_alter(&$form, &$form_state, $form_id){
    if ($form_id == 'search_block_form'){
        $form['actions']['submit']['#type'] = 'image_button';
        $form['actions']['submit']['src'] = drupal_get_path('theme', 'theme_name') . '/images/search.png';
        //extra om eventueel de css class aan te passen
        $form['actions']['submit']['attributes']['class'][] = 'class_name';
    }
}

/**
 * Preprocess function for node.tpl.php.
 */

/**
 * Hier de code voor de node--article.tpl.php file
 *<div class="content"<?php print $content_attributes; ?»
 *<div class="dateblock"›
 *<span class="month"><?php print $submitted_month; ?></span>
 *<span class="day"><?php print $submitted_day; ?></span>
 *<span class="year"><?php print $submitted_yead; ?></span>
 *</div>
 */

function theme_preprocess_node(&$variables)
{
    //dit om de aanmaak datum te lay-outen, de bijhorende node--article.tpl.php
    // juist hierboven in comment

if ($variables['type'] == 'article') {
        $node = $variables['node'];
        $variables['submitted_day'] = format_date($node->created, 'custom', 'j');
        $variables['submitted_month'] = format_date($node->created, 'custom', 'M');
        $variables['submitted_year'] = format_date($node->created, 'custom', 'Y');
    }

    // hier voor een aangepaste versie voor de dag van de week er wordt hier een
    // node--dag.tpl.php aangemaakt zou dit ook kunnen gebruiken voor de bepaalde periodes
    // van het jaar bv kerst een aangepaste versie de tpl controleren hierna een aparte .css
    // file laden bijvoorbeeld
    if ($variables['type'] == 'page') {
        $today = strtolower(date('l'));
        $variables['theme_hook_suggestions'] = 'node__' . $today;
        $variables['day_of_the_week'] = $today;
        kpr($variables);
    }
}

/**
 * Overrides theme_menu_tree()
 */
function yourtheme_menu_tree(&$variables) {
    return '<ul class="menu nav navbar-nav">' . $variables['tree'] . '</ul>';
}

//dropdown
/**
 * Overrides theme_menu_link().
 */
function yourtheme_menu_link(array $variables) {
    $element = $variables['element'];
    $sub_menu = '';

    if ($element['#below']) {
        // Prevent dropdown functions from being added to management menu so it
        // does not affect the navbar module.
        if (($element['#original_link']['menu_name'] == 'management') && (module_exists('navbar'))) {
            $sub_menu = drupal_render($element['#below']);
        }
        elseif ((!empty($element['#original_link']['depth'])) && ($element['#original_link']['depth'] == 1)) {
            // Add our own wrapper.
            unset($element['#below']['#theme_wrappers']);
            $sub_menu = '<ul class="dropdown-menu">' . drupal_render($element['#below']) . '</ul>';
            // Generate as standard dropdown.
            $element['#title'] .= ' <span class="caret"></span>';
            $element['#attributes']['class'][] = 'dropdown';
            $element['#localized_options']['html'] = TRUE;

            // Set dropdown trigger element to # to prevent inadvertant page loading
            // when a submenu link is clicked.
            $element['#localized_options']['attributes']['data-target'] = '#';
            $element['#localized_options']['attributes']['class'][] = 'dropdown-toggle';
            $element['#localized_options']['attributes']['data-toggle'] = 'dropdown';
        }
    }
    // On primary navigation menu, class 'active' is not set on active menu item.
    // @see https://drupal.org/node/1896674
    if (($element['#href'] == $_GET['q'] || ($element['#href'] == '<front>' && drupal_is_front_page())) && (empty($element['#localized_options']['language']))) {
        $element['#attributes']['class'][] = 'active';
    }
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}

