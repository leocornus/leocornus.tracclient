<?php

/**
 * generate the page select html to offer the available pages
 * 
 * @param $name template name
 * @param $page_id the page id.
 */
function wptc_pages_list_html($name, $page_id) {

    $select_html = wp_dropdown_pages(
        array(
            'name'             => $name,
            'id'               => $name,
            'echo'             => false,
            'show_option_none' => '- None -',
            'selected'         => $page_id
        )
    );

    return $select_html;
}
?>

<div class='wrap'>
  <h2>WordPress Trac Client - Page Templates Management</h2>

  <p>
  Associate a WordPress page with each Trac client template.
  </p>

  <?php echo wptc_page_list('trac', ); ?>

</div>
