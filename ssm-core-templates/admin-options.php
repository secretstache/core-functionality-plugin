<div class="wrap">

<?php if ( get_option('ssm_core_agency_name') ) { ?>

    <h1><?php echo get_option('ssm_core_agency_name'); ?> Admin Core</h1>

 <?php } else { ?>

    <h1>Admin Core</h1>

 <?php } ?>

<form method="post" action="options.php">

    <?php settings_fields( 'ssm-core-settings-group' ); ?>
    <?php do_settings_sections( 'ssm_core' ); ?>

    <?php submit_button(); ?>

</form>

</div>