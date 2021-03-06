<?php
/**
 * BuddyPress - Groups Cover Image Header.
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

/**
 * Fires before the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_group_header' ); ?>

<?php
$real_cover_image_url = bp_attachments_get_attachment('url', array(
	'object_dir' => 'groups',
	'item_id' => bp_get_group_id(),
));

if (!$real_cover_image_url) {
	$bg_style = 'style="background: url(\'' . default_cover_image(bp_get_group_id()) . '\') no-repeat"';
}
else {
	$bg_style = '';
}
?>

<div id="cover-image-container">
	<a id="header-cover-image" href="<?php echo esc_url( bp_get_group_permalink() . 'forum/' ); ?>" <?=$bg_style?>></a>

	<div id="item-header-cover-image">
		<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
			<div id="item-header-avatar">
				<a href="<?php echo esc_url( bp_get_group_permalink() . 'forum/' ); ?>" title="<?php echo esc_attr( bp_get_group_name() ); ?>">

					<?php bp_group_avatar(); ?>

				</a>
			</div><!-- #item-header-avatar -->
		<?php endif; ?>

		<div id="item-header-content">

			<div id="item-buttons"><?php

				/**
				 * Fires in the group header actions section.
				 *
				 * @since 1.2.6
				 */
				if (is_user_logged_in()) {
					do_action( 'bp_group_header_actions' ); ?></div><!-- #item-buttons --><?php
				}
				else {
					?>
					<div class="generic-button group-button public" id="groupbutton-1">
						<a href="#" title="加入群组" class="group-button join-group" data-toggle="modal" data-target="#loginModal">加入群组</a>
					</div>
					</div>
					<?php
				}

			/**
			 * Fires before the display of the group's header meta.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_group_header_meta' ); ?>

			<div id="item-meta">

				<?php

				/**
				 * Fires after the group header actions section.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_group_header_meta' ); ?>

				<span class="highlight"><?php bp_group_type(); ?></span>
				<span class="activity"><?php printf( __( 'active %s', 'buddypress' ), bp_get_group_last_active() ); ?></span>

				<?php bp_group_description(); ?>

			</div>
		</div><!-- #item-header-content -->

		<div id="item-actions">

			<?php if ( bp_group_is_visible() ) : ?>

				<h4><?php //_e( 'Group Admins', 'buddypress' ); ?></h4>

				<?php //bp_group_list_admins();

				/**
				 * Fires after the display of the group's administrators.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_after_group_menu_admins' );

				if ( bp_group_has_moderators() ) :

					/**
					 * Fires before the display of the group's moderators, if there are any.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_before_group_menu_mods' ); ?>

					<h4><?php _e( 'Group Mods' , 'buddypress' ); ?></h4>

					<?php bp_group_list_mods();

					/**
					 * Fires after the display of the group's moderators, if there are any.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_after_group_menu_mods' );

				endif;

			endif; ?>

		</div><!-- #item-actions -->

	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->

<?php

/**
 * Fires after the display of a group's header.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_group_header' );

/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
do_action( 'template_notices' ); ?>
