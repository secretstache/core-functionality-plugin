<?php

    global $tpl_args;
    
    $style = $this->getInlineStyles( 'hero_unit' );

?>

<section <?php echo $this->getHeroUnitClasses() . $style ?> >

	<?php echo $this->getVideoBackground( 'hero_unit' ); ?>
	<?php echo $this->buildColumns( 'hero_unit', $tpl_args ); ?>

</section>