<?php
/**
 * ACF Configuration
 *
 * @package GA
 */


/**
 * ACF
 * Class used to configure ACF plugin 
 */
class ACF_GA_Blocks {

    /**
     * Constructor
     */
	function __construct() {
		// load class.
		$this->setup_hooks();
	}

    /**
     * Method to add action and filter hooks for ACF
     *
     * @return void
     */
	function setup_hooks() {
        add_action( 'acf/init', [ $this, 'acf_init' ] );
        add_filter( 'acf/settings/load_json', [ $this, 'acf_load' ] );
        add_filter( 'acf/settings/save_json',  [ $this, 'acf_save' ] );
        add_filter( 'render_block', [ $this, 'render_block' ], PHP_INT_MAX - 1, 2 );
	}

    /**
     * This method creates a block category for our blocks, load every custom blocks and also create a theme settings page
     *
     * @return void
     */
    public function acf_init() {

        add_filter( 'block_categories_all', function( $categories, $post ) {
            return array_merge(
                $categories,
                array(
                    array(
                        'slug' => 'ga',
                        'title' => esc_html__( 'GA Blocks', 'ga' ),
                    ),
                )
            );
        }, 10, 2 );

        // Check if function exists.
        if ( function_exists( 'acf_register_block' ) ) {

            $blocks = require_once( GA_DIR_PATH . '/inc/acf/blocks.php');

            if ( is_array( $blocks ) ) {
                foreach ( $blocks as $name => $params ) {
                    $params = array_merge(
                        $params,
                        [
                            'name'            => $name,
                            'mode'            => 'edit',
                            'render_callback' => [ $this, 'block_render_callback' ],
                        ]
                    );

                    // Register a block.
                    acf_register_block( $params );
                }
            }
        }

        // Creates a settings page
        if ( function_exists( 'acf_add_options_page' ) ) {
            acf_add_options_page(
                [
                    'page_title'  => 'GA Theme Settings',
                    'parent_slug' => 'themes.php',
                ]
            );
        }

    }

    /**
     * Load a block template
     *
     * @param array $block
     * @return void
     */
    public function block_render_callback( $block ) {
        $slug = str_replace('acf/', '', $block['name']);

        if ( file_exists( get_theme_file_path( "/blocks/{$slug}.php" ) ) ) {
            include( get_theme_file_path( "blocks/{$slug}.php" ) );
        }
    }

    /**
     * This function can be used to modify custom and default blocks before they are displayed. 
     *
     * @param string    $block_content
     * @param array     $block
     * 
     * @return void
     */
    public function render_block( $block_content, $block ) {
        // $aos = 'data-aos="fade-up"';
//         echo '<pre>';
       
//         print_r($block);
//  echo '</pre>';
        if( 'core/column' != $block['blockName'] ) {
            
                if(isset($block['attrs']['align'])) {
                    if ( $block['attrs']['align'] == 'full' ) {
                        $block_content = sprintf( '<div class="default-block container-fluid">%s</div>', $block_content );
                    }
                }
                elseif ( 'core/button' == $block['blockName']) {
                    return $block_content;
                }
                elseif ( preg_match( '~^core/|core-embed/~', $block['blockName'] ) ) {
                    $block_content = sprintf( '<div class="default-block container">%s</div>', $block_content );
                }
        }


    //    if( 'acf/title-with-image' == $block['blockName'] ) {
            
    //             $block_content = sprintf( '<div class="default-block container-fluid">%s</div>', $block_content );
            
    //     }
    
        return $block_content;
    }


    /**
     * Sets the ACF JSON saving point
     * 
     * @param string $path
     * 
     * @return void
     */
    public function acf_save( $path ) {
        return GA_DIR_PATH . '/inc/acf/local-json';
    }


    /**
     * Sets the ACF JSON loading point
     *
     * @param array $paths
     * @return void
     */
    public function acf_load( $paths ) {
        unset( $paths[0] );
        $paths[] = GA_DIR_PATH . '/inc/acf/local-json';
        return $paths;
    }

}