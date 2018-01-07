<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://developpeur-web-toulouse.fr/
 * @since      1.0.0
 *
 * @package    Fake_Real_Text
 * @subpackage Fake_Real_Text/admin/partials
 */

$id1 = $this->generate_fake_post();
$id2 = $this->generate_fake_post('post', 'en_US');
echo "Generated posts with ids: $id1, $id2";
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
