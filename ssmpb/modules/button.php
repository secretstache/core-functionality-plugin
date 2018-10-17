<?php

  $module = 'button';
  $option = 'option';

  $label = get_sub_field( $module . '_label' );
  $url = get_sub_field( $module . '_url' );
  $target = ( get_sub_field( $option . '_target' ) == 'new_tab' ) ? '_blank' : '_self';

?>

<div <?php echo $this->getModuleClasses( $module ) ?> >

    <a class="button" href="<?php echo $url; ?>" target="<?php echo $target; ?>"> <?php echo $label; ?> </a>

</div>