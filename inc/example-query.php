<?php
$args      = [
	'post_type'      => 'pages',
	'posts_per_page' => - 1,
	'meta_query'=> [
		[
			'key' => 'event_date',
			'compare' => '>=',
			'value' => date("Y-m-d"),
			'type' => 'DATE',
		]
	],
];
$the_query = new WP_Query( $args ); ?>
	<div class="order-list">
		<?php
		while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
			<h1><?php the_title(); ?></h1>
			<h3><?php the_field( 'event_date' ); ?></h3>
			<?php the_content(); ?>
			<?php
		endwhile;
		?>
	</div>
<?php
wp_reset_postdata();
