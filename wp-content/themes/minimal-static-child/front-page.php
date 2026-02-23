<?php
/*
 * Front page template: Steam-like portfolio layout
 */
get_header();
?>
<?php $asset_base = get_stylesheet_directory_uri() . '/assets'; ?>
<div class="steam-portfolio">
    <header class="steam-topbar">
        <div class="steam-topbar-inner">
            <div class="steam-brand">
                <h1 class="steam-name"><?php bloginfo( 'name' ); ?></h1>
                <div class="steam-sub"><?php echo get_bloginfo( 'description' ); ?></div>
            </div>
            <nav class="steam-nav" aria-label="Primary">
                <?php
                if ( has_nav_menu( 'primary' ) ) {
                    wp_nav_menu( array( 'theme_location' => 'primary', 'container' => '', 'menu_class' => 'steam-nav-list' ) );
                } else {
                    $about_page = get_page_by_path( 'about' );
                    $about_label = $about_page ? get_the_title( $about_page ) : __( 'About', 'minimal-static-child' );
                    $github = get_theme_mod( 'github_url', '' );
                    $linkedin = get_theme_mod( 'linkedin_url', '' );
                    $gh_href = $github ? esc_url( $github ) : '#';
                    $li_href = $linkedin ? esc_url( $linkedin ) : '#';
                    echo '<a class="nav-btn" href="' . $gh_href . '" target="_blank" rel="noopener noreferrer">GitHub</a>';
                    echo '<a class="nav-btn" href="' . $li_href . '" target="_blank" rel="noopener noreferrer">LinkedIn</a>';
                    echo '<a class="nav-btn" href="' . esc_url( home_url( '/about' ) ) . '">' . esc_html( $about_label ) . '</a>';
                }
                ?>
            </nav>
        </div>
    </header>

    <main class="steam-main">
        <section class="steam-left">
            <div class="panel recent-activity">
                <h2>Recent Activity</h2>
                <?php
                $recent = new WP_Query( array( 'posts_per_page' => 5, 'post_type' => array( 'post', 'project' ) ) );
                if ( $recent->have_posts() ) :
                    while ( $recent->have_posts() ) : $recent->the_post();
                ?>
                <article class="activity-card">
                    <div class="thumb">
                        <?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'thumbnail' ); } else {
                            // Use a consistent framed fallback instead of separate SVG placeholders
                            ?><div class="thumb-fallback-small" aria-hidden="true"></div><?php
                        } ?>
                    </div>
                    <div class="meta">
                        <a href="<?php the_permalink(); ?>" class="activity-title"><?php the_title(); ?></a>
                        <div class="activity-sub">
                            <?php echo get_post_type(); ?> &middot; <?php echo get_the_date(); ?>
                        </div>
                    </div>
                </article>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <p>No recent activity yet.</p>
                <?php endif; ?>
            </div>
            <div class="panel projects-sample">
                <h3>Featured Projects</h3>
                <?php
                $proj = new WP_Query( array( 'post_type' => 'project', 'posts_per_page' => 3 ) );
                if ( $proj->have_posts() ) :
                    while ( $proj->have_posts() ) : $proj->the_post();
                ?>
                    <?php
                    // Prepare excerpt once for layout
                    $raw_excerpt = get_the_excerpt() ?: get_the_content();
                    $raw_excerpt = str_replace('\\n', "\n", $raw_excerpt);
                    $trimmed = wp_trim_words( wp_strip_all_tags( $raw_excerpt ), 18 );
                    ?>
                    <div class="project-card">
                        <a href="<?php the_permalink(); ?>" class="project-link">
                            <div class="project-title"><?php the_title(); ?></div>
                            <div class="project-content">
                                <div class="project-thumb">
                                    <div class="thumb-frame">
                                    <?php if ( has_post_thumbnail() ) {
                                        the_post_thumbnail( 'medium' );
                                    } else {
                                        echo '<div class="thumb-fallback" aria-hidden="true"></div>';
                                    } ?>
                                    </div>
                                </div>
                                <div class="project-right">
                                    <div class="project-excerpt"><?php echo nl2br( esc_html( $trimmed ) ); ?></div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else:
                ?>
                <?php if ( current_user_can( 'edit_posts' ) ) : ?>
                    <p>No projects yet — <a class="portfolio-button" href="<?php echo esc_url( admin_url( 'post-new.php?post_type=project' ) ); ?>">Add your first project</a></p>
                <?php else: ?>
                    <p>No projects yet.</p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <aside class="steam-right">
            <?php
            // Scan the theme assets/ folder for existing badge files so we only render files that exist
            $asset_dir = get_stylesheet_directory() . '/assets';
            $techstack = array();
            if ( is_dir( $asset_dir ) ) {
                $found = glob( $asset_dir . '/badge-*.{svg,png,jpg,jpeg,gif}', GLOB_BRACE );
                if ( $found ) {
                    foreach ( $found as $f ) {
                        $techstack[] = basename( $f );
                    }
                }
            }
            $tech_count = count( $techstack );
            ?>
            <div class="panel sidebar-summary">
                <h4>Summary</h4>
                <ul class="summary-list">
                    <li>Posts: <?php $p = wp_count_posts( 'post' ); echo intval( $p->publish ); ?></li>
                    <li>Projects: <?php $pp = wp_count_posts( 'project' ); echo intval( $pp->publish ); ?></li>
                    <li>Techstack: <?php echo intval( $tech_count ); ?></li>
                </ul>
            </div>
            <div class="panel sidebar-links">
                <h4>Links</h4>
                <?php
                // Resolve common pages by slug when present, otherwise fall back to sensible paths
                $about_page = get_page_by_path( 'about' );
                $contact_page = get_page_by_path( 'contact' );
                $projects_page = get_page_by_path( 'projects' );
                $about_url = $about_page ? get_permalink( $about_page ) : home_url( '/about' );
                $contact_url = $contact_page ? get_permalink( $contact_page ) : home_url( '/contact' );
                $projects_url = $projects_page ? get_permalink( $projects_page ) : home_url( '/projects' );
                ?>
                <ul>
                    <li><a href="<?php echo esc_url( $about_url ); ?>">About / Resume</a></li>
                    <li><a href="<?php echo esc_url( $contact_url ); ?>">Contact</a></li>
                    <li><a href="<?php echo esc_url( $projects_url ); ?>">All Projects</a></li>
                </ul>
            </div>
            <div class="panel badges-panel">
                <h4>Techstack</h4>
                <div class="badges-row">
                    <?php foreach ( $techstack as $t ) : ?>
                        <img class="badge-img" src="<?php echo esc_url( $asset_base . '/' . $t ); ?>" alt="<?php echo esc_attr( pathinfo( $t, PATHINFO_FILENAME ) ); ?>" />
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </main>

</div>

<?php get_footer();
