<?php
// Shortcode function to display the news filtering form and posts
function custom_tabs_shortcode() {
    ob_start();
    ?>
    <div class="col-11 col-md-11 col-lg-11 col-xl-11 mx-auto position-relative">
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="fw-bold">Filter by Category:</label>
                        <select id="category-filter" class="form-control">
                            <option value="">All Categories</option>
                            <?php
                            $categories = get_categories(array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                                'exclude' => get_cat_ID('Uncategorized')
                            ));

                            foreach ($categories as $category) {
                                echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-4">
                        <label class="fw-bold">Filter by Tag:</label>
                        <select id="tag-filter" class="form-control">
                            <option value="">All Tags</option>
                            <?php
                            $tags = get_tags(array(
                                'orderby' => 'name',
                                'order' => 'ASC',
                                'hide_empty' => false,
                            ));

                            foreach ($tags as $tag) {
                                echo '<option value="' . $tag->term_id . '">' . $tag->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-4">
                        <label>&nbsp;</label><br>
                        <button id="reset-filters" class="btn btn-primary">Reset</button>
                    </div>
                </div>
            </div>
            <div class="row" id="blog-posts-container">
            </div>
            <div class="row">
                <div class="col-md-12">
                    <nav aria-label="Page navigation">
                        <ul id="pagination" class="pagination justify-content-center">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

// Function to handle AJAX request for filtering posts
function filter_posts_callback() {
    $categoryID = isset($_POST['category']) ? $_POST['category'] : '';
    $tagID = isset($_POST['tag']) ? $_POST['tag'] : '';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 5,
        'paged' => $paged,
        'post_status' => 'publish',
    );

    if ($categoryID) {
        $args['cat'] = $categoryID;
    }

    if ($tagID) {
        $args['tag__in'] = $tagID;
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="row no-gutters">
                        <div class="col-md-4">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php the_post_thumbnail_url('full'); ?>" class="card-img" alt="<?php the_title(); ?>">
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                <p class="card-text"><?php echo custom_trim_excerpt(get_the_excerpt(), 300); ?></p>
                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Read more</a>
                                <p class="card-text mt-2"><small class="text-muted">Posted on <?php echo get_the_date(); ?> | <span class="post-categories">
                                    <?php
                                    $categories = get_the_category();
                                    $category_names = array();
                                    if ($categories) {
                                        foreach ($categories as $category) {
                                            $category_names[] = $category->name;
                                        }
                                        echo implode(', ', $category_names);
                                    }
                                    ?>
                                </span> <span class="post-tags">
                                    <?php
                                    $tags = get_the_tags();
                                    $tag_names = array();
                                    if ($tags) {
                                        foreach ($tags as $tag) {
                                            $tag_names[] = $tag->name;
                                        }
                                        echo (' | ');
                                        echo implode(', ', $tag_names);
                                    }
                                    ?>
                                </span></small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else :
        echo "<p>No posts found</p>";
    endif;

    wp_reset_postdata();

    $posts_content = ob_get_clean();

    echo json_encode(array(
        'posts' => $posts_content,
        'total_posts' => $query->found_posts,
        'pagination' => $query->max_num_pages > 1 ? get_pagination_links($query->max_num_pages, $paged) : '',
    ));

    die();
}

// Custom excerpt trimming function
function custom_trim_excerpt($text, $length) {
    if (strlen($text) > $length) {
        $text = substr($text, 0, $length);
        $text = substr($text, 0, strrpos($text, ' '));
        $text .= '...';
    }
    return $text;
}

// Function to generate pagination links
function get_pagination_links($total_pages, $current_page) {
    $links = '';
    $prev_page = $current_page - 1;
    $next_page = $current_page + 1;
    $prev_disabled = ($current_page == 1) ? 'disabled' : '';
    $next_disabled = ($current_page == $total_pages) ? 'disabled' : '';

    $links .= '<li><button data-page="' . $prev_page . '" class="prev button" ' . $prev_disabled . '>Prev</button></li>';

    for ($i = 1; $i <= $total_pages; $i++) {
        if ($current_page == $i) {
            $links .= '<li><span class="current">' . $i . '</span></li>';
        } else {
            $links .= '<li><button data-page="' . $i . '" class="button">' . $i . '</button></li>';
        }
    }

    $links .= '<li><button data-page="' . $next_page . '" class="next button" ' . $next_disabled . '>Next</button></li>';

    return $links;
}

// Register shortcode
function register_news_shortcode() {
    add_shortcode('custom-news', 'custom_tabs_shortcode');
}

add_action('init', 'register_news_shortcode');

// Handle AJAX requests
add_action('wp_ajax_filter_posts', 'filter_posts_callback');
add_action('wp_ajax_nopriv_filter_posts', 'filter_posts_callback');
?>
