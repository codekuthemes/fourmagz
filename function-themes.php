Replace Line below in /inc/theme-function.php:1088

`/* Fourmagz Related Post */
if( ! function_exists('fourmagz_related_post') ) {
	function fourmagz_related_post(){
		global $post;
		$tags = wp_get_post_terms( get_queried_object_id(), 'post_tag', ['fields' => 'ids'] );
		$args = [
			'post__not_in'        => array( get_queried_object_id() ),
			'posts_per_page'      => 6,
			'ignore_sticky_posts' => 1,
			'orderby'             => 'rand',
			'tax_query' => [
				[
					'taxonomy' => 'post_tag',
					'terms'    => $tags
				]
			]
		];			
		$my_query = new wp_query( $args );
		if( $my_query->have_posts() ) { ?>
			<div id="relatedposts" class="relatedposts"><h3><?php _e( 'Related Posts', 'fourmagz' ); ?></h3><ul class="load_post">
			<?php while ($my_query->have_posts()) {
			$my_query->the_post(); ?>			
				<?php if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) { ?>
				<li><div class="relatedthumb"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'fourmagz-thumb-small' ); ?><h2><?php the_title(); ?></h2><?php if( has_post_format('video') ) : ?><span class="fa-relatid"><i class="fa fa-play-circle-o"></i></span><?php elseif ( has_post_format('audio') ) : ?><span class="fa-relatid"><i class="fa fa-music"></i></span><?php elseif( has_post_format('gallery') ) : ?><span class="fa-relatid"><i class="fa fa-picture-o"></i></span><?php endif; ?></a></div></li>
				<?php } ?>			
			<?php } 
			wp_reset_postdata();
			echo '</ul></div>';
		}
	}
}`

to:

`/* Fourmagz Related Post */
if( ! function_exists('fourmagz_related_post') ) {
	function fourmagz_related_post(){
		$orig_post = $post;
		global $post;
		$categories = get_the_category($post->ID);
		if ($categories) {
			$category_ids = array();
			foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;

			$args=array(
				'category__in' => $category_ids,
				'post__not_in' => array($post->ID),
				'posts_per_page'=> 6,
				'caller_get_posts'=>1
			);

			$my_query = new wp_query( $args );
			if( $my_query->have_posts() ) { ?>	
				<div id="relatedposts" class="relatedposts"><h3><?php _e( 'Related Posts', 'fourmagz' ); ?></h3><ul class="load_post">
				<?php while ($my_query->have_posts()) {
				$my_query->the_post(); ?>			
					<?php if ( (function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) { ?>
					<li><div class="relatedthumb"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'fourmagz-thumb-small' ); ?><h2><?php the_title(); ?></h2><?php if( has_post_format('video') ) : ?><span class="fa-relatid"><i class="fa fa-play-circle-o"></i></span><?php elseif ( has_post_format('audio') ) : ?><span class="fa-relatid"><i class="fa fa-music"></i></span><?php elseif( has_post_format('gallery') ) : ?><span class="fa-relatid"><i class="fa fa-picture-o"></i></span><?php endif; ?></a></div></li>
					<?php } ?>			
				<?php }
				echo '</ul></div>';
			}
		}
		$post = $orig_post;
		wp_reset_query();
	}
}`
