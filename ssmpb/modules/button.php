<?php

$c_classes = 'buttons';
$button = get_field( 'button' );

echo '<div' . $this->getComponentClasses( $c_classes ) . '>';

if ( $button ) {
//   foreach( $buttons as $button ) {
      $target = '_blank';
    // $target = $button['target'] == 'New Tab' ? '_blank' : '_self';
    echo '<a class="button" href="' . $button['url'] . '" target="' . $target . '">' . $button['label'] . '</a>';
  }
// }

echo '</div>';