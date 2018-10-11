<?php

// Bail if section is set to inactive
if (get_sub_field('status') == 0) {
    return;
}

global $tpl_args;
$style = $this->getInlineStyles();

echo '<section' . $this->getSectionClasses() . $style . '>';
$this->getVideoBackground();
$this->getSectionHeader();
$this->build('section', $tpl_args);
echo '</section>';