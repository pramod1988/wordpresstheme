
   </div>
	</div><!-- .site-content -->

	<footer id="colophon" class="site-footer padding" role="contentinfo">
		<?php if ( is_active_sidebar( 'footer-a' ) || is_active_sidebar( 'footer-b') || is_active_sidebar( 'footer-c' ) ) : ?>

	  <div class="footer">
			
		<div class="section-inner container">
		  <div class="row">
		
			<?php if ( is_active_sidebar( 'footer-a' ) ) : ?>
			
				<div class="col-xs-4">
				
					<div class="widgets text-left">
			
						<?php dynamic_sidebar( 'footer-a' ); ?>
											
					</div>
					
				</div>
				
			<?php endif; ?> <!-- /footer-a -->
				
			<?php if ( is_active_sidebar( 'footer-b' ) ) : ?>
			
				<div class="col-xs-4">
				
					<div class="widgets text-left">
			
						<?php dynamic_sidebar( 'footer-b' ); ?>
											
					</div> <!-- /widgets -->
					
				</div>
				
			<?php endif; ?> <!-- /footer-b -->
								
			<?php if ( is_active_sidebar( 'footer-c' ) ) : ?>
			
				<div class="col-xs-4">
			
					<div class="widgets text-left">
			
						<?php dynamic_sidebar( 'footer-c' ); ?>
											
					</div> <!-- /widgets -->
					
				</div>
				
			<?php endif; ?> <!-- /footer-c -->
			
			<div class="clear"></div>
		
		</div> <!-- /footer-inner -->
	
	</div> <!-- /footer -->

<?php endif; ?>


		<div class="site-info">
			     <a href="<?php echo esc_url( __( 'https://twitter.com/pramodpandey05', 'material-blog-story' ) ); ?>"><?php printf( __( 'Theme by Pramod Pandey', 'material-blog-story' ), '' ); ?></a>
		</div><!-- .site-info -->
	</footer><!-- .site-footer -->

</div><!-- .site -->

<?php wp_footer(); ?>

</body>
</html>
