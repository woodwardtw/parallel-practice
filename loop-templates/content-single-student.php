<?php
/**
 * Single student partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

	<header class="entry-header">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<button id="profileButton" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profile">
		  Edit Your Name
		</button>

	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php 
			if(get_post_custom_values('author_login')){
				$p_student_login = get_post_custom_values('author_login')[0];
			} else {
				$p_student_login = '';
			}
		 	
			if(current_user_can( 'activate_plugins' ) || is_user_logged_in() && get_current_user_id() === get_user_by('login', $p_student_login)->ID):?>
		<div id="chart"></div>
		<?php
		the_content();
		pp_practice_log();
		?>
		<button id="logButton" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#logData">
		  Add practice
		</button>		
	<?php endif;?>
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<a href="<?php echo wp_logout_url();?>">logout</a>
		<!-- Modal -->
		<div class="modal fade" id="logData" tabindex="-1" aria-labelledby="practice-title" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
		    <div class="modal-content">
		      <div class="modal-body">
		      	<h2 id="practice-title">Practice Logger</h2>
		       <?php echo do_shortcode('[gravityform id="1" title="false" description="false" ajax="true"]')?>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		<!--modal end-->	
		<!-- Modal -->
		<div class="modal fade" id="comment" tabindex="-1" aria-labelledby="practice-title" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
		    <div class="modal-content">
		      <div class="modal-body">
		      	<h2 id="practice-title">Comment</h2>
		       <?php echo do_shortcode('[gravityform id="4" title="false" description="false" ajax="true"]')?>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		<!--modal end-->
		<!-- Modal -->
		<div class="modal fade" id="profile" tabindex="-1" aria-labelledby="profile-title" aria-hidden="true">
		  <div class="modal-dialog modal-xl">
		    <div class="modal-content">
		      <div class="modal-body">
		      	<h2 id="profile-title">Update your name</h2>
		       <?php echo do_shortcode('[gravityform id="5" title="false" description="false" ajax="true"]')?>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>
		<!--modal end-->				
		<?php understrap_entry_footer(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->

