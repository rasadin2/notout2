<?php
/**
 * Plugin Name: Cricket Matches
 * Plugin URI: https://notout.com
 * Description: Custom post type for cricket match management with meta fields and frontend display
 * Version: 1.0.0
 * Author: NotOut
 * Author URI: https://notout.com
 * Text Domain: cricket-matches
 * Domain Path: /languages
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CRICKET_MATCHES_VERSION', '1.0.0');
define('CRICKET_MATCHES_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CRICKET_MATCHES_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * Register Custom Post Type
 */
function cricket_matches_register_post_type() {
    $labels = array(
        'name'                  => _x('Cricket Matches', 'Post Type General Name', 'cricket-matches'),
        'singular_name'         => _x('Cricket Match', 'Post Type Singular Name', 'cricket-matches'),
        'menu_name'            => __('Cricket Matches', 'cricket-matches'),
        'name_admin_bar'       => __('Cricket Match', 'cricket-matches'),
        'archives'             => __('Match Archives', 'cricket-matches'),
        'attributes'           => __('Match Attributes', 'cricket-matches'),
        'parent_item_colon'    => __('Parent Match:', 'cricket-matches'),
        'all_items'            => __('All Matches', 'cricket-matches'),
        'add_new_item'         => __('Add New Match', 'cricket-matches'),
        'add_new'              => __('Add New', 'cricket-matches'),
        'new_item'             => __('New Match', 'cricket-matches'),
        'edit_item'            => __('Edit Match', 'cricket-matches'),
        'update_item'          => __('Update Match', 'cricket-matches'),
        'view_item'            => __('View Match', 'cricket-matches'),
        'view_items'           => __('View Matches', 'cricket-matches'),
        'search_items'         => __('Search Match', 'cricket-matches'),
        'not_found'            => __('Not found', 'cricket-matches'),
        'not_found_in_trash'   => __('Not found in Trash', 'cricket-matches'),
        'featured_image'       => __('Match Image', 'cricket-matches'),
        'set_featured_image'   => __('Set match image', 'cricket-matches'),
        'remove_featured_image'=> __('Remove match image', 'cricket-matches'),
        'use_featured_image'   => __('Use as match image', 'cricket-matches'),
        'insert_into_item'     => __('Insert into match', 'cricket-matches'),
        'uploaded_to_this_item'=> __('Uploaded to this match', 'cricket-matches'),
        'items_list'           => __('Matches list', 'cricket-matches'),
        'items_list_navigation'=> __('Matches list navigation', 'cricket-matches'),
        'filter_items_list'    => __('Filter matches list', 'cricket-matches'),
    );

    $args = array(
        'label'               => __('Cricket Match', 'cricket-matches'),
        'description'         => __('Cricket match information', 'cricket-matches'),
        'labels'              => $labels,
        'supports'            => array('title', 'thumbnail', 'editor', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'menu_icon'           => 'dashicons-awards',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type('cricket_match', $args);
}
add_action('init', 'cricket_matches_register_post_type', 0);

/**
 * Add Meta Boxes
 */
function cricket_matches_add_meta_boxes() {
    // Meta box for custom post type
    add_meta_box(
        'cricket_match_details',
        __('Match Details', 'cricket-matches'),
        'cricket_matches_meta_box_callback',
        'cricket_match',
        'normal',
        'high'
    );

    // Meta box for default posts
    add_meta_box(
        'cricket_post_match_details',
        __('Cricket Match Data', 'cricket-matches'),
        'cricket_matches_post_meta_box_callback',
        'post',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'cricket_matches_add_meta_boxes');

/**
 * Meta Box Callback
 */
function cricket_matches_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('cricket_match_meta_box', 'cricket_match_meta_box_nonce');

    // Retrieve existing values
    $series_badge = get_post_meta($post->ID, '_cricket_series_badge', true);
    $is_popular = get_post_meta($post->ID, '_cricket_is_popular', true);
    $team_name_1 = get_post_meta($post->ID, '_cricket_team_name_1', true);
    $team_name_2 = get_post_meta($post->ID, '_cricket_team_name_2', true);
    $match_time = get_post_meta($post->ID, '_cricket_match_time', true);
    $win_probability_team = get_post_meta($post->ID, '_cricket_win_probability_team', true);
    $win_probability_percentage = get_post_meta($post->ID, '_cricket_win_probability_percentage', true);
    $prediction_text = get_post_meta($post->ID, '_cricket_prediction_text', true);
    $total_bets = get_post_meta($post->ID, '_cricket_total_bets', true);
    $odds = get_post_meta($post->ID, '_cricket_odds', true);
    $bet_button_text = get_post_meta($post->ID, '_cricket_bet_button_text', true);
    $bet_button_url = get_post_meta($post->ID, '_cricket_bet_button_url', true);

    ?>
    <style>
        .cricket-meta-field { margin-bottom: 15px; }
        .cricket-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .cricket-meta-field input[type="text"],
        .cricket-meta-field input[type="number"],
        .cricket-meta-field textarea { width: 100%; padding: 8px; }
        .cricket-meta-field textarea { min-height: 80px; }
    </style>

    <div class="cricket-meta-field">
        <label for="cricket_series_badge"><?php _e('Series Badge (e.g., আর্মিপ্রিমিয়ার ২০২৫)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_series_badge" name="cricket_series_badge" value="<?php echo esc_attr($series_badge); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label>
            <input type="checkbox" id="cricket_is_popular" name="cricket_is_popular" value="1" <?php checked($is_popular, '1'); ?> />
            <?php _e('Mark as Popular (🔥 জনপ্রিয় পছন্দ)', 'cricket-matches'); ?>
        </label>
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_team_name_1"><?php _e('Team Name 1', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_team_name_1" name="cricket_team_name_1" value="<?php echo esc_attr($team_name_1); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_team_name_2"><?php _e('Team Name 2 (Optional)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_team_name_2" name="cricket_team_name_2" value="<?php echo esc_attr($team_name_2); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_match_time"><?php _e('Match Time (e.g., আজ সন্ধ্যা ৭:০০ PM)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_match_time" name="cricket_match_time" value="<?php echo esc_attr($match_time); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_win_probability_team"><?php _e('Win Probability Team Name', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_win_probability_team" name="cricket_win_probability_team" value="<?php echo esc_attr($win_probability_team); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_win_probability_percentage"><?php _e('Win Probability Percentage (e.g., 65)', 'cricket-matches'); ?></label>
        <input type="number" id="cricket_win_probability_percentage" name="cricket_win_probability_percentage" value="<?php echo esc_attr($win_probability_percentage); ?>" min="0" max="100" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_prediction_text"><?php _e('Prediction Text', 'cricket-matches'); ?></label>
        <textarea id="cricket_prediction_text" name="cricket_prediction_text"><?php echo esc_textarea($prediction_text); ?></textarea>
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_total_bets"><?php _e('Total Bets (e.g., ৩,৫০০+)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_total_bets" name="cricket_total_bets" value="<?php echo esc_attr($total_bets); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_odds"><?php _e('Odds (e.g., 1.85)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_odds" name="cricket_odds" value="<?php echo esc_attr($odds); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_bet_button_text"><?php _e('Bet Button Text (e.g., এখনই বেট করুন →)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_bet_button_text" name="cricket_bet_button_text" value="<?php echo esc_attr($bet_button_text); ?>" />
    </div>

    <div class="cricket-meta-field">
        <label for="cricket_bet_button_url"><?php _e('Bet Button URL', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_bet_button_url" name="cricket_bet_button_url" value="<?php echo esc_attr($bet_button_url); ?>" placeholder="https://" />
    </div>
    <?php
}

/**
 * Meta Box Callback for Default Posts
 */
function cricket_matches_post_meta_box_callback($post) {
    // Add nonce for security
    wp_nonce_field('cricket_post_match_meta_box', 'cricket_post_match_meta_box_nonce');

    // Retrieve existing values
    $series_badge = get_post_meta($post->ID, '_cricket_post_series_badge', true);
    $is_popular = get_post_meta($post->ID, '_cricket_post_is_popular', true);
    $team_name_1 = get_post_meta($post->ID, '_cricket_post_team_name_1', true);
    $team_name_2 = get_post_meta($post->ID, '_cricket_post_team_name_2', true);
    $match_time = get_post_meta($post->ID, '_cricket_post_match_time', true);
    $win_probability_team = get_post_meta($post->ID, '_cricket_post_win_probability_team', true);
    $win_probability_percentage = get_post_meta($post->ID, '_cricket_post_win_probability_percentage', true);
    $prediction_text = get_post_meta($post->ID, '_cricket_post_prediction_text', true);
    $total_bets = get_post_meta($post->ID, '_cricket_post_total_bets', true);
    $odds = get_post_meta($post->ID, '_cricket_post_odds', true);
    $bet_button_text = get_post_meta($post->ID, '_cricket_post_bet_button_text', true);
    $bet_button_url = get_post_meta($post->ID, '_cricket_post_bet_button_url', true);

    ?>
    <style>
        .cricket-post-meta-field { margin-bottom: 15px; }
        .cricket-post-meta-field label { display: block; font-weight: bold; margin-bottom: 5px; }
        .cricket-post-meta-field input[type="text"],
        .cricket-post-meta-field input[type="number"],
        .cricket-post-meta-field textarea { width: 100%; padding: 8px; }
        .cricket-post-meta-field textarea { min-height: 80px; }
    </style>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_series_badge"><?php _e('Series Badge (e.g., আর্মিপ্রিমিয়ার ২০২৫)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_series_badge" name="cricket_post_series_badge" value="<?php echo esc_attr($series_badge); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label>
            <input type="checkbox" id="cricket_post_is_popular" name="cricket_post_is_popular" value="1" <?php checked($is_popular, '1'); ?> />
            <?php _e('Mark as Popular (🔥 জনপ্রিয় পছন্দ)', 'cricket-matches'); ?>
        </label>
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_team_name_1"><?php _e('Team Name 1', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_team_name_1" name="cricket_post_team_name_1" value="<?php echo esc_attr($team_name_1); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_team_name_2"><?php _e('Team Name 2 (Optional)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_team_name_2" name="cricket_post_team_name_2" value="<?php echo esc_attr($team_name_2); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_match_time"><?php _e('Match Time (e.g., আজ সন্ধ্যা ৭:০০ PM)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_match_time" name="cricket_post_match_time" value="<?php echo esc_attr($match_time); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_win_probability_team"><?php _e('Win Probability Team Name', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_win_probability_team" name="cricket_post_win_probability_team" value="<?php echo esc_attr($win_probability_team); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_win_probability_percentage"><?php _e('Win Probability Percentage (e.g., 65)', 'cricket-matches'); ?></label>
        <input type="number" id="cricket_post_win_probability_percentage" name="cricket_post_win_probability_percentage" value="<?php echo esc_attr($win_probability_percentage); ?>" min="0" max="100" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_prediction_text"><?php _e('Prediction Text', 'cricket-matches'); ?></label>
        <textarea id="cricket_post_prediction_text" name="cricket_post_prediction_text"><?php echo esc_textarea($prediction_text); ?></textarea>
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_total_bets"><?php _e('Total Bets (e.g., ৩,৫০০+)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_total_bets" name="cricket_post_total_bets" value="<?php echo esc_attr($total_bets); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_odds"><?php _e('Odds (e.g., 1.85)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_odds" name="cricket_post_odds" value="<?php echo esc_attr($odds); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_bet_button_text"><?php _e('Bet Button Text (e.g., এখনই বেট করুন →)', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_bet_button_text" name="cricket_post_bet_button_text" value="<?php echo esc_attr($bet_button_text); ?>" />
    </div>

    <div class="cricket-post-meta-field">
        <label for="cricket_post_bet_button_url"><?php _e('Bet Button URL', 'cricket-matches'); ?></label>
        <input type="text" id="cricket_post_bet_button_url" name="cricket_post_bet_button_url" value="<?php echo esc_attr($bet_button_url); ?>" placeholder="https://" />
    </div>
    <?php
}

/**
 * Save Meta Box Data for Custom Post Type
 */
function cricket_matches_save_meta_box_data($post_id) {
    // Check nonce for custom post type
    if (isset($_POST['cricket_match_meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['cricket_match_meta_box_nonce'], 'cricket_match_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save meta fields for custom post type
        $fields = array(
            'cricket_series_badge',
            'cricket_is_popular',
            'cricket_team_name_1',
            'cricket_team_name_2',
            'cricket_match_time',
            'cricket_win_probability_team',
            'cricket_win_probability_percentage',
            'cricket_prediction_text',
            'cricket_total_bets',
            'cricket_odds',
            'cricket_bet_button_text',
            'cricket_bet_button_url',
        );

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }

    // Check nonce for default posts
    if (isset($_POST['cricket_post_match_meta_box_nonce'])) {
        if (!wp_verify_nonce($_POST['cricket_post_match_meta_box_nonce'], 'cricket_post_match_meta_box')) {
            return;
        }

        // Check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save meta fields for default posts
        $post_fields = array(
            'cricket_post_series_badge',
            'cricket_post_is_popular',
            'cricket_post_team_name_1',
            'cricket_post_team_name_2',
            'cricket_post_match_time',
            'cricket_post_win_probability_team',
            'cricket_post_win_probability_percentage',
            'cricket_post_prediction_text',
            'cricket_post_total_bets',
            'cricket_post_odds',
            'cricket_post_bet_button_text',
            'cricket_post_bet_button_url',
        );

        foreach ($post_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            } else {
                delete_post_meta($post_id, '_' . $field);
            }
        }
    }
}
add_action('save_post', 'cricket_matches_save_meta_box_data');

/**
 * Shortcode for displaying matches
 * Usage: [cricket_matches limit="6"]
 */
function cricket_matches_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts, 'cricket_matches');

    $args = array(
        'post_type' => 'cricket_match',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        ?>
        <div class="cricket-container-match">
            <div class="container">
                <div class="matches-grid">
                    <?php while ($query->have_posts()) : $query->the_post();
                        $post_id = get_the_ID();
                        $series_badge = get_post_meta($post_id, '_cricket_series_badge', true);
                        $is_popular = get_post_meta($post_id, '_cricket_is_popular', true);
                        $team_name_1 = get_post_meta($post_id, '_cricket_team_name_1', true);
                        $team_name_2 = get_post_meta($post_id, '_cricket_team_name_2', true);
                        $match_time = get_post_meta($post_id, '_cricket_match_time', true);
                        $win_probability_team = get_post_meta($post_id, '_cricket_win_probability_team', true);
                        $win_probability_percentage = get_post_meta($post_id, '_cricket_win_probability_percentage', true);
                        $prediction_text = get_post_meta($post_id, '_cricket_prediction_text', true);
                        $total_bets = get_post_meta($post_id, '_cricket_total_bets', true);
                        $odds = get_post_meta($post_id, '_cricket_odds', true);
                        $bet_button_text = get_post_meta($post_id, '_cricket_bet_button_text', true) ?: 'এখনই বেট করুন →';
                        $bet_button_url = get_post_meta($post_id, '_cricket_bet_button_url', true);
                        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
                    ?>
                    <div class="match-card">
                        <div class="image-container">
                            <div class="image-box">
                                <?php if ($thumbnail_url) : ?>
                                    <img decoding="async" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="blog-image">
                                <?php endif; ?>
                            </div>

                            <div class="image-overlay">
                                <?php if ($series_badge) : ?>
                                    <span class="badge badge-series"><?php echo esc_html($series_badge); ?></span>
                                <?php endif; ?>
                                <?php if ($is_popular) : ?>
                                    <span class="badge badge-popular">🔥 জনপ্রিয় পছন্দ</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="match-content">
                            <div class="match-teams">
                                <?php if ($team_name_1) : ?>
                                    <span class="team-name"><?php echo esc_html($team_name_1); ?></span>
                                <?php endif; ?>
                                <?php if ($team_name_2) : ?>
                                    <span class="vs-text"> বনাম </span>
                                    <span class="team-name"><?php echo esc_html($team_name_2); ?></span>
                                <?php else : ?>
                                    <span class="vs-text"> বনাম</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($match_time) : ?>
                                <div class="match-time">
                                    🕐 <?php echo esc_html($match_time); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($win_probability_team || $win_probability_percentage || $prediction_text) : ?>
                                <div class="prediction-box">
                                    <?php if ($win_probability_team && $win_probability_percentage) : ?>
                                        <div class="prediction-header">
                                            📈 <?php echo esc_html($win_probability_team); ?> জয়ের সম্ভাবনা <?php echo cricket_convert_to_bengali_number(esc_html($win_probability_percentage)); ?>%
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($prediction_text) : ?>
                                        <div class="prediction-text">
                                            <?php echo esc_html($prediction_text); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($total_bets || $odds) : ?>
                                <div class="match-stats">
                                    <?php if ($total_bets) : ?>
                                        <span class="stat">👥 <?php echo cricket_convert_to_bengali_number(esc_html($total_bets)); ?> বেট</span>
                                    <?php endif; ?>
                                    <?php if ($odds) : ?>
                                        <span class="odds">অডস: <?php echo cricket_convert_to_bengali_number(esc_html($odds)); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($bet_button_url) : ?>
                                <a href="<?php echo esc_url($bet_button_url); ?>" class="bet-button">
                                    <?php echo esc_html($bet_button_text); ?>
                                </a>
                            <?php else : ?>
                                <button class="bet-button">
                                    <?php echo esc_html($bet_button_text); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    } else {
        echo '<p>' . __('No matches found.', 'cricket-matches') . '</p>';
    }

    return ob_get_clean();
}
add_shortcode('cricket_matches', 'cricket_matches_shortcode');



function cricket_matches_shortcode_list($atts) {

    $atts = shortcode_atts(array(
        'limit' => 6,
    ), $atts, 'cricket_matches');

    // Pagination fix
    $paged = max( 1, get_query_var('paged'), get_query_var('page') );

    $query = new WP_Query(array(
        'post_type'      => 'cricket_match',
        'posts_per_page' => intval($atts['limit']),
        'paged'          => $paged,
    ));

    ob_start();

    if ($query->have_posts()) :
    ?>
    <div class="blog-detail-list blog-list">
        <div class="container">

            <?php while ($query->have_posts()) : $query->the_post();

                $post_id     = get_the_ID();
                $badge       = get_post_meta($post_id, '_cricket_series_badge', true);
                $read_time   = get_post_meta($post_id, '_cricket_match_time', true) ?: '৪ মিনিট';
                $thumb       = get_the_post_thumbnail_url($post_id, 'full');
                $author      = get_the_author();
                $author_char = mb_substr($author, 0, 1);
            ?>

            <!-- CARD LOOP -->
            <div class="card">
                <div class="card-image">
                    <a href="<?php the_permalink(); ?>">
                        <?php if ($thumb) : ?>
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                        <?php else : ?>
                            <div class="cricket-list-placeholder">
                                <span class="placeholder-text">বিশেষজ্ঞ বিশ্লেষণ</span>
                            </div>
                        <?php endif; ?>
                    </a>

                    <?php if ($badge) : ?>
                        <span class="category-badge"><?php echo esc_html($badge); ?></span>
                    <?php endif; ?>
                </div>

                <div class="card-content">
                    <h2 class="card-title"><?php the_title(); ?></h2>

                    <div class="des-tex">
                        <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                    </div>

                    <div class="cricket-author-date-inline">
                        <div class="comment-pp">
                            <span><?php echo esc_html($author_char); ?></span>
                            <span class="pp-name"><?php echo esc_html($author); ?></span>
                        </div>
                        <div class="meta-item">
                            <svg class="meta-icon icon-calendar" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <span><?php echo cricket_convert_to_bengali_date(get_the_date('j F, Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END CARD -->

            <?php endwhile; ?>

            <!-- Load More Button -->
            <?php if ($query->max_num_pages > $paged) : ?>
            <div class="cricket-load-more-wrap">
                <button class="cricket-load-more-btn"
                        data-page="<?php echo $paged; ?>"
                        data-max-pages="<?php echo $query->max_num_pages; ?>"
                        data-limit="<?php echo $atts['limit']; ?>">
                    আরও দেখুন
                    <svg class="load-more-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div class="cricket-load-more-loader" style="display: none;">
                    <div class="spinner"></div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
    <?php
    wp_reset_postdata();
    endif;

    return ob_get_clean();
}
add_shortcode('cricket_matches_list', 'cricket_matches_shortcode_list');

/**
 * AJAX Handler for Load More
 */
function cricket_matches_list_load_more() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) + 1 : 2;
    $limit = isset($_POST['limit']) ? intval($_POST['limit']) : 6;

    $query = new WP_Query(array(
        'post_type'      => 'cricket_match',
        'posts_per_page' => $limit,
        'paged'          => $paged,
    ));

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            $post_id     = get_the_ID();
            $badge       = get_post_meta($post_id, '_cricket_series_badge', true);
            $thumb       = get_the_post_thumbnail_url($post_id, 'full');
            $author      = get_the_author();
            $author_char = mb_substr($author, 0, 1);
        ?>
        <div class="card">
            <div class="card-image">
                <a href="<?php the_permalink(); ?>">
                    <?php if ($thumb) : ?>
                        <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                    <?php else : ?>
                        <div class="cricket-list-placeholder">
                            <span class="placeholder-text">বিশেষজ্ঞ বিশ্লেষণ</span>
                        </div>
                    <?php endif; ?>
                </a>
                <?php if ($badge) : ?>
                    <span class="category-badge"><?php echo esc_html($badge); ?></span>
                <?php endif; ?>
            </div>
            <div class="card-content">
                <h2 class="card-title"><?php the_title(); ?></h2>
                <div class="des-tex">
                    <p><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                </div>
                <div class="cricket-author-date-inline">
                    <div class="comment-pp">
                        <span><?php echo esc_html($author_char); ?></span>
                        <span class="pp-name"><?php echo esc_html($author); ?></span>
                    </div>
                    <div class="meta-item">
                        <svg class="meta-icon icon-calendar" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span><?php echo cricket_convert_to_bengali_date(get_the_date('j F, Y')); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php
        endwhile;
        wp_reset_postdata();
    endif;

    wp_die();
}
add_action('wp_ajax_cricket_load_more', 'cricket_matches_list_load_more');
add_action('wp_ajax_nopriv_cricket_load_more', 'cricket_matches_list_load_more');

/**
 * Enqueue Load More Script
 */
function cricket_matches_enqueue_load_more_script() {
    wp_enqueue_script('cricket-load-more', plugin_dir_url(__FILE__) . 'js/load-more.js', array('jquery'), '1.0', true);
    wp_localize_script('cricket-load-more', 'cricketAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'cricket_matches_enqueue_load_more_script');

/**
 * Convert English numbers to Bengali (Bangla) numbers
 */
function cricket_convert_to_bengali_number($string) {
    $english_numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $bengali_numbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    return str_replace($english_numbers, $bengali_numbers, $string);
}

/**
 * Convert English date to Bengali (Bangla) format
 */
function cricket_convert_to_bengali_date($date_string) {
    $english_numbers = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $bengali_numbers = array('০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯');

    $english_months = array('January', 'February', 'March', 'April', 'May', 'June',
                           'July', 'August', 'September', 'October', 'November', 'December');
    $bengali_months = array('জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
                           'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর');

    // Replace English months with Bengali months
    $date_string = str_replace($english_months, $bengali_months, $date_string);

    // Replace English numbers with Bengali numbers
    $date_string = str_replace($english_numbers, $bengali_numbers, $date_string);

    return $date_string;
}

/**
 * Shortcode for displaying cricket data from default WordPress posts
 * Usage: [cricket_posts limit="6"]
 */
function cricket_posts_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
        'category' => '', // Optional category filter
    ), $atts, 'cricket_posts');

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish'
    );

    // Add category filter if specified
    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        ?>
        <div class="sports-post-container">
            <div class="container">
                <div class="grid">
                    <?php while ($query->have_posts()) : $query->the_post();
                        $post_id = get_the_ID();
                        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
                        $categories = get_the_category($post_id);
                        $category_name = !empty($categories) ? esc_html($categories[0]->name) : 'সাধারণ';
                        $post_date_english = get_the_date('j F, Y', $post_id);
                        $post_date = cricket_convert_to_bengali_date($post_date_english);
                        $excerpt = get_the_excerpt($post_id);
                        if (empty($excerpt)) {
                            $excerpt = wp_trim_words(get_the_content(), 20, '...');
                        }
                    ?>
                    <div class="card">
                        <div class="card-header">
                            <?php if ($thumbnail_url) : ?>
                                <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="card-image">
                            <?php else : ?>
                                <div class="card-image card-image-placeholder" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; min-height: 200px; color: white; font-size: 18px; font-weight: 600;">
                                    ছবি নেই
                                </div>
                            <?php endif; ?>
                            <span class="category-badge"><?php echo $category_name; ?></span>
                        </div>
                        <div class="card-body">
                            <div class="date">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <?php echo esc_html($post_date); ?>
                            </div>
                            <h3 class="card-title"><?php echo get_the_title(); ?></h3>
                            <p class="card-excerpt"><?php echo esc_html($excerpt); ?></p>
                            <a href="<?php echo esc_url(get_permalink()); ?>" class="read-more">
                                বিস্তারিত পড়ুন
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    } else {
        echo '<p>' . __('No posts found.', 'cricket-matches') . '</p>';
    }

    return ob_get_clean();
}
add_shortcode('cricket_posts', 'cricket_posts_shortcode');

/**
 * Shortcode for displaying blog listing with pagination
 * Usage: [blog_listing posts_per_page="6" category=""]
 */
function blog_listing_shortcode($atts) {
    $atts = shortcode_atts(array(
        'posts_per_page' => 6,
        'category' => '',
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts, 'blog_listing');

    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => intval($atts['posts_per_page']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
        'paged' => $paged
    );

    if (!empty($atts['category'])) {
        $args['category_name'] = $atts['category'];
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        ?>
        <div class="blog-detail-list blog-list">
            <div class="container">
                <?php while ($query->have_posts()) : $query->the_post();
                    $post_id = get_the_ID();
                    $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
                    $categories = get_the_category($post_id);
                    $category_name = !empty($categories) ? esc_html($categories[0]->name) : 'সাধারণ';
                    $post_date_english = get_the_date('j F, Y', $post_id);
                    $post_date = cricket_convert_to_bengali_date($post_date_english);
                    $excerpt = get_the_excerpt($post_id);
                    if (empty($excerpt)) {
                        $excerpt = wp_trim_words(get_the_content(), 20, '...');
                    }
                    // Calculate reading time (assuming 200 words per minute)
                    $content = get_post_field('post_content', $post_id);
                    $word_count = str_word_count(strip_tags($content));
                    $reading_time = ceil($word_count / 200);
                    $reading_time_bangla = cricket_convert_to_bengali_number($reading_time);
                ?>
<div class="card">
            <div class="card-image">
               <a href="#"> <img src="/notout/wp-content/uploads/2025/12/detail-img-1.png" alt="Football"></a>
                <span class="category-badge">ফুটবল</span>
            </div>
            <div class="card-content">
                <h2 class="card-title">প্রিমিয়ার লিগ: ম্যানচেস্টার ভার্সি পূর্বরূ
                 </h2>
				 <div class="des-tex"><p>আগামীকালের বড় ম্যাচের জন্য প্রস্তুত হন। বিশেষজ্ঞদের বিশ্লেষণ এবং বেটিং টিপস সহ সম্পূর্ণ প্রিভিউ.</p></div>
                <div class="card-meta">
                    <div class="meta-item">
                        <svg class="meta-icon icon-calendar" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <span>৬ ডিসেম্বর, ২০২৪</span>
                    </div>
                    <div class="meta-item">
                        <svg class="meta-icon icon-clock" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10"/>
                            <polyline points="12 6 12 12 16 14"/>
                        </svg>
                        <span>৪ মিনিট</span>
                    </div>
                </div>
            </div>
        </div>


                <?php endwhile; ?>
            </div>
        </div>

        <?php
        // Pagination
        if ($query->max_num_pages > 1) {
            $total_pages = $query->max_num_pages;
            $current_page = max(1, $paged);

            echo '<div class="blog-pagination">';

            echo paginate_links(array(
                'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                'format' => '?paged=%#%',
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text' => '&laquo; পূর্ববর্তী',
                'next_text' => 'পরবর্তী &raquo;',
                'type' => 'list',
                'end_size' => 3,
                'mid_size' => 2
            ));

            echo '</div>';
        }
        ?>
        <?php
        wp_reset_postdata();
    } else {
        echo '<p>' . __('কোন পোস্ট পাওয়া যায়নি।', 'cricket-matches') . '</p>';
    }

    return ob_get_clean();
}
add_shortcode('blog_listing', 'blog_listing_shortcode');

/**
 * Cricket Matches Paginated Shortcode
 * Usage: [cricket_matches_paged limit="6" columns="3"]
 */
function cricket_matches_paged_shortcode($atts) {
    $atts = shortcode_atts(array(
        'limit' => 6,
        'columns' => 3,
        'orderby' => 'date',
        'order' => 'DESC',
    ), $atts, 'cricket_matches_paged');

    // Get current page
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    $args = array(
        'post_type' => 'cricket_match',
        'posts_per_page' => intval($atts['limit']),
        'orderby' => $atts['orderby'],
        'order' => $atts['order'],
        'post_status' => 'publish',
        'paged' => $paged
    );

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) {
        $columns = intval($atts['columns']);
        $columns = max(1, min(4, $columns)); // Limit between 1-4
        ?>
        <style>
            .cricket-matches-paged-<?php echo $columns; ?> .matches-grid {
                grid-template-columns: repeat(<?php echo $columns; ?>, 1fr);
            }
            @media (max-width: 768px) {
                .cricket-matches-paged-<?php echo $columns; ?> .matches-grid {
                    grid-template-columns: 1fr;
                }
            }
            @media (min-width: 769px) and (max-width: 1024px) {
                .cricket-matches-paged-<?php echo $columns; ?> .matches-grid {
                    grid-template-columns: repeat(<?php echo min(2, $columns); ?>, 1fr);
                }
            }
        </style>
        <div class="cricket-container-match cricket-matches-paged-<?php echo $columns; ?>">
            <div class="container">
                <div class="matches-grid">
                    <?php while ($query->have_posts()) : $query->the_post();
                        $post_id = get_the_ID();
                        $series_badge = get_post_meta($post_id, '_cricket_series_badge', true);
                        $is_popular = get_post_meta($post_id, '_cricket_is_popular', true);
                        $team_name_1 = get_post_meta($post_id, '_cricket_team_name_1', true);
                        $team_name_2 = get_post_meta($post_id, '_cricket_team_name_2', true);
                        $match_time = get_post_meta($post_id, '_cricket_match_time', true);
                        $win_probability_team = get_post_meta($post_id, '_cricket_win_probability_team', true);
                        $win_probability_percentage = get_post_meta($post_id, '_cricket_win_probability_percentage', true);
                        $prediction_text = get_post_meta($post_id, '_cricket_prediction_text', true);
                        $total_bets = get_post_meta($post_id, '_cricket_total_bets', true);
                        $odds = get_post_meta($post_id, '_cricket_odds', true);
                        $bet_button_text = get_post_meta($post_id, '_cricket_bet_button_text', true) ?: 'এখনই বেট করুন →';
                        $bet_button_url = get_post_meta($post_id, '_cricket_bet_button_url', true);
                        $thumbnail_url = get_the_post_thumbnail_url($post_id, 'full');
                    ?>
                    <div class="match-card">
                        <div class="image-container">
                            <div class="image-box">
                                <?php if ($thumbnail_url) : ?>
                                    <img decoding="async" src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="blog-image">
                                <?php endif; ?>
                            </div>

                            <div class="image-overlay">
                                <?php if ($series_badge) : ?>
                                    <span class="badge badge-series"><?php echo esc_html($series_badge); ?></span>
                                <?php endif; ?>
                                <?php if ($is_popular) : ?>
                                    <span class="badge badge-popular">🔥 জনপ্রিয় পছন্দ</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="match-content">
                            <div class="match-teams">
                                <?php if ($team_name_1) : ?>
                                    <span class="team-name"><?php echo esc_html($team_name_1); ?></span>
                                <?php endif; ?>
                                <?php if ($team_name_2) : ?>
                                    <span class="vs-text"> বনাম </span>
                                    <span class="team-name"><?php echo esc_html($team_name_2); ?></span>
                                <?php else : ?>
                                    <span class="vs-text"> বনাম</span>
                                <?php endif; ?>
                            </div>

                            <?php if ($match_time) : ?>
                                <div class="match-time">
                                    🕐 <?php echo esc_html($match_time); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($win_probability_team || $win_probability_percentage || $prediction_text) : ?>
                                <div class="prediction-box">
                                    <?php if ($win_probability_team && $win_probability_percentage) : ?>
                                        <div class="prediction-header">
                                            📈 <?php echo esc_html($win_probability_team); ?> জয়ের সম্ভাবনা <?php echo cricket_convert_to_bengali_number(esc_html($win_probability_percentage)); ?>%
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($prediction_text) : ?>
                                        <div class="prediction-text">
                                            <?php echo esc_html($prediction_text); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($total_bets || $odds) : ?>
                                <div class="match-stats">
                                    <?php if ($total_bets) : ?>
                                        <span class="stat">👥 <?php echo cricket_convert_to_bengali_number(esc_html($total_bets)); ?> বেট</span>
                                    <?php endif; ?>
                                    <?php if ($odds) : ?>
                                        <span class="odds">অডস: <?php echo cricket_convert_to_bengali_number(esc_html($odds)); ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($bet_button_url) : ?>
                                <a href="<?php echo esc_url($bet_button_url); ?>" class="bet-button">
                                    <?php echo esc_html($bet_button_text); ?>
                                </a>
                            <?php else : ?>
                                <button class="bet-button">
                                    <?php echo esc_html($bet_button_text); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <?php
                // Pagination
                if ($query->max_num_pages > 1) {
                    $pagination = paginate_links(array(
                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format' => '?paged=%#%',
                        'current' => max(1, $paged),
                        'total' => $query->max_num_pages,
                        'prev_text' => __('« পূর্ববর্তী', 'cricket-matches'),
                        'next_text' => __('পরবর্তী »', 'cricket-matches'),
                        'type' => 'array',
                        'mid_size' => 2,
                    ));

                    if ($pagination) {
                        echo '<div class="blog-pagination"><ul class="page-numbers">';
                        foreach ($pagination as $page) {
                            // Convert numbers to Bengali
                            $page = cricket_convert_to_bengali_number($page);
                            echo '<li>' . $page . '</li>';
                        }
                        echo '</ul></div>';
                    }
                }
                ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
    } else {
        echo '<p>' . __('কোন ম্যাচ পাওয়া যায়নি।', 'cricket-matches') . '</p>';
    }

    return ob_get_clean();
}
add_shortcode('cricket_matches_paged', 'cricket_matches_paged_shortcode');

/**
 * Handle Random Posts Generation
 */
function cricket_matches_handle_random_posts() {
    if (isset($_GET['cricket_generate_random']) && current_user_can('manage_options')) {
        check_admin_referer('cricket_generate_random');
        $count = cricket_matches_add_random_posts();
        add_settings_error(
            'cricket_matches_messages',
            'cricket_matches_message',
            sprintf(__('Successfully created %d random cricket match posts!', 'cricket-matches'), $count),
            'success'
        );
        set_transient('cricket_matches_admin_notice', true, 5);
    }
}
add_action('admin_init', 'cricket_matches_handle_random_posts');

/**
 * Display Admin Notice
 */
function cricket_matches_admin_notice() {
    if (get_transient('cricket_matches_admin_notice')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Successfully created 20 random cricket match posts!', 'cricket-matches'); ?></p>
        </div>
        <?php
        delete_transient('cricket_matches_admin_notice');
    }
}
add_action('admin_notices', 'cricket_matches_admin_notice');

/**
 * Add Admin Menu Page
 */
function cricket_matches_add_admin_menu() {
    add_submenu_page(
        'edit.php?post_type=cricket_match',
        __('How to Use', 'cricket-matches'),
        __('How to Use', 'cricket-matches'),
        'manage_options',
        'cricket-matches-help',
        'cricket_matches_help_page'
    );
}
add_action('admin_menu', 'cricket_matches_add_admin_menu');

/**
 * Add Admin Menu Page Under Posts
 */
function cricket_posts_add_admin_menu() {
    add_submenu_page(
        'edit.php',
        __('Cricket Posts Guide', 'cricket-matches'),
        __('Cricket Posts Guide', 'cricket-matches'),
        'manage_options',
        'cricket-posts-help',
        'cricket_posts_help_page'
    );
}
add_action('admin_menu', 'cricket_posts_add_admin_menu');

/**
 * Handle Random Default Posts Generation
 */
function cricket_posts_handle_random_generation() {
    if (isset($_GET['cricket_generate_random_posts']) &&
        isset($_GET['_wpnonce']) &&
        wp_verify_nonce($_GET['_wpnonce'], 'cricket_generate_random_posts')) {
        cricket_posts_add_random_posts();
        set_transient('cricket_posts_admin_notice', true, 45);
        wp_redirect(admin_url('edit.php?page=cricket-posts-help'));
        exit;
    }
}
add_action('admin_init', 'cricket_posts_handle_random_generation');

/**
 * Admin Notice for Random Posts
 */
function cricket_posts_admin_notice() {
    if (get_transient('cricket_posts_admin_notice')) {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Successfully created 20 random cricket posts!', 'cricket-matches'); ?></p>
        </div>
        <?php
        delete_transient('cricket_posts_admin_notice');
    }
}
add_action('admin_notices', 'cricket_posts_admin_notice');

/**
 * Cricket Posts Help Page Content
 */
function cricket_posts_help_page() {
    ?>
    <div class="wrap cricket-matches-help-page">
        <h1><?php _e('Cricket Posts - How to Use', 'cricket-matches'); ?></h1>

        <!-- Generate Random Posts Button -->
        <div class="cricket-random-posts-section">
            <h2><?php _e('Quick Actions', 'cricket-matches'); ?></h2>
            <p><?php _e('Need sample data? Click below to generate 20 random cricket posts using WordPress default posts.', 'cricket-matches'); ?></p>
            <a href="<?php echo wp_nonce_url(admin_url('edit.php?page=cricket-posts-help&cricket_generate_random_posts=1'), 'cricket_generate_random_posts'); ?>"
               class="button button-primary button-large">
                <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span>
                <?php _e('Generate 20 Random Cricket Posts', 'cricket-matches'); ?>
            </a>
        </div>

        <div class="cricket-help-container">
            <!-- Quick Start -->
            <div class="cricket-help-section">
                <h2>🚀 <?php _e('Quick Start', 'cricket-matches'); ?></h2>
                <ol class="cricket-steps">
                    <li><?php _e('Add cricket posts using "Add New" under Posts menu', 'cricket-matches'); ?></li>
                    <li><?php _e('Fill in the "Cricket Match Data" meta box with match details', 'cricket-matches'); ?></li>
                    <li><?php _e('Copy a shortcode from below', 'cricket-matches'); ?></li>
                    <li><?php _e('Paste it into any page or post', 'cricket-matches'); ?></li>
                    <li><?php _e('Publish and view your cricket posts!', 'cricket-matches'); ?></li>
                </ol>
            </div>

            <!-- Shortcode Examples -->
            <div class="cricket-help-section">
                <h2>📝 <?php _e('Shortcode Examples for Cricket Posts', 'cricket-matches'); ?></h2>

                <div class="shortcode-example">
                    <h3><?php _e('Display All Cricket Posts', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows all published posts that have cricket match data.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 6 Cricket Posts (Homepage)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts limit="6"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts limit=&quot;6&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows only 6 most recent cricket posts.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 3 Cricket Posts (Sidebar)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts limit="3"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts limit=&quot;3&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Perfect for sidebar widgets or small sections.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display Posts from Specific Category', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts category="cricket" limit="6"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts category=&quot;cricket&quot; limit=&quot;6&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Filter by category slug - useful for organizing different types of cricket content.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Custom Sorting by Title', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts limit="10" orderby="title" order="ASC"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts limit=&quot;10&quot; orderby=&quot;title&quot; order=&quot;ASC&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows 10 posts sorted alphabetically by title.', 'cricket-matches'); ?></p>
                </div>
            </div>

            <!-- Parameters Table -->
            <div class="cricket-help-section">
                <h2>⚙️ <?php _e('Shortcode Parameters', 'cricket-matches'); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Parameter', 'cricket-matches'); ?></th>
                            <th><?php _e('Description', 'cricket-matches'); ?></th>
                            <th><?php _e('Default', 'cricket-matches'); ?></th>
                            <th><?php _e('Options', 'cricket-matches'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>limit</code></td>
                            <td><?php _e('Number of posts to display', 'cricket-matches'); ?></td>
                            <td>-1 (all)</td>
                            <td><?php _e('Any number (e.g., 3, 6, 12)', 'cricket-matches'); ?></td>
                        </tr>
                        <tr>
                            <td><code>orderby</code></td>
                            <td><?php _e('Sort posts by field', 'cricket-matches'); ?></td>
                            <td>date</td>
                            <td>date, title, ID, modified</td>
                        </tr>
                        <tr>
                            <td><code>order</code></td>
                            <td><?php _e('Sort direction', 'cricket-matches'); ?></td>
                            <td>DESC</td>
                            <td>ASC, DESC</td>
                        </tr>
                        <tr>
                            <td><code>category</code></td>
                            <td><?php _e('Filter by category slug', 'cricket-matches'); ?></td>
                            <td><?php _e('none', 'cricket-matches'); ?></td>
                            <td><?php _e('Any category slug', 'cricket-matches'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Usage Instructions -->
            <div class="cricket-help-section">
                <h2>📍 <?php _e('Where to Use', 'cricket-matches'); ?></h2>

                <h3><?php _e('In WordPress Editor (Gutenberg)', 'cricket-matches'); ?></h3>
                <ol class="cricket-steps">
                    <li><?php _e('Add a "Shortcode" block', 'cricket-matches'); ?></li>
                    <li><?php _e('Paste your shortcode (e.g., [cricket_posts limit="6"])', 'cricket-matches'); ?></li>
                    <li><?php _e('Preview or Publish', 'cricket-matches'); ?></li>
                </ol>

                <h3><?php _e('In Classic Editor', 'cricket-matches'); ?></h3>
                <ol class="cricket-steps">
                    <li><?php _e('Switch to "Text" mode', 'cricket-matches'); ?></li>
                    <li><?php _e('Paste your shortcode', 'cricket-matches'); ?></li>
                    <li><?php _e('Save or Publish', 'cricket-matches'); ?></li>
                </ol>

                <h3><?php _e('In Theme Template Files', 'cricket-matches'); ?></h3>
                <div class="code-block">
                    <code>&lt;?php echo do_shortcode('[cricket_posts limit="6"]'); ?&gt;</code>
                </div>
            </div>

            <!-- Features -->
            <div class="cricket-help-section">
                <h2>✨ <?php _e('Features', 'cricket-matches'); ?></h2>
                <ul class="cricket-feature-list">
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('Uses WordPress default posts', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('Category filtering support', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('12 cricket-specific meta fields', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('Beautiful responsive grid layout', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('Automatic featured image support', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('SEO-friendly with standard WordPress posts', 'cricket-matches'); ?></li>
                    <li><span class="dashicons dashicons-yes"></span> <?php _e('Works with all post categories and tags', 'cricket-matches'); ?></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="cricket-help-section">
                <h2>📞 <?php _e('Need Help?', 'cricket-matches'); ?></h2>
                <p><?php _e('Remember: This shortcode uses WordPress default posts with cricket match meta data.', 'cricket-matches'); ?></p>
                <ul class="cricket-steps">
                    <li><?php _e('Make sure posts have the "Cricket Match Data" meta box filled in', 'cricket-matches'); ?></li>
                    <li><?php _e('Posts must be published (not draft) to appear', 'cricket-matches'); ?></li>
                    <li><?php _e('Use category parameter to organize different cricket content', 'cricket-matches'); ?></li>
                    <li><?php _e('Featured images are automatically used for match images', 'cricket-matches'); ?></li>
                </ul>
            </div>
        </div>

        <style>
            .cricket-matches-help-page {
                max-width: 1200px;
            }
            .cricket-random-posts-section {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-left: 4px solid #2271b1;
                padding: 20px;
                margin: 20px 0;
                box-shadow: 0 1px 1px rgba(0,0,0,.04);
            }
            .cricket-random-posts-section h2 {
                margin-top: 0;
                color: #2271b1;
            }
            .cricket-help-container {
                background: #fff;
                padding: 30px;
                margin-top: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,.13);
            }
            .cricket-help-section {
                margin-bottom: 40px;
                padding-bottom: 30px;
                border-bottom: 1px solid #e5e5e5;
            }
            .cricket-help-section:last-child {
                border-bottom: none;
            }
            .cricket-help-section h2 {
                color: #1d2327;
                font-size: 20px;
                margin-bottom: 15px;
            }
            .shortcode-example {
                background: #f6f7f7;
                padding: 20px;
                margin: 15px 0;
                border-radius: 4px;
                border-left: 4px solid #2271b1;
            }
            .shortcode-example h3 {
                margin-top: 0;
                color: #2271b1;
                font-size: 16px;
            }
            .code-block {
                background: #1e1e1e;
                color: #d4d4d4;
                padding: 15px;
                border-radius: 4px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                font-family: 'Courier New', monospace;
                margin: 10px 0;
            }
            .code-block code {
                color: #d4d4d4;
                font-size: 14px;
            }
            .copy-btn {
                background: #2271b1;
                color: #fff;
                border: none;
                padding: 6px 15px;
                border-radius: 3px;
                cursor: pointer;
                font-size: 12px;
                transition: background 0.3s;
            }
            .copy-btn:hover {
                background: #135e96;
            }
            .description {
                color: #646970;
                font-style: italic;
                margin: 5px 0 0 0;
            }
            .cricket-steps {
                background: #f0f6fc;
                padding: 20px 20px 20px 40px;
                border-radius: 4px;
                line-height: 1.8;
            }
            .cricket-feature-list {
                list-style: none;
                padding: 0;
            }
            .cricket-feature-list li {
                padding: 8px 0;
                display: flex;
                align-items: center;
            }
            .cricket-feature-list .dashicons {
                color: #00a32a;
                margin-right: 10px;
            }
        </style>

        <script>
            function copyToClipboard(text) {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);

                // Show success message
                const btn = event.target;
                const originalText = btn.textContent;
                btn.textContent = '<?php _e('Copied!', 'cricket-matches'); ?>';
                btn.style.background = '#00a32a';
                setTimeout(() => {
                    btn.textContent = originalText;
                    btn.style.background = '#2271b1';
                }, 2000);
            }
        </script>
    </div>
    <?php
}

/**
 * Help Page Content
 */
function cricket_matches_help_page() {
    ?>
    <div class="wrap cricket-matches-help-page">
        <h1><?php _e('Cricket Matches - How to Use', 'cricket-matches'); ?></h1>

        <!-- Generate Random Posts Button -->
        <div class="cricket-random-posts-section">
            <h2><?php _e('Quick Actions', 'cricket-matches'); ?></h2>
            <p><?php _e('Need more sample data? Click below to generate 20 additional random cricket match posts.', 'cricket-matches'); ?></p>
            <a href="<?php echo wp_nonce_url(admin_url('edit.php?post_type=cricket_match&page=cricket-matches-help&cricket_generate_random=1'), 'cricket_generate_random'); ?>"
               class="button button-primary button-large">
                <span class="dashicons dashicons-plus-alt" style="margin-top: 3px;"></span>
                <?php _e('Generate 20 Random Match Posts', 'cricket-matches'); ?>
            </a>
        </div>

        <div class="cricket-help-container">
            <!-- Quick Start -->
            <div class="cricket-help-section">
                <h2>🚀 <?php _e('Quick Start', 'cricket-matches'); ?></h2>
                <ol class="cricket-steps">
                    <li><?php _e('Add cricket matches using "Add New" button', 'cricket-matches'); ?></li>
                    <li><?php _e('Copy a shortcode from below', 'cricket-matches'); ?></li>
                    <li><?php _e('Paste it into any page or post', 'cricket-matches'); ?></li>
                    <li><?php _e('Publish and view your matches!', 'cricket-matches'); ?></li>
                </ol>
            </div>

            <!-- Shortcode Examples -->
            <div class="cricket-help-section">
                <h2>📝 <?php _e('Shortcode Examples', 'cricket-matches'); ?></h2>

                <h3 style="color: #2271b1; margin-top: 20px;"><?php _e('For Cricket Match Custom Post Type:', 'cricket-matches'); ?></h3>

                <div class="shortcode-example">
                    <h3><?php _e('Display All Matches', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows all published cricket matches in descending order by date.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 6 Matches (Perfect for Homepage)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches limit="6"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches limit=&quot;6&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows only 6 most recent matches - ideal for your homepage.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 3 Matches (Sidebar Widget)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches limit="3"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches limit=&quot;3&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Perfect for sidebars or compact sections.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Sort by Title (Alphabetically)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches orderby="title" order="ASC"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches orderby=&quot;title&quot; order=&quot;ASC&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Display matches sorted alphabetically by title.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Custom Combination', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches limit="10" orderby="date" order="DESC"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches limit=&quot;10&quot; orderby=&quot;date&quot; order=&quot;DESC&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows 10 most recent matches.', 'cricket-matches'); ?></p>
                </div>

                <h3 style="color: #9333ea; margin-top: 30px; border-top: 2px solid #9333ea; padding-top: 20px;"><?php _e('🔥 Paginated Version with Column Control:', 'cricket-matches'); ?></h3>
                <p style="background: #f3e8ff; padding: 15px; border-left: 4px solid #9333ea; margin-bottom: 20px;">
                    <strong><?php _e('New!', 'cricket-matches'); ?></strong> <?php _e('Display cricket matches with pagination and control how many columns to show. Perfect for archive pages!', 'cricket-matches'); ?>
                </p>

                <div class="shortcode-example">
                    <h3><?php _e('Display 6 Matches in 3 Columns with Pagination', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches_paged limit="6" columns="3"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches_paged limit=&quot;6&quot; columns=&quot;3&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Perfect for archive pages - shows 6 matches per page in 3 columns with Bengali pagination.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 9 Matches in 3 Columns', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches_paged limit="9" columns="3"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches_paged limit=&quot;9&quot; columns=&quot;3&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows 9 matches per page in 3 columns layout.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 8 Matches in 4 Columns', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches_paged limit="8" columns="4"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches_paged limit=&quot;8&quot; columns=&quot;4&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Maximum column layout - shows 8 matches per page in 4 columns.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 4 Matches in 2 Columns', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches_paged limit="4" columns="2"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches_paged limit=&quot;4&quot; columns=&quot;2&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Compact layout - 4 matches per page in 2 columns.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 6 Matches in 1 Column (List View)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_matches_paged limit="6" columns="1"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_matches_paged limit=&quot;6&quot; columns=&quot;1&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Full-width list view - 6 matches per page in single column.', 'cricket-matches'); ?></p>
                </div>

                <h3 style="color: #00a32a; margin-top: 30px; border-top: 2px solid #00a32a; padding-top: 20px;"><?php _e('For Default WordPress Posts:', 'cricket-matches'); ?></h3>
                <p style="background: #d5f4e6; padding: 15px; border-left: 4px solid #00a32a; margin-bottom: 20px;">
                    <strong><?php _e('New!', 'cricket-matches'); ?></strong> <?php _e('Now you can display cricket match data from regular WordPress posts. Just add the "Cricket Match Data" meta box fields when creating a post.', 'cricket-matches'); ?>
                </p>

                <div class="shortcode-example">
                    <h3><?php _e('Display All Cricket Posts', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows all WordPress posts that have cricket match data added.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Display 6 Cricket Posts', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts limit="6"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts limit=&quot;6&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows 6 most recent cricket posts.', 'cricket-matches'); ?></p>
                </div>

                <div class="shortcode-example">
                    <h3><?php _e('Filter by Category', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>[cricket_posts limit="6" category="cricket"]</code>
                        <button class="copy-btn" onclick="copyToClipboard('[cricket_posts limit=&quot;6&quot; category=&quot;cricket&quot;]')"><?php _e('Copy', 'cricket-matches'); ?></button>
                    </div>
                    <p class="description"><?php _e('Shows 6 cricket posts from "cricket" category.', 'cricket-matches'); ?></p>
                </div>
            </div>

            <!-- Shortcode Parameters -->
            <div class="cricket-help-section">
                <h2>⚙️ <?php _e('Shortcode Parameters', 'cricket-matches'); ?></h2>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Parameter', 'cricket-matches'); ?></th>
                            <th><?php _e('Description', 'cricket-matches'); ?></th>
                            <th><?php _e('Default', 'cricket-matches'); ?></th>
                            <th><?php _e('Options', 'cricket-matches'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>limit</code></td>
                            <td><?php _e('Number of matches to display', 'cricket-matches'); ?></td>
                            <td><code>-1</code> (<?php _e('all', 'cricket-matches'); ?>)</td>
                            <td><?php _e('Any number: 3, 6, 10, etc.', 'cricket-matches'); ?></td>
                        </tr>
                        <tr>
                            <td><code>orderby</code></td>
                            <td><?php _e('Sort matches by', 'cricket-matches'); ?></td>
                            <td><code>date</code></td>
                            <td><code>date</code>, <code>title</code>, <code>ID</code>, <code>modified</code></td>
                        </tr>
                        <tr>
                            <td><code>order</code></td>
                            <td><?php _e('Sort direction', 'cricket-matches'); ?></td>
                            <td><code>DESC</code></td>
                            <td><code>ASC</code> (<?php _e('ascending', 'cricket-matches'); ?>), <code>DESC</code> (<?php _e('descending', 'cricket-matches'); ?>)</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Where to Use -->
            <div class="cricket-help-section">
                <h2>📍 <?php _e('Where to Use the Shortcode', 'cricket-matches'); ?></h2>

                <div class="usage-location">
                    <h3>✓ <?php _e('In Pages/Posts (Gutenberg Editor)', 'cricket-matches'); ?></h3>
                    <ol>
                        <li><?php _e('Click "+ Add block"', 'cricket-matches'); ?></li>
                        <li><?php _e('Search for "Shortcode"', 'cricket-matches'); ?></li>
                        <li><?php _e('Add the shortcode block', 'cricket-matches'); ?></li>
                        <li><?php _e('Paste: [cricket_matches limit="6"]', 'cricket-matches'); ?></li>
                        <li><?php _e('Publish or Update', 'cricket-matches'); ?></li>
                    </ol>
                </div>

                <div class="usage-location">
                    <h3>✓ <?php _e('In Classic Editor', 'cricket-matches'); ?></h3>
                    <ol>
                        <li><?php _e('Switch to "Text" tab', 'cricket-matches'); ?></li>
                        <li><?php _e('Paste the shortcode where you want matches to appear', 'cricket-matches'); ?></li>
                        <li><?php _e('Save the page/post', 'cricket-matches'); ?></li>
                    </ol>
                </div>

                <div class="usage-location">
                    <h3>✓ <?php _e('In Page Builders (Elementor, etc.)', 'cricket-matches'); ?></h3>
                    <ol>
                        <li><?php _e('Add a "Shortcode" widget/element', 'cricket-matches'); ?></li>
                        <li><?php _e('Paste the shortcode', 'cricket-matches'); ?></li>
                        <li><?php _e('Save and preview', 'cricket-matches'); ?></li>
                    </ol>
                </div>

                <div class="usage-location">
                    <h3>✓ <?php _e('In Theme Templates (PHP)', 'cricket-matches'); ?></h3>
                    <div class="code-block">
                        <code>&lt;?php echo do_shortcode('[cricket_matches limit="6"]'); ?&gt;</code>
                    </div>
                </div>
            </div>

            <!-- Sample Data Info -->
            <div class="cricket-help-section">
                <h2>📦 <?php _e('Sample Data', 'cricket-matches'); ?></h2>
                <p><?php _e('The plugin includes 6 pre-configured sample matches based on your HTML template:', 'cricket-matches'); ?></p>
                <ul class="sample-matches-list">
                    <li>✓ <?php _e('দুমাই জুলফিকার ক্রিকেট বনাম (আর্মিপ্রিমিয়ার ২০২৫) - Popular', 'cricket-matches'); ?></li>
                    <li>✓ <?php _e('মিডনিশনার্স পিয়ান্সার বনাম (আর্মিপ্রিমিয়ার ২০২৫)', 'cricket-matches'); ?></li>
                    <li>✓ <?php _e('অস্ট্রেলিয়া বনাম ইংল্যান্ড (টেস্ট সিরিজ) - Popular', 'cricket-matches'); ?></li>
                    <li>✓ <?php _e('পাকিস্তান বনাম নিউজিল্যান্ড (ODI সিরিজ)', 'cricket-matches'); ?></li>
                    <li>✓ <?php _e('রাজস্থান রয়্যালস বনাম পাঞ্জাব কিংস (আর্মিপ্রিমিয়ার ২০২৫) - Popular', 'cricket-matches'); ?></li>
                    <li>✓ <?php _e('মুম্বাই ইন্ডিয়ানস জেনেসোয়াকশি বনাম (আর্মিপ্রিমিয়ার ২০২৫)', 'cricket-matches'); ?></li>
                </ul>
                <p class="note">💡 <strong><?php _e('Note:', 'cricket-matches'); ?></strong> <?php _e('Add featured images to sample matches for complete display.', 'cricket-matches'); ?></p>
            </div>

            <!-- Features -->
            <div class="cricket-help-section">
                <h2>✨ <?php _e('Features', 'cricket-matches'); ?></h2>
                <div class="features-grid">
                    <div class="feature-item">
                        <span class="dashicons dashicons-awards"></span>
                        <h4><?php _e('Custom Post Type', 'cricket-matches'); ?></h4>
                        <p><?php _e('Dedicated Cricket Matches management', 'cricket-matches'); ?></p>
                    </div>
                    <div class="feature-item">
                        <span class="dashicons dashicons-smartphone"></span>
                        <h4><?php _e('Fully Responsive', 'cricket-matches'); ?></h4>
                        <p><?php _e('3 cols desktop → 2 tablet → 1 mobile', 'cricket-matches'); ?></p>
                    </div>
                    <div class="feature-item">
                        <span class="dashicons dashicons-admin-appearance"></span>
                        <h4><?php _e('Beautiful Design', 'cricket-matches'); ?></h4>
                        <p><?php _e('Modern cards with hover effects', 'cricket-matches'); ?></p>
                    </div>
                    <div class="feature-item">
                        <span class="dashicons dashicons-editor-code"></span>
                        <h4><?php _e('Easy Shortcode', 'cricket-matches'); ?></h4>
                        <p><?php _e('One shortcode, unlimited usage', 'cricket-matches'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Support -->
            <div class="cricket-help-section cricket-support">
                <h2>📞 <?php _e('Need Help?', 'cricket-matches'); ?></h2>
                <p><?php _e('Check the plugin documentation files:', 'cricket-matches'); ?></p>
                <ul>
                    <li><strong>README.md</strong> - <?php _e('General documentation', 'cricket-matches'); ?></li>
                    <li><strong>ACTIVATION-GUIDE.md</strong> - <?php _e('Complete activation guide', 'cricket-matches'); ?></li>
                    <li><strong>SHORTCODE-GUIDE.md</strong> - <?php _e('Detailed shortcode reference', 'cricket-matches'); ?></li>
                </ul>
                <p><strong><?php _e('Plugin Version:', 'cricket-matches'); ?></strong> <?php echo CRICKET_MATCHES_VERSION; ?></p>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);

        // Show feedback
        event.target.textContent = '<?php _e('Copied!', 'cricket-matches'); ?>';
        setTimeout(() => {
            event.target.textContent = '<?php _e('Copy', 'cricket-matches'); ?>';
        }, 2000);
    }
    </script>

    <style>
        .cricket-matches-help-page {
            margin: 20px 20px 20px 0;
        }

        .cricket-random-posts-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0 30px;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .cricket-random-posts-section h2 {
            color: #fff;
            margin-top: 0;
            font-size: 22px;
        }

        .cricket-random-posts-section p {
            font-size: 16px;
            margin-bottom: 20px;
            opacity: 0.95;
        }

        .cricket-random-posts-section .button-large {
            font-size: 16px;
            padding: 12px 24px;
            height: auto;
            line-height: 1.5;
            background: #fff;
            color: #667eea;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .cricket-random-posts-section .button-large:hover {
            background: #f0f0f0;
            color: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .cricket-help-container {
            max-width: 1200px;
        }

        .cricket-help-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
            margin: 20px 0;
            padding: 30px;
        }

        .cricket-help-section h2 {
            margin-top: 0;
            color: #1d2327;
            font-size: 20px;
            border-bottom: 2px solid #2271b1;
            padding-bottom: 10px;
        }

        .cricket-steps {
            font-size: 16px;
            line-height: 1.8;
            color: #1d2327;
        }

        .shortcode-example {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e5e5;
        }

        .shortcode-example:last-child {
            border-bottom: none;
        }

        .shortcode-example h3 {
            margin: 15px 0 10px;
            color: #1d2327;
            font-size: 16px;
        }

        .code-block {
            background: #f6f7f7;
            border: 1px solid #c3c4c7;
            border-left: 4px solid #2271b1;
            padding: 15px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .code-block code {
            font-size: 14px;
            color: #1d2327;
            background: transparent;
            padding: 0;
        }

        .copy-btn {
            background: #2271b1;
            color: #fff;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            border-radius: 3px;
            font-size: 13px;
        }

        .copy-btn:hover {
            background: #135e96;
        }

        .description {
            color: #646970;
            font-style: italic;
            margin: 5px 0 0;
        }

        .usage-location {
            margin: 20px 0;
            padding: 20px;
            background: #f9f9f9;
            border-left: 4px solid #00a32a;
        }

        .usage-location h3 {
            margin-top: 0;
            color: #00a32a;
        }

        .usage-location ol {
            margin-left: 20px;
        }

        .sample-matches-list {
            list-style: none;
            padding-left: 0;
        }

        .sample-matches-list li {
            padding: 8px 0;
            font-size: 15px;
        }

        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin-top: 20px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .feature-item {
            text-align: center;
            padding: 20px;
            background: #f6f7f7;
            border-radius: 5px;
        }

        .feature-item .dashicons {
            font-size: 40px;
            width: 40px;
            height: 40px;
            color: #2271b1;
        }

        .feature-item h4 {
            margin: 10px 0 5px;
            color: #1d2327;
        }

        .feature-item p {
            color: #646970;
            font-size: 14px;
            margin: 0;
        }

        .cricket-support {
            background: #e7f5fe;
            border-left: 4px solid #2271b1;
        }

        .cricket-support ul {
            margin-left: 20px;
        }
    </style>
    <?php
}

/**
 * Insert Sample Match Data
 * This function will run once on plugin activation to create sample matches
 */
function cricket_matches_insert_sample_data() {
    // Check if sample data already exists
    if (get_option('cricket_matches_sample_data_inserted')) {
        return;
    }

    // Sample matches data based on provided HTML
    $sample_matches = array(
        array(
            'title' => 'দুমাই জুলফিকার ক্রিকেট বনাম',
            'series_badge' => 'আর্মিপ্রিমিয়ার ২০২৫',
            'is_popular' => true,
            'team_name_1' => 'দুমাই জুলফিকার ক্রিকেট',
            'team_name_2' => '',
            'match_time' => 'আজ সন্ধ্যা ৭:০০ PM',
            'win_probability_team' => 'দুমাই',
            'win_probability_percentage' => '65',
            'prediction_text' => 'দুমাই দেমে এখনও পরিচালনা। রেকর্ড পশার ফর্ম চমৎকার। টেবল টপেপ লেভেল পূরণ',
            'total_bets' => '৩,৫০০+',
            'odds' => '1.85',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'match-image.png'
        ),
        array(
            'title' => 'মিডনিশনার্স পিয়ান্সার বনাম',
            'series_badge' => 'আর্মিপ্রিমিয়ার ২০২৫',
            'is_popular' => false,
            'team_name_1' => 'মিডনিশনার্স পিয়ান্সার',
            'team_name_2' => '',
            'match_time' => 'আজ রাত ১০:০০ PM',
            'win_probability_team' => 'সনকাতার',
            'win_probability_percentage' => '58',
            'prediction_text' => 'সনকাতারের শিরোনার দলির বিকরণ কাপরকন উইসান মাস্টার নুরার দেনবড',
            'total_bets' => '১,৮০০+',
            'odds' => '2.10',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'match-image.png'
        ),
        array(
            'title' => 'অস্ট্রেলিয়া বনাম ইংল্যান্ড',
            'series_badge' => 'টেস্ট সিরিজ',
            'is_popular' => true,
            'team_name_1' => 'অস্ট্রেলিয়া',
            'team_name_2' => 'ইংল্যান্ড',
            'match_time' => 'আজ সকাল ৯:০০ AM',
            'win_probability_team' => 'অস্ট্রেলিয়া',
            'win_probability_percentage' => '72',
            'prediction_text' => 'অস্ট্রেলিয়ার হোম সুবিধা এবং শক্তিশালী বোলিং আক্রমণ তাদের প্রিয় করে তুলেছে',
            'total_bets' => '৪,২০০+',
            'odds' => '1.65',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'cricket-image.png'
        ),
        array(
            'title' => 'পাকিস্তান বনাম নিউজিল্যান্ড',
            'series_badge' => 'ODI সিরিজ',
            'is_popular' => false,
            'team_name_1' => 'পাকিস্তান',
            'team_name_2' => 'নিউজিল্যান্ড',
            'match_time' => 'আজ বিকাল ৩:০০ PM',
            'win_probability_team' => 'পাকিস্তান',
            'win_probability_percentage' => '55',
            'prediction_text' => 'পাকিস্তান দেমে পূর্বর্তন। নামত আক্রমান ফর্মপাবনর দুবে ডিবল্লার নিংসার',
            'total_bets' => '২,৫০০+',
            'odds' => '1.95',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'cricket-image.png'
        ),
        array(
            'title' => 'রাজস্থান রয়্যালস বনাম পাঞ্জাব কিংস',
            'series_badge' => 'আর্মিপ্রিমিয়ার ২০২৫',
            'is_popular' => true,
            'team_name_1' => 'রাজস্থান রয়্যালস',
            'team_name_2' => 'পাঞ্জাব কিংস',
            'match_time' => 'আজ সন্ধ্যা ৭:০০ PM',
            'win_probability_team' => 'রাজস্থান',
            'win_probability_percentage' => '61',
            'prediction_text' => 'রাজস্থানের গান্ধি পরিবন্তন জাকাবুধু। পারবাগর মাস্তব পূর্বপ্রবল',
            'total_bets' => '৩,০০০+',
            'odds' => '1.90',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'match-image.png'
        ),
        array(
            'title' => 'মুম্বাই ইন্ডিয়ানস জেনেসোয়াকশি বনাম',
            'series_badge' => 'আর্মিপ্রিমিয়ার ২০২৫',
            'is_popular' => false,
            'team_name_1' => 'মুম্বাই ইন্ডিয়ানস জেনেসোয়াকশি',
            'team_name_2' => '',
            'match_time' => 'কাল রাত ১০:০০ PM',
            'win_probability_team' => 'মুম্বাই',
            'win_probability_percentage' => '68',
            'prediction_text' => 'মুম্বাইয়ের অভিজ্ঞ দল এবং শক্তিশালী ব্যাটিং লাইনআপ তাদের সুবিধা দেয়',
            'total_bets' => '২,৮০০+',
            'odds' => '1.75',
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
            'image' => 'cricket-image.png'
        ),
    );

    foreach ($sample_matches as $match_data) {
        // Create post
        $post_id = wp_insert_post(array(
            'post_type' => 'cricket_match',
            'post_title' => $match_data['title'],
            'post_status' => 'publish',
            'post_author' => 1,
        ));

        if ($post_id && !is_wp_error($post_id)) {
            // Update meta fields
            update_post_meta($post_id, '_cricket_series_badge', $match_data['series_badge']);
            update_post_meta($post_id, '_cricket_is_popular', $match_data['is_popular'] ? '1' : '');
            update_post_meta($post_id, '_cricket_team_name_1', $match_data['team_name_1']);
            update_post_meta($post_id, '_cricket_team_name_2', $match_data['team_name_2']);
            update_post_meta($post_id, '_cricket_match_time', $match_data['match_time']);
            update_post_meta($post_id, '_cricket_win_probability_team', $match_data['win_probability_team']);
            update_post_meta($post_id, '_cricket_win_probability_percentage', $match_data['win_probability_percentage']);
            update_post_meta($post_id, '_cricket_prediction_text', $match_data['prediction_text']);
            update_post_meta($post_id, '_cricket_total_bets', $match_data['total_bets']);
            update_post_meta($post_id, '_cricket_odds', $match_data['odds']);
            update_post_meta($post_id, '_cricket_bet_button_text', $match_data['bet_button_text']);
            update_post_meta($post_id, '_cricket_bet_button_url', $match_data['bet_button_url']);

            // Note: Featured images would need to be set manually or uploaded programmatically
        }
    }

    // Mark that sample data has been inserted
    update_option('cricket_matches_sample_data_inserted', true);
}

/**
 * Add 20 Random Cricket Match Posts
 * This function can be called manually to add more sample data
 */
function cricket_matches_add_random_posts() {
    $teams = array(
        'ঢাকা ডায়নামাইটস', 'চট্টগ্রাম চ্যালেঞ্জার্স', 'কুমিল্লা ওয়ারিয়র্স', 'রংপুর রাইডার্স',
        'সিলেট থান্ডার', 'খুলনা টাইগার্স', 'বরিশাল বুলস', 'রাজশাহী রয়্যালস',
        'কলকাতা নাইট রাইডার্স', 'মুম্বাই ইন্ডিয়ানস', 'চেন্নাই সুপার কিংস', 'দিল্লি ক্যাপিটালস',
        'বাংলাদেশ', 'ভারত', 'পাকিস্তান', 'শ্রীলংকা', 'অস্ট্রেলিয়া', 'ইংল্যান্ড',
        'নিউজিল্যান্ড', 'দক্ষিণ আফ্রিকা', 'ওয়েস্ট ইন্ডিজ', 'আফগানিস্তান'
    );

    $series = array(
        'বিপিএল ২০২৫', 'আইপিএল ২০২৫', 'টি-২০ বিশ্বকাপ', 'এশিয়া কাপ',
        'টেস্ট সিরিজ', 'ওয়ানডে সিরিজ', 'টি-২০ সিরিজ', 'চ্যাম্পিয়ন্স ট্রফি'
    );

    $times = array(
        'আজ সকাল ৯:০০ AM', 'আজ দুপুর ১২:০০ PM', 'আজ বিকাল ৩:০০ PM',
        'আজ সন্ধ্যা ৭:০০ PM', 'আজ রাত ১০:০০ PM', 'কাল সকাল ৮:০০ AM',
        'কাল বিকাল ৪:০০ PM', 'কাল সন্ধ্যা ৬:৩০ PM', 'পরশু সন্ধ্যা ৭:৩০ PM'
    );

    $predictions = array(
        'দলের ব্যাটিং লাইনআপ শক্তিশালী এবং বোলিং আক্রমণ চমৎকার ফর্মে রয়েছে',
        'হোম সুবিধা এবং দলের সাম্প্রতিক ফর্ম তাদের এগিয়ে রাখে',
        'দলীয় ভারসাম্য এবং খেলোয়াড়দের অভিজ্ঞতা মূল শক্তি',
        'পূর্ববর্তী ম্যাচের পারফরম্যান্স এবং মনোবল উচ্চ',
        'মাঠের পরিস্থিতি এই দলের জন্য অনুকূল',
        'কী খেলোয়াড়দের ফর্ম এবং দলীয় কৌশল শক্তিশালী',
        'সাম্প্রতিক জয়ের ধারা এবং দলীয় আত্মবিশ্বাস উচ্চ',
        'বোলিং আক্রমণ চমৎকার এবং ব্যাটিং অর্ডার সুসংগঠিত'
    );

    $random_posts = array();

    for ($i = 1; $i <= 20; $i++) {
        // Randomly select two different teams
        $team1_index = array_rand($teams);
        do {
            $team2_index = array_rand($teams);
        } while ($team2_index === $team1_index);

        $team1 = $teams[$team1_index];
        $team2 = $teams[$team2_index];
        $series_name = $series[array_rand($series)];
        $time = $times[array_rand($times)];
        $prediction = $predictions[array_rand($predictions)];

        // Random values
        $is_popular = (rand(1, 100) > 70); // 30% chance of being popular
        $win_percentage = rand(50, 85);
        $total_bets = rand(500, 5000);
        $odds = number_format(rand(150, 300) / 100, 2);

        $random_posts[] = array(
            'title' => $team1 . ' বনাম ' . $team2,
            'series_badge' => $series_name,
            'is_popular' => $is_popular,
            'team_name_1' => $team1,
            'team_name_2' => $team2,
            'match_time' => $time,
            'win_probability_team' => $team1,
            'win_probability_percentage' => $win_percentage,
            'prediction_text' => $prediction,
            'total_bets' => number_format($total_bets, 0, '.', ',') . '+',
            'odds' => $odds,
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
        );
    }

    // Insert posts
    foreach ($random_posts as $match_data) {
        $post_id = wp_insert_post(array(
            'post_type' => 'cricket_match',
            'post_title' => $match_data['title'],
            'post_status' => 'publish',
            'post_author' => 1,
        ));

        if ($post_id && !is_wp_error($post_id)) {
            update_post_meta($post_id, '_cricket_series_badge', $match_data['series_badge']);
            update_post_meta($post_id, '_cricket_is_popular', $match_data['is_popular'] ? '1' : '');
            update_post_meta($post_id, '_cricket_team_name_1', $match_data['team_name_1']);
            update_post_meta($post_id, '_cricket_team_name_2', $match_data['team_name_2']);
            update_post_meta($post_id, '_cricket_match_time', $match_data['match_time']);
            update_post_meta($post_id, '_cricket_win_probability_team', $match_data['win_probability_team']);
            update_post_meta($post_id, '_cricket_win_probability_percentage', $match_data['win_probability_percentage']);
            update_post_meta($post_id, '_cricket_prediction_text', $match_data['prediction_text']);
            update_post_meta($post_id, '_cricket_total_bets', $match_data['total_bets']);
            update_post_meta($post_id, '_cricket_odds', $match_data['odds']);
            update_post_meta($post_id, '_cricket_bet_button_text', $match_data['bet_button_text']);
            update_post_meta($post_id, '_cricket_bet_button_url', $match_data['bet_button_url']);
        }
    }

    return count($random_posts);
}

/**
 * Add 20 Random Cricket Posts (using default WordPress posts)
 */
function cricket_posts_add_random_posts() {
    $teams = array(
        'ঢাকা ডায়নামাইটস', 'চট্টগ্রাম চ্যালেঞ্জার্স', 'কুমিল্লা ওয়ারিয়র্স', 'রংপুর রাইডার্স',
        'সিলেট থান্ডার', 'খুলনা টাইগার্স', 'বরিশাল বুলস', 'রাজশাহী রয়্যালস',
        'কলকাতা নাইট রাইডার্স', 'মুম্বাই ইন্ডিয়ানস', 'চেন্নাই সুপার কিংস', 'দিল্লি ক্যাপিটালস',
        'বাংলাদেশ', 'ভারত', 'পাকিস্তান', 'শ্রীলংকা', 'অস্ট্রেলিয়া', 'ইংল্যান্ড',
        'নিউজিল্যান্ড', 'দক্ষিণ আফ্রিকা', 'ওয়েস্ট ইন্ডিজ', 'আফগানিস্তান'
    );

    $series = array(
        'বিপিএল ২০২৫', 'আইপিএল ২০২৫', 'টি-২০ বিশ্বকাপ', 'এশিয়া কাপ',
        'টেস্ট সিরিজ', 'ওয়ানডে সিরিজ', 'টি-২০ সিরিজ', 'চ্যাম্পিয়ন্স ট্রফি'
    );

    $times = array(
        'আজ সকাল ৯:০০ AM', 'আজ দুপুর ১২:০০ PM', 'আজ বিকাল ৩:০০ PM',
        'আজ সন্ধ্যা ৭:০০ PM', 'আজ রাত ১০:০০ PM', 'কাল সকাল ৮:০০ AM',
        'কাল বিকাল ৪:০০ PM', 'কাল সন্ধ্যা ৬:৩০ PM', 'পরশু সন্ধ্যা ৭:৩০ PM'
    );

    $predictions = array(
        'দলের ব্যাটিং লাইনআপ শক্তিশালী এবং বোলিং আক্রমণ চমৎকার ফর্মে রয়েছে',
        'হোম সুবিধা এবং দলের সাম্প্রতিক ফর্ম তাদের এগিয়ে রাখে',
        'দলীয় ভারসাম্য এবং খেলোয়াড়দের অভিজ্ঞতা মূল শক্তি',
        'পূর্ববর্তী ম্যাচের পারফরম্যান্স এবং মনোবল উচ্চ',
        'মাঠের পরিস্থিতি এই দলের জন্য অনুকূল',
        'কী খেলোয়াড়দের ফর্ম এবং দলীয় কৌশল শক্তিশালী',
        'সাম্প্রতিক জয়ের ধারা এবং দলীয় আত্মবিশ্বাস উচ্চ',
        'বোলিং আক্রমণ চমৎকার এবং ব্যাটিং অর্ডার সুসংগঠিত'
    );

    $random_posts = array();

    for ($i = 1; $i <= 20; $i++) {
        // Randomly select two different teams
        $team1_index = array_rand($teams);
        do {
            $team2_index = array_rand($teams);
        } while ($team2_index === $team1_index);

        $team1 = $teams[$team1_index];
        $team2 = $teams[$team2_index];
        $series_name = $series[array_rand($series)];
        $time = $times[array_rand($times)];
        $prediction = $predictions[array_rand($predictions)];

        // Random values
        $is_popular = (rand(1, 100) > 70); // 30% chance of being popular
        $win_percentage = rand(50, 85);
        $total_bets = rand(500, 5000);
        $odds = number_format(rand(150, 300) / 100, 2);

        $random_posts[] = array(
            'title' => $team1 . ' বনাম ' . $team2,
            'series_badge' => $series_name,
            'is_popular' => $is_popular,
            'team_name_1' => $team1,
            'team_name_2' => $team2,
            'match_time' => $time,
            'win_probability_team' => $team1,
            'win_probability_percentage' => $win_percentage,
            'prediction_text' => $prediction,
            'total_bets' => number_format($total_bets, 0, '.', ',') . '+',
            'odds' => $odds,
            'bet_button_text' => 'এখনই বেট করুন →',
            'bet_button_url' => '#',
        );
    }

    // Insert posts as default WordPress posts
    foreach ($random_posts as $match_data) {
        $post_id = wp_insert_post(array(
            'post_type' => 'post',
            'post_title' => $match_data['title'],
            'post_status' => 'publish',
            'post_author' => 1,
        ));

        if ($post_id && !is_wp_error($post_id)) {
            // Use _cricket_post_ prefix for default posts
            update_post_meta($post_id, '_cricket_post_series_badge', $match_data['series_badge']);
            update_post_meta($post_id, '_cricket_post_is_popular', $match_data['is_popular'] ? '1' : '');
            update_post_meta($post_id, '_cricket_post_team_name_1', $match_data['team_name_1']);
            update_post_meta($post_id, '_cricket_post_team_name_2', $match_data['team_name_2']);
            update_post_meta($post_id, '_cricket_post_match_time', $match_data['match_time']);
            update_post_meta($post_id, '_cricket_post_win_probability_team', $match_data['win_probability_team']);
            update_post_meta($post_id, '_cricket_post_win_probability_percentage', $match_data['win_probability_percentage']);
            update_post_meta($post_id, '_cricket_post_prediction_text', $match_data['prediction_text']);
            update_post_meta($post_id, '_cricket_post_total_bets', $match_data['total_bets']);
            update_post_meta($post_id, '_cricket_post_odds', $match_data['odds']);
            update_post_meta($post_id, '_cricket_post_bet_button_text', $match_data['bet_button_text']);
            update_post_meta($post_id, '_cricket_post_bet_button_url', $match_data['bet_button_url']);
        }
    }

    return count($random_posts);
}

/**
 * Plugin Activation Hook
 */
function cricket_matches_activate() {
    // Register post type first
    cricket_matches_register_post_type();

    // Flush rewrite rules
    flush_rewrite_rules();

    // Insert sample data
    cricket_matches_insert_sample_data();

    // Add 20 random posts
    cricket_matches_add_random_posts();
}
register_activation_hook(__FILE__, 'cricket_matches_activate');

/**
 * Plugin Deactivation Hook
 */
function cricket_matches_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'cricket_matches_deactivate');

/**
 * Enqueue Styles
 */
function cricket_matches_enqueue_styles() {
    wp_enqueue_style(
        'cricket-matches-style',
        CRICKET_MATCHES_PLUGIN_URL . 'css/style.css',
        array(),
        CRICKET_MATCHES_VERSION
    );
}
add_action('wp_enqueue_scripts', 'cricket_matches_enqueue_styles');

/**
 * Add Shortcode List Page to Settings Menu
 */
function cricket_matches_add_shortcode_list_page() {
    add_options_page(
        'Cricket Shortcode List',
        'Cricket Shortcodes',
        'manage_options',
        'cricket-shortcode-list',
        'cricket_matches_render_shortcode_list_page'
    );
}
add_action('admin_menu', 'cricket_matches_add_shortcode_list_page');

/**
 * Render Shortcode List Page
 */
function cricket_matches_render_shortcode_list_page() {
    ?>
    <div class="wrap cricket-shortcode-list-wrap">
        <h1>🏏 Cricket Matches Plugin - Shortcode List</h1>
        <p class="description">All available shortcodes for the Cricket Matches plugin. Click the copy button to copy any shortcode.</p>

        <style>
            .cricket-shortcode-list-wrap {
                max-width: 1200px;
            }
            .shortcode-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(500px, 1fr));
                gap: 20px;
                margin-top: 30px;
            }
            .shortcode-card {
                background: #fff;
                border: 1px solid #c3c4c7;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.05);
                transition: all 0.3s ease;
            }
            .shortcode-card:hover {
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                transform: translateY(-2px);
            }
            .shortcode-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 15px;
                padding-bottom: 15px;
                border-bottom: 2px solid #002C22;
            }
            .shortcode-title {
                font-size: 18px;
                font-weight: 600;
                color: #002C22;
                margin: 0;
            }
            .shortcode-badge {
                background: #002C22;
                color: #fff;
                padding: 4px 12px;
                border-radius: 12px;
                font-size: 12px;
                font-weight: 600;
            }
            .shortcode-code-block {
                background: #f6f7f7;
                border: 1px solid #dcdcde;
                border-radius: 4px;
                padding: 12px;
                margin: 10px 0;
                font-family: 'Courier New', monospace;
                font-size: 14px;
                position: relative;
            }
            .shortcode-code-block code {
                color: #d63638;
                font-weight: 600;
            }
            .copy-shortcode-btn {
                position: absolute;
                top: 8px;
                right: 8px;
                background: #002C22;
                color: #fff;
                border: none;
                padding: 6px 12px;
                border-radius: 4px;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.2s ease;
            }
            .copy-shortcode-btn:hover {
                background: #004d3d;
            }
            .copy-shortcode-btn.copied {
                background: #00a32a;
            }
            .shortcode-description {
                color: #50575e;
                line-height: 1.6;
                margin: 10px 0;
            }
            .shortcode-params {
                margin-top: 15px;
            }
            .shortcode-params h4 {
                font-size: 14px;
                font-weight: 600;
                color: #1d2327;
                margin-bottom: 8px;
            }
            .param-list {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .param-list li {
                padding: 6px 0;
                border-bottom: 1px solid #f0f0f1;
                font-size: 13px;
            }
            .param-list li:last-child {
                border-bottom: none;
            }
            .param-name {
                font-weight: 600;
                color: #002C22;
                font-family: 'Courier New', monospace;
            }
            .param-default {
                color: #787c82;
                font-style: italic;
            }
            .shortcode-examples {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid #dcdcde;
            }
            .shortcode-examples h4 {
                font-size: 14px;
                font-weight: 600;
                color: #1d2327;
                margin-bottom: 8px;
            }
            @media (max-width: 768px) {
                .shortcode-grid {
                    grid-template-columns: 1fr;
                }
            }
        </style>

        <div class="shortcode-grid">

            <!-- Shortcode 1: cricket_matches -->
            <div class="shortcode-card">
                <div class="shortcode-header">
                    <h3 class="shortcode-title">[cricket_matches]</h3>
                    <span class="shortcode-badge">Basic Display</span>
                </div>
                <div class="shortcode-code-block">
                    <code>[cricket_matches]</code>
                    <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches]')">Copy</button>
                </div>
                <p class="shortcode-description">
                    <strong>Post Type:</strong> <code>cricket_match</code><br>
                    Displays cricket matches from the "Cricket Match" custom post type in a responsive grid layout. Shows all match details including teams, time, predictions, odds, and betting buttons.
                </p>
                <div class="shortcode-params">
                    <h4>Parameters:</h4>
                    <ul class="param-list">
                        <li><span class="param-name">limit</span> - Number of matches to display <span class="param-default">(default: -1 = all)</span></li>
                        <li><span class="param-name">orderby</span> - Sort by field <span class="param-default">(default: date)</span></li>
                        <li><span class="param-name">order</span> - ASC or DESC <span class="param-default">(default: DESC)</span></li>
                    </ul>
                </div>
                <div class="shortcode-examples">
                    <h4>Examples:</h4>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches limit="6"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches limit=&quot;6&quot;]')">Copy</button>
                    </div>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches limit="3" orderby="title" order="ASC"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches limit=&quot;3&quot; orderby=&quot;title&quot; order=&quot;ASC&quot;]')">Copy</button>
                    </div>
                </div>
            </div>

            <!-- Shortcode 2: cricket_matches_paged -->
            <div class="shortcode-card">
                <div class="shortcode-header">
                    <h3 class="shortcode-title">[cricket_matches_paged]</h3>
                    <span class="shortcode-badge">With Pagination</span>
                </div>
                <div class="shortcode-code-block">
                    <code>[cricket_matches_paged]</code>
                    <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_paged]')">Copy</button>
                </div>
                <p class="shortcode-description">
                    <strong>Post Type:</strong> <code>cricket_match</code><br>
                    Displays cricket matches with pagination support and column control. Perfect for archive pages with many matches. Includes Bengali pagination with professional design.
                </p>
                <div class="shortcode-params">
                    <h4>Parameters:</h4>
                    <ul class="param-list">
                        <li><span class="param-name">limit</span> - Posts per page <span class="param-default">(default: 6)</span></li>
                        <li><span class="param-name">columns</span> - Number of columns (1-4) <span class="param-default">(default: 3)</span></li>
                        <li><span class="param-name">orderby</span> - Sort by field <span class="param-default">(default: date)</span></li>
                        <li><span class="param-name">order</span> - ASC or DESC <span class="param-default">(default: DESC)</span></li>
                    </ul>
                </div>
                <div class="shortcode-examples">
                    <h4>Examples:</h4>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches_paged limit="6" columns="3"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_paged limit=&quot;6&quot; columns=&quot;3&quot;]')">Copy</button>
                    </div>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches_paged limit="9" columns="3"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_paged limit=&quot;9&quot; columns=&quot;3&quot;]')">Copy</button>
                    </div>
                </div>
            </div>

            <!-- Shortcode 3: cricket_matches_list -->
            <div class="shortcode-card">
                <div class="shortcode-header">
                    <h3 class="shortcode-title">[cricket_matches_list]</h3>
                    <span class="shortcode-badge">List + Load More</span>
                </div>
                <div class="shortcode-code-block">
                    <code>[cricket_matches_list]</code>
                    <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_list]')">Copy</button>
                </div>
                <p class="shortcode-description">
                    <strong>Post Type:</strong> <code>cricket_match</code><br>
                    Displays cricket matches in a list/blog layout with "Load More" AJAX button. Includes excerpt, author info, Bengali date, and placeholder images. Automatically loads more posts without page reload.
                </p>
                <div class="shortcode-params">
                    <h4>Parameters:</h4>
                    <ul class="param-list">
                        <li><span class="param-name">limit</span> - Posts per load <span class="param-default">(default: 6)</span></li>
                    </ul>
                </div>
                <div class="shortcode-examples">
                    <h4>Examples:</h4>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches_list limit="6"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_list limit=&quot;6&quot;]')">Copy</button>
                    </div>
                    <div class="shortcode-code-block">
                        <code>[cricket_matches_list limit="9"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_matches_list limit=&quot;9&quot;]')">Copy</button>
                    </div>
                </div>
            </div>

            <!-- Shortcode 4: cricket_posts -->
            <div class="shortcode-card">
                <div class="shortcode-header">
                    <h3 class="shortcode-title">[cricket_posts]</h3>
                    <span class="shortcode-badge">WordPress Posts</span>
                </div>
                <div class="shortcode-code-block">
                    <code>[cricket_posts]</code>
                    <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_posts]')">Copy</button>
                </div>
                <p class="shortcode-description">
                    <strong>Post Type:</strong> <code>post</code> (regular WordPress posts)<br>
                    Displays regular WordPress posts with cricket match data from the meta box. Perfect for blog posts about matches. Shows image, teams, time, predictions, and betting info in match card format.
                </p>
                <div class="shortcode-params">
                    <h4>Parameters:</h4>
                    <ul class="param-list">
                        <li><span class="param-name">limit</span> - Number of posts <span class="param-default">(default: -1 = all)</span></li>
                        <li><span class="param-name">orderby</span> - Sort by field <span class="param-default">(default: date)</span></li>
                        <li><span class="param-name">order</span> - ASC or DESC <span class="param-default">(default: DESC)</span></li>
                    </ul>
                </div>
                <div class="shortcode-examples">
                    <h4>Examples:</h4>
                    <div class="shortcode-code-block">
                        <code>[cricket_posts limit="6"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_posts limit=&quot;6&quot;]')">Copy</button>
                    </div>
                    <div class="shortcode-code-block">
                        <code>[cricket_posts limit="10" order="ASC"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[cricket_posts limit=&quot;10&quot; order=&quot;ASC&quot;]')">Copy</button>
                    </div>
                </div>
            </div>

            <!-- Shortcode 5: blog_listing -->
            <div class="shortcode-card">
                <div class="shortcode-header">
                    <h3 class="shortcode-title">[blog_listing]</h3>
                    <span class="shortcode-badge">Blog Posts</span>
                </div>
                <div class="shortcode-code-block">
                    <code>[blog_listing]</code>
                    <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[blog_listing]')">Copy</button>
                </div>
                <p class="shortcode-description">
                    <strong>Post Type:</strong> <code>post</code> (regular WordPress posts)<br>
                    Displays regular WordPress blog posts with pagination. Shows featured image, title, excerpt, reading time, date, and author. Professional pagination design with Bengali numbers.
                </p>
                <div class="shortcode-params">
                    <h4>Parameters:</h4>
                    <ul class="param-list">
                        <li><span class="param-name">posts_per_page</span> - Posts per page <span class="param-default">(default: 6)</span></li>
                        <li><span class="param-name">category</span> - Category slug to filter <span class="param-default">(default: all)</span></li>
                        <li><span class="param-name">orderby</span> - Sort by field <span class="param-default">(default: date)</span></li>
                        <li><span class="param-name">order</span> - ASC or DESC <span class="param-default">(default: DESC)</span></li>
                    </ul>
                </div>
                <div class="shortcode-examples">
                    <h4>Examples:</h4>
                    <div class="shortcode-code-block">
                        <code>[blog_listing posts_per_page="6"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[blog_listing posts_per_page=&quot;6&quot;]')">Copy</button>
                    </div>
                    <div class="shortcode-code-block">
                        <code>[blog_listing posts_per_page="9" category="news"]</code>
                        <button class="copy-shortcode-btn" onclick="copyCricketShortcode(this, '[blog_listing posts_per_page=&quot;9&quot; category=&quot;news&quot;]')">Copy</button>
                    </div>
                </div>
            </div>

        </div>

        <script>
        function copyCricketShortcode(button, text) {
            navigator.clipboard.writeText(text).then(function() {
                var originalText = button.textContent;
                button.textContent = 'Copied!';
                button.classList.add('copied');

                setTimeout(function() {
                    button.textContent = originalText;
                    button.classList.remove('copied');
                }, 2000);
            }).catch(function(err) {
                alert('Failed to copy: ' + err);
            });
        }
        </script>
    </div>
    <?php
}

