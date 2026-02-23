<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
get_header();
?>

<main>
<?php if ( have_posts() ) : ?>
  <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1><?php the_title(); ?></h1>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
    </article>
  <?php endwhile; ?>
<?php else : ?>
  <section class="no-posts">
    <h1><?php bloginfo( 'name' ); ?></h1>
    <p><?php echo esc_html( get_bloginfo( 'description' ) ); ?></p>
    <p>This is the Minimal Static theme — WordPress is still available for admin and REST API.</p>
  </section>
<?php endif; ?>

</main>

<?php get_footer(); ?>
