<?php
/**
 * BuddyBoss - Members Profile Loop
 *
 * @since BuddyPress 3.0.0
 * @version 3.1.0
 */

$edit_profile_link = trailingslashit( bp_displayed_user_domain() . bp_get_profile_slug() . '/edit' );
?>

<header class="entry-header profile-loop-header profile-header flex align-items-center">
	<h1 class="entry-title bb-profile-title"><?php esc_attr_e( 'Feeds', 'buddyboss-theme' ); ?></h1>

	<?php /*if( bp_is_my_profile() ) { ?>
		<a href="<?php echo $edit_profile_link; ?>" class="push-right button outline small"><?php esc_attr_e( 'Edit Profile', 'buddyboss-theme' ); ?></a>
	<?php } */?>
</header>

<?php bp_nouveau_xprofile_hook( 'before', 'loop_content' ); ?>

<div class="row">

<div class="col-sm-12 trending_news">
<h4 class="title_p">Trending News</a></h4>
<?php 
$user_id = get_current_user_id();

$current_user = wp_get_current_user();
$username = $current_user->user_login;
?>


<div class="post-grid bb-grid bb-latest-news-list" style="display: block">
<?php
    global $post, $is_related_posts;
    $posts_array = wp_list_pluck( $posts_array, 'ID' );
    $args = array(
        'post_type' => 'post',
        'post__in' => $posts_array,
        'post__not_in' => array( $post->ID ),
        'orderby' => 'post__in',
        'order' => 'ASC',
		'posts_per_page'=>'6',
		'author'=> $user_id,
        'ignore_sticky_posts' => true,
    );
    $post_query = new WP_Query( $args );
    $is_related_posts = true;
    // The Loop
    if ( $post_query->have_posts() ) {
        while ( $post_query->have_posts() ) {
        $post_query->the_post();
        get_template_part( 'template-parts/content', 'related-posts' );
        }
        /* Restore original Post Data */
        wp_reset_postdata();
    }
	else{ ?>
		<aside class="bp-feedback bp-messages info">
			<span class="bp-icon" aria-hidden="true"></span>
			<p>You have not created any Post yet.</p>
		</aside>
	<?php }
    $is_related_posts = false;
    ?>
</div>
</div>

<div class="col-sm-12 juicer">
<h4 class="title_p">Trending on social media</a></h4>
<?php 
if($username == 'developer'){
	$user= 'BINARYSTAR_Co';
}
if($username == 'attsoftwarellc'){
	$user= 'attsoftware';
}
echo do_shortcode('[social-wall]
    [custom-twitter-feeds screenname="'.$user.'"]
[/social-wall]
');
?>
</div>

<div class="col-sm-8 group_list_p">

<h4 class="title_p"><a href="https://binary-star.plus/members/<?php echo $username; ?>/groups/">Groups</a> <span><a href="https://binary-star.plus/members/<?php echo $username; ?>/groups/">Show All</a></span></h4>



<?php $cover_class = bp_disable_group_cover_image_uploads() ? 'bb-cover-disabled' : 'bb-cover-enabled'; ?>

<?php if ( bp_has_groups( bp_ajax_querystring('groups') . '&order=ASC&per_page=6')) : ?>

	<?php bp_nouveau_pagination( 'top' ); ?>

	<ul id="groups-list" class="item-list groups-list bp-list bb-cover-enabled">

	<?php
	while ( bp_groups() ) :
		bp_the_group();
	?>

		<li class="item-entry odd public is-admin is-member group-has-avatar" data-bp-item-id="3" data-bp-item-component="groups">
			<div class="list-wrap">

				<?php if( !bp_disable_group_cover_image_uploads() ) { ?>
					<?php
					$group_cover_image_url = bp_attachments_get_attachment( 'url', array(
						'object_dir' => 'groups',
						'item_id'    => bp_get_group_id(),
					) );
					$default_group_cover   = buddyboss_theme_get_option( 'buddyboss_group_cover_default', 'url' );
					$group_cover_image_url = $group_cover_image_url ?: $default_group_cover;
					?>
					<div class="bs-group-cover only-grid-view"><a href="<?php bp_group_permalink(); ?>"><img src="<?php echo $group_cover_image_url; ?>"></a></div>
				<?php } ?>

				<?php if ( ! bp_disable_group_avatar_uploads() ) : ?>
					<div class="item-avatar">
						<a href="<?php bp_group_permalink(); ?>" class="group-avatar-wrap"><?php bp_group_avatar( bp_nouveau_avatar_args() ); ?></a>

						<div class="groups-loop-buttons only-grid-view">
							<?php bp_nouveau_groups_loop_buttons(); ?>
						</div>
					</div>
				<?php endif; ?>

				<div class="item">
					<div class="item-block">

						<h2 class="list-title groups-title"><?php bp_group_link(); ?></h2>

						<?php if ( bp_nouveau_group_has_meta() ) : ?>

							<p class="item-meta group-details only-list-view"><?php bp_nouveau_group_meta(); ?></p>
							
						<?php endif; ?>
					</div>

					<?php bp_nouveau_groups_loop_item(); ?>

				</div>
			</div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php bp_nouveau_pagination( 'bottom' ); ?>

<?php else : ?>

	<?php bp_nouveau_user_feedback( 'groups-loop-none' ); ?>

<?php endif; ?>

</div>
<div class="col-sm-4 forum_p">
<h4 class="title_p"><a href="https://binary-star.plus/members/<?php echo $username; ?>/forums/">Forums</a> <span><a href="https://binary-star.plus/members/<?php echo $username; ?>/forums/">Show All</a></span></h4>
<div id="bbp-user-topics-started" class="bbp-user-topics-started">
		<h2 class="screen-heading topics-started-screen"><?php _e( 'Forum Discussions Started', 'buddyboss' ); ?></h2>
		<div class="bbp-user-section">

			<?php if ( bbp_get_user_topics_started() ) : ?>

				<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

				<?php bbp_get_template_part( 'loop', 'topics' ); ?>

				<?php bbp_get_template_part( 'pagination', 'topics' ); ?>

			<?php else : ?>

				<aside class="bp-feedback bp-messages info">
					<span class="bp-icon" aria-hidden="true"></span>
					<p><?php bbp_is_user_home() ? _e( 'You have not created any discussions.', 'buddyboss' ) : _e( 'This user has not created any discussions.', 'buddyboss' ); ?></p>
				</aside>

			<?php endif; ?>

		</div>
	</div><!-- #bbp-user-topics-started -->

</div>


<div class="col-sm-4 document_lib">
<h4 class="title_p"><a href="https://binary-star.plus/members/<?php echo $username; ?>/document-library/">Documents</a> <span><a href="https://binary-star.plus/members/<?php echo $username; ?>/document-library/">Show All</a></span></h4>
<?php echo do_shortcode('[posts_table post_type="document" columns="title" author="'.$user_id.'"]'); ?>

</div>

<div class="col-sm-8 photo_p">
<h4 class="title_p"><a href="https://binary-star.plus/members/<?php echo $username; ?>/photos/">Photos</a> <span><a href="https://binary-star.plus/members/<?php echo $username; ?>/photos/">Show All</a></span></h4>

<div class="bb-media-container member-media">

	<?php bp_get_template_part( 'media/theatre' ); ?>

	<?php
			bp_nouveau_member_hook( 'before', 'media_content' );

			?>

			<div id="media-stream" class="media profile_media" data-bp-list="media">
				<div id="bp-ajax-loader"><?php bp_nouveau_user_feedback( 'member-media-loading' ); ?></div>
			</div><!-- .media -->

			<?php
			bp_nouveau_member_hook( 'after', 'media_content' );
			
	?>
</div>

</div>

</div>
<?php
bp_nouveau_xprofile_hook( 'after', 'loop_content' );
