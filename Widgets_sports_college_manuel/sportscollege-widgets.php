<?php
/*
Plugin Name: SportsCollege - Widgets
Plugin URI:
Description: Add Custom Widgets to Sports Colleges Web Site
Version: 1.0.0
Author: Manuel Esteban Morales Zuarez
Author URI: https://github.com/Angstromico
Text Domain: SportsCollege
*/
if(!defined('ABSPATH')) die();
/**
 * Adds Foo_Widget widget.
 */
class Sports_College_Classes_Widgets extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'foo_widget', // Base ID
			esc_html__( 'Sports College Classes', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'Add the Classes as Widgets', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}	
    ?>
    <ul>
    <?php
	$quantity = $instance['cantidad'];
	if($quantity == ''){
		$quantity = 3;
}
    $args = array(
        'post_type' => 'classes',
        'posts_per_page' => $quantity,
        'orderby' => 'rand'

    );
    $classes = new WP_Query($args);
    while($classes->have_posts()): $classes->the_post();
    ?>
    <li class="sidebar-class">
        <div class="img">
        <?php the_post_thumbnail('thumnail'); ?>
        </div>
        <div class="content-class">
            <a href="<?php the_permalink(); ?>">
            <h3><?php the_title(); ?> </h3></a>
            <?php 
        $inscriptions = get_field('start_date');
        $graduation = get_field('final_date');
        ?>
            <p>On <?php the_field('class_days'); ?> from <?php echo $inscriptions . ' to ' . $graduation ?></p>
        </div>
    </li>
    <?php endwhile; wp_reset_postdata(); ?>
    </ul>
	<?php
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$quantity = ! empty($instance['quantity']) ? $instance['quantity'] : esc_html__('How many classes do you wish to show?', 'sporstcollege'); 
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('quantity')); ?>">
			<?php esc_attr_e('How many classes do you wish to show?', 'sportscollege'); ?>
			</label>
			<input 
            class="widefat" 
            id="<?php echo esc_attr( $this->get_field_id( 'quantity' ) ); ?>" 
            name="<?php echo esc_attr( $this->get_field_name( 'quantity' ) ); ?>" 
            type="number" 
            value="<?php echo esc_attr( $quantity ); ?>" >
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['quantity'] = ( ! empty( $new_instance['quantity'] ) ) ? sanitize_text_field( $new_instance['quantity'] ) : '';

		return $instance;
	}

} // class Foo_Widget
// register Foo_Widget widget
function register_foo_widget() {
    register_widget( 'Sports_College_Classes_Widgets' );
}
add_action( 'widgets_init', 'register_foo_widget' );