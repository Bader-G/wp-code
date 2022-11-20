<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Carbon_Fields\Block;

add_action( 'carbon_fields_register_fields', 'crb_attach_theme_options' );
function crb_attach_theme_options() {
/* Custom post meta data */

//custom meta for programs
Container::make( 'post_meta', 'Program Data' )
->where( 'post_type', '=', 'programs' )
->add_fields( array( 
	Field::make( 'Image', 'program_icon', __( 'Program Icon' ) ),
	Field::make( 'Image', 'program_bg_img', __( 'Program Background Image' ) )
) );

/* custom gutenburg block */

//programs block
wp_enqueue_style('projects-block-stylesheet',get_template_directory_uri() . '/css/my-styles/projects-block.css');
 Block::make( __( 'Programs Block' ) )
  ->set_category( 'Baders' )
  ->set_mode( 'preview' )
	->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
	      $is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
    $loop = new WP_Query( array( 'post_type' => 'programs', 'posts_per_page' => ($is_editor)? 4 : -1, 'orderby' => 'date', 'order' => 'DESC') ); 
		?>
		<div class="albayan_projects row <?php echo ($is_editor)? 'wp-block-columns is-layout-flex' : '' ; ?>">
      <?php while ( $loop->have_posts() ) : $loop->the_post();?>
		<div class="albayan_projects__project col-md-6 <?php echo ($is_editor)? 'wp-block-column' : '' ; ?>">
    		<a href="<?php echo get_the_permalink(); ?>" style="background-image: url(<?php echo wp_get_attachment_url( get_post_meta( get_the_ID(), '_program_bg_img', true ), 'full' ); ?>);">
				<img src="<?php echo wp_get_attachment_url( get_post_meta( get_the_ID(), '_program_icon', true ), 'full' ); ?>" alt="img">
				<h3><?php echo get_the_title(); ?></h3>
			</a>
		</div><!-- /.block__heading -->
      <?php  endwhile; wp_reset_query(); ?>
    </div>
		<?php
	} );
	
  //count up block
  Block::make( __( 'Count Up Block' ) )
  ->add_fields( array(
    Field::make( 'text', 'amount', __( 'Amount to count' ) )->set_required(true)->set_attribute( 'type', 'number' ),
    Field::make( 'text', 'trail', __( 'Trail icon or text' ) )
  ) )
    ->set_category( 'Baders' )
    ->set_mode( 'preview' )
	->set_editor_style( 'projects-block-stylesheet' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
      $is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
      ?>
      <div class="statistic">
        <h2><span data-counter="<?php echo $fields['amount']; ?>" class="count-number"><?php echo ($is_editor)? $fields['amount'] : 0 ; ?></span><?php echo $fields['trail']; ?></h2>
      </div>
      <?php
	} );

	//Slide show
  wp_enqueue_style('crb-my-shiny-gutenberg-block-stylesheet',get_template_directory_uri() . '/css/my-styles/slider_block.css');
  	Block::make( __( 'Slider Block' ) )
  		->add_fields( array(
			Field::make( 'complex', 'slides', __( 'Slide' ) )
           	 	->set_layout('tabbed-horizontal')//remove this for not tabbed and verticle
            	->add_fields( array(
					Field::make( 'Image', 'image', __( 'Slider Image' ) )->set_required(true),
					Field::make( 'color', 'bg_color', __( 'Background Colour' ) ),
                	Field::make( 'textarea', 'title', __( 'Slider Text' ) ),
                  Field::make( 'textarea', 'content', __( 'Slider Content' ) ),
					Field::make( 'text', 'button_text', __( 'Button Text' ) ),
					Field::make( 'text', 'button_link', __( 'Button Link' ) ),
            	) )
  	) )
    ->set_category( 'Baders' )
    ->set_mode( 'preview' )
	->set_editor_style( 'crb-my-shiny-gutenberg-block-stylesheet' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
      $is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
      ?>
      <div class="cedarslider">
		<?php foreach($fields['slides'] as $slide): ?>
        	<div class="cedarslider__slide" style="<?php echo (!empty($slide['bg_color']))? '--main-bg-color:' . $slide['bg_color'] . ';' : '' ; ?>background-image: url(<?php echo wp_get_attachment_image_url($slide['image'], 'full'); ?>);">
				<div class="cedarslider__slide__row">
					<div class="container cedarslider__slide__row__content">
						<?php if(!empty($slide['title'])): ?>
							<h1><?php echo wpautop($slide['title']); ?></h1>
              <div class="cedarslider__slide__row__content__text"><?php echo wpautop($slide['content']); ?></div>
						<?php endif; ?>
						<?php if(!empty($slide['button_text']) && !empty($slide['button_link'])): ?>
							<div class="btn-clip-right"><a class="wp-element-button" href="<?php echo $slide['button_link']; ?>"><?php echo $slide['button_text']; ?></a></div>
						<?php endif; ?>
					</div>
				</div>

		  	</div>
		<?php endforeach; ?>
      </div>
      <?php
	} );

	//Initiatives
  wp_enqueue_style('initiatives-block-stylesheet',get_template_directory_uri() . '/css/my-styles/initiatives_block.css');
  	Block::make( __( 'Initiatives Block' ) )
  		->add_fields( array(
			Field::make( 'complex', 'initiatives', __( 'Initiative' ) )
           	 	->set_layout('tabbed-horizontal')//remove this for not tabbed and verticle
            	->add_fields( array(
					        Field::make( 'image', 'image', __( 'Initiative Image' ) )->set_required(true),
                	Field::make( 'text', 'title', __( 'Initiative title' ) ),
                  Field::make( 'textarea', 'content', __( 'Initiative Content' ) ),
            	) )
  	) )
    ->set_category( 'Baders' )
    ->set_mode( 'preview' )
	->set_editor_style( 'initiatives-block-stylesheet' )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
      $is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
      ?>
      <div class="cedarinitatives row" <?php echo ($is_editor)? 'style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); "' : ''; ?>>
		  <?php foreach($fields['initiatives'] as $initative): ?>
          <div class="cedarinitatives__initiative col-md-4" >
            <div class="cedarinitatives__initiative__intro">
              <img src="<?php echo wp_get_attachment_image_url($initative['image'], 'full'); ?>" alt="<?php echo $initative['title']; ?>">
              <h5><?php echo $initative['title']; ?></h5>
            </div>
            <div class="cedarinitatives__initiative__content text-light">
              <h5><?php echo $initative['title']; ?></h5>
              <div class="cedarinitatives__initiative__content__text"><?php echo wpautop($initative['content']); ?></div>
            </div>
          </div>
		  <?php endforeach; ?>
      </div>
      <?php
	} );

		//Masjid block
		wp_enqueue_style('initiatives-block-stylesheet',get_template_directory_uri() . '/css/my-styles/initiatives_block.css');
		Block::make( __( 'Masjid Block' ) )
			->add_fields( array(
			  Field::make( 'complex', 'initiatives', __( 'Initiative' ) )
					  ->set_layout('tabbed-horizontal')//remove this for not tabbed and verticle
				  ->add_fields( array(
					Field::make( 'image', 'image', __( 'Initiative Image' ) )->set_required(true),
					Field::make( 'text', 'title', __( 'Initiative title' ) ),
					Field::make( 'textarea', 'content', __( 'Initiative Content' ) ),
					Field::make( 'text', 'link_title', __( 'Button title' ) ),
					Field::make( 'text', 'link_href', __( 'Button link' ) ),
					Field::make( 'color', 'bg_color', __( 'Background Colour' ) ),
					Field::make( 'color', 'text_color', __( 'Text Colour' ) ),
				  ) ),
				Field::make( 'image', 'image', __( 'Last block Image' ) ),
				Field::make( 'text', 'title', __( 'Last block title' ) ),
				Field::make( 'textarea', 'content', __( 'Last block content' ) ),
		) )
	  ->set_category( 'Baders' )
	  ->set_mode( 'preview' )
	  ->set_editor_style( 'initiatives-block-stylesheet' )
	  ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
		$is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
		?>
		<div class="cedarinitatives cedarinitatives--masjid" <?php echo ($is_editor)? 'style="display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); "' : ''; ?>>
			<?php foreach($fields['initiatives'] as $initative): ?>
			<div class="cedarinitatives__initiative" >
			  <div class="cedarinitatives__initiative__intro" <?php echo (!empty($initative['bg_color']))? 'style="background: '.$initative['bg_color'].' ;"' : '' ; ?>>
				<img src="<?php echo wp_get_attachment_image_url($initative['image'], 'full'); ?>" alt="<?php echo $initative['title']; ?>">
				<h5 <?php echo (!empty($initative['bg_color']))? 'style="color: '.$initative['text_color'].' ;"' : '' ; ?>><?php echo $initative['title']; ?></h5>
			  </div>
			  <div class="cedarinitatives__initiative__content text-light">
				<h5><?php echo $initative['title']; ?></h5>
				<div class="cedarinitatives__initiative__content__text"><?php echo wpautop($initative['content']); ?></div>
				<?php if(!empty($initative['link_title']) && !empty($initative['link_href'])): ?>
					<div class="cedarinitatives__initiative__content__button"><a href="<?php echo esc_url($initative['link_href']); ?>"><?php echo esc_html($initative['link_title']); ?></a></div>
				<?php endif; ?>
			  </div>
			</div>
			<?php endforeach; ?>
			<?php if(!empty($fields['image']) && !empty($fields['title'])): ?>
				<div class="cedarinitatives__initiative cedarinitatives__initiative--last" >
					<img src="<?php echo wp_get_attachment_image_url($fields['image'], 'full'); ?>" alt="<?php echo $fields['title']; ?>">
					<h5><?php echo $fields['title']; ?></h5>
				</div>
			<?php endif; ?>
			<?php if(!empty($fields['content'])): ?>
				<?php echo wpautop($fields['content']); ?>
			<?php endif; ?>	
		</div>
		<?php
	  } );

	  //donations block
		wp_enqueue_style('donations-block-stylesheet',get_template_directory_uri() . '/css/my-styles/donations_block.css');
		Block::make( __( 'Donations Block' ) )
			->add_fields( array(
			  Field::make( 'complex', 'amounts', __( 'Custom amounts' ) )
					  ->set_layout('tabbed-horizontal')//remove this for not tabbed and verticle
				  ->add_fields( array(
					Field::make( 'text', 'amount', __( 'Amount' ) ),
				  ) ),
		) )
	  ->set_category( 'Baders' )
	  ->set_mode( 'preview' )
	  ->set_editor_style( 'donations-block-stylesheet' )
	  ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
		$is_editor = (strstr($_SERVER['REQUEST_URI'], 'wp-json') !== false)? true: false;
		?>
		<div class="albayan__donations jumbotron">
  			<h2>Donate to Albayan!</h2>
			<div class="row albayan__donations__selection">
				<div class="col-4 active"><span>One Time</span></div>
				<div class="col-4"><span>Weekly</span></div>
				<div class="col-4"><span>Monthly</span></div>
			</div>
			<div class="albayan__donations__amount">
				<?php foreach($fields['amounts'] as $amount): ?>
				<div class="albayan__donations__amount__selection" data-amount="<?php echo esc_html($amount['amount']); ?>"><span>$<?php echo esc_html($amount['amount']); ?></span></div>
				<?php endforeach; ?>
				<div class="albayan__donations__amount__selection--last"><input type="number" id="albayan__donations__amount__custom" name="albayan__donations__amount__custom" placeholder="Other:"></div>
			</div>
			
			<button class="rounded-0 btn btn-lg w-100 p-1" role="button">Donate Now!</button>
		</div>
		<?php
	  } );
}

add_action( 'after_setup_theme', 'crb_load' );
function crb_load() {
    \Carbon_Fields\Carbon_Fields::boot();
}