<?php get_header(); ?>

		<main>
			<h1>Search Results for &ldquo;<?php the_search_query(); ?>&rdquo;</h1>
			<?php while(have_posts()): the_post(); ?>
				<a class="search__result" href="<?php the_permalink(); ?>">
					<h2><?php the_title(); ?></h2>
				</a>
			<?php endwhile; ?>
		</main>

<?php get_footer(); ?>
