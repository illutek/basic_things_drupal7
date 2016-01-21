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
