<?php

    // Bail if section is set to inactive
    if (get_sub_field('option_status') == 0) {
        return;
    }

    global $tpl_args;
    
    $style = $this->getInlineStyles();

?>

<section <?php echo $this->getTemplateClasses() . $style ?> >;

    <?php echo $this->getVideoBackground(); ?>
    <?php echo $this->getTemplateHeader(); ?>

    <?php echo $this->buildColumns( 'template', $tpl_args ); ?>

</section>