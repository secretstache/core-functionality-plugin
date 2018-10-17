<?php

    $module = 'image';
    $option = 'option';

    $image = get_sub_field( $module . '_inner_image' );

?>

<div <?php echo $this->getModuleClasses( $module ); ?> >

  <img src=" <?php echo $image['url']; ?>" alt=" <?php echo $image['alt']; ?>" />

</div>