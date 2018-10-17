
<?php

    $module = 'html_editor';
    $html_editor = get_sub_field( $module . '_inner_text' );

?>

<div <?php echo $this->getModuleClasses( 'default' ); ?>>

	<?php echo $html_editor; ?>

</div>