<?php
/**
 * Fabrication Widget Class
 *
 * @package MW_Custom_Tab
 */

namespace MW_Custom_Tab\Widget;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Fabrication_Widget
 */
class Fabrication_Widget extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'mw_fabrication_widget';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return esc_html__( 'MW Fabrication Tabs', 'mw-custom-tab' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return [ 'general' ];
	}

	/**
	 * Get widget keywords.
	 */
	public function get_keywords() {
		return [ 'tabs', 'fabrication', 'mw', 'custom', 'service', 'accordion' ];
	}

	/**
	 * Script dependencies.
	 */
	public function get_script_depends() {
		return [ 'mw-custom-tab' ];
	}

	/**
	 * Style dependencies.
	 */
	public function get_style_depends() {
		return [ 'mw-custom-tab' ];
	}

	// ===========================================================
	// REGISTER CONTROLS
	// ===========================================================

	protected function register_controls() {

		// -------------------------------------------------------
		// SECTION: Layout
		// -------------------------------------------------------
		$this->start_controls_section( 'section_layout', [
			'label' => esc_html__( 'Layout', 'mw-custom-tab' ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'layout_style', [
			'label'   => esc_html__( 'Layout Style', 'mw-custom-tab' ),
			'type'    => Controls_Manager::SELECT,
			'default' => 'style_one',
			'options' => [
				'style_one' => esc_html__( 'Style One – Vertical Tabs', 'mw-custom-tab' ),
				'style_two' => esc_html__( 'Style Two – Category + Sub Tabs', 'mw-custom-tab' ),
			],
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION: Style One Tabs
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_one', [
			'label'     => esc_html__( 'Style One – Tabs', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'layout_style' => 'style_one' ],
		] );

		$repeater_one = new Repeater();

		$repeater_one->add_control( 'tab_title', [
			'label'       => esc_html__( 'Tab Title', 'mw-custom-tab' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Metal Fabrication', 'mw-custom-tab' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$repeater_one->add_control( 'tab_description', [
			'label'   => esc_html__( 'Description', 'mw-custom-tab' ),
			'type'    => Controls_Manager::WYSIWYG,
			'default' => esc_html__( 'High-strength, precision-engineered metal structures crafted for durability, safety, and long-term commercial performance.', 'mw-custom-tab' ),
		] );

		$repeater_one->add_control( 'tab_image', [
			'label'   => esc_html__( 'Image', 'mw-custom-tab' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'style_one_tabs', [
			'label'       => esc_html__( 'Tabs', 'mw-custom-tab' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $repeater_one->get_controls(),
			'default'     => [
				[
					'tab_title'       => esc_html__( 'Custom Design', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'Tailored design solutions crafted to match your vision and functional requirements.', 'mw-custom-tab' ),
				],
				[
					'tab_title'       => esc_html__( 'Metal Fabrication', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'High-strength, precision-engineered metal structures crafted for durability, safety, and long-term commercial performance.', 'mw-custom-tab' ),
				],
				[
					'tab_title'       => esc_html__( 'Wood Fabrication', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'Expert woodwork and joinery for furniture, fixtures, and bespoke interior elements.', 'mw-custom-tab' ),
				],
				[
					'tab_title'       => esc_html__( 'Interior Build-Outs', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'Complete interior construction and fit-out services for commercial and retail spaces.', 'mw-custom-tab' ),
				],
				[
					'tab_title'       => esc_html__( 'Project Management', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'End-to-end project coordination ensuring timely delivery and quality outcomes.', 'mw-custom-tab' ),
				],
				[
					'tab_title'       => esc_html__( 'Installation & Finishing', 'mw-custom-tab' ),
					'tab_description' => esc_html__( 'Professional installation and finishing services that bring your project to life.', 'mw-custom-tab' ),
				],
			],
			'title_field' => '{{{ tab_title }}}',
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION: Style Two – Settings
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_two_settings', [
			'label'     => esc_html__( 'Style Two – Settings', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'layout_style' => 'style_two' ],
		] );

		$this->add_control( 'content_display_type', [
			'label'       => esc_html__( 'Content Display Type', 'mw-custom-tab' ),
			'type'        => Controls_Manager::SELECT,
			'default'     => 'static',
			'options'     => [
				'static'   => esc_html__( 'Static Image', 'mw-custom-tab' ),
				'carousel' => esc_html__( 'Carousel (Swiper)', 'mw-custom-tab' ),
			],
			'description' => esc_html__( 'Choose how the middle image column is displayed.', 'mw-custom-tab' ),
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION: Style Two – Categories
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_two_categories', [
			'label'     => esc_html__( 'Style Two – Categories', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'layout_style' => 'style_two' ],
		] );

		$this->add_control( 'cat_index_notice', [
			'type'            => Controls_Manager::NOTICE,
			'notice_type'     => 'info',
			'content'         => esc_html__( 'Add your top-level categories here. In the Items section below, assign each item to a category using its 0-based index (0 = first category, 1 = second, etc.).', 'mw-custom-tab' ),
		] );

		$cat_repeater = new Repeater();

		$cat_repeater->add_control( 'category_name', [
			'label'       => esc_html__( 'Category Name', 'mw-custom-tab' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Sheet Metal Processing', 'mw-custom-tab' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$this->add_control( 'style_two_categories', [
			'label'       => esc_html__( 'Categories', 'mw-custom-tab' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $cat_repeater->get_controls(),
			'default'     => [
				[ 'category_name' => esc_html__( 'Sheet Metal Processing', 'mw-custom-tab' ) ],
				[ 'category_name' => esc_html__( 'Plate & Structural Steel Processing', 'mw-custom-tab' ) ],
				[ 'category_name' => esc_html__( 'Welding', 'mw-custom-tab' ) ],
				[ 'category_name' => esc_html__( 'Cranes & Material Handling', 'mw-custom-tab' ) ],
				[ 'category_name' => esc_html__( 'Machining Equipment', 'mw-custom-tab' ) ],
				[ 'category_name' => esc_html__( 'Inspection', 'mw-custom-tab' ) ],
			],
			'title_field' => '{{{ category_name }}}',
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION: Style Two – Items
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_two_items', [
			'label'     => esc_html__( 'Style Two – Items', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_CONTENT,
			'condition' => [ 'layout_style' => 'style_two' ],
		] );

		$item_repeater = new Repeater();

		$item_repeater->add_control( 'item_category_index', [
			'label'       => esc_html__( 'Category Index (0-based)', 'mw-custom-tab' ),
			'type'        => Controls_Manager::NUMBER,
			'default'     => 0,
			'min'         => 0,
			'step'        => 1,
			'description' => esc_html__( '0 = first category, 1 = second, etc.', 'mw-custom-tab' ),
		] );

		$item_repeater->add_control( 'item_title', [
			'label'       => esc_html__( 'Title', 'mw-custom-tab' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'CNC Laser Cutters', 'mw-custom-tab' ),
			'label_block' => true,
			'dynamic'     => [ 'active' => true ],
		] );

		$item_repeater->add_control( 'item_description', [
			'label'   => esc_html__( 'Description', 'mw-custom-tab' ),
			'type'    => Controls_Manager::WYSIWYG,
			'default' => esc_html__( 'Premium cutting, forming, and shaping systems ensure precise sheet metal fabrication with consistent quality and efficient repeat production. Precision cutting, forming, and shaping systems ensure precise sheet metal production with consolidated quality and efficient repeat product.', 'mw-custom-tab' ),
		] );

		$item_repeater->add_control( 'item_image', [
			'label'   => esc_html__( 'Image', 'mw-custom-tab' ),
			'type'    => Controls_Manager::MEDIA,
			'default' => [ 'url' => Utils::get_placeholder_image_src() ],
			'dynamic' => [ 'active' => true ],
		] );

		$item_repeater->add_control( 'item_button_text', [
			'label'       => esc_html__( 'Button Text', 'mw-custom-tab' ),
			'type'        => Controls_Manager::TEXT,
			'default'     => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
			'label_block' => true,
		] );

		$item_repeater->add_control( 'item_button_link', [
			'label'         => esc_html__( 'Button Link', 'mw-custom-tab' ),
			'type'          => Controls_Manager::URL,
			'placeholder'   => esc_html__( 'https://your-link.com', 'mw-custom-tab' ),
			'show_external' => true,
			'default'       => [
				'url'         => '#',
				'is_external' => false,
				'nofollow'    => false,
			],
			'dynamic' => [ 'active' => true ],
		] );

		$this->add_control( 'style_two_items', [
			'label'       => esc_html__( 'Items', 'mw-custom-tab' ),
			'type'        => Controls_Manager::REPEATER,
			'fields'      => $item_repeater->get_controls(),
			'default'     => [
				[
					'item_category_index' => 0,
					'item_title'          => esc_html__( 'CNC Laser Cutters', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'Premium cutting, forming, and shaping systems ensure precise sheet metal fabrication with consistent quality and efficient repeat production.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
				[
					'item_category_index' => 0,
					'item_title'          => esc_html__( 'CNC Press Brakes', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'Precision bending and forming solutions for consistent sheet metal fabrication with high-accuracy results across large production runs.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
				[
					'item_category_index' => 0,
					'item_title'          => esc_html__( 'Shearing Machines', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'Advanced shearing machines for precise and efficient metal sheet cutting, delivering clean edges and accurate dimensions every time.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
				[
					'item_category_index' => 0,
					'item_title'          => esc_html__( 'Rolling Machines', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'High-performance rolling machines for forming cylindrical and curved metal components with consistent radius and superior surface quality.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
				[
					'item_category_index' => 1,
					'item_title'          => esc_html__( 'Plasma Cutting', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'Industrial plasma cutting systems for precise structural steel processing and fabrication of heavy-gauge components.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
				[
					'item_category_index' => 1,
					'item_title'          => esc_html__( 'Flame Cutting', 'mw-custom-tab' ),
					'item_description'    => esc_html__( 'High-capacity flame cutting equipment for efficient processing of thick steel plate and structural profiles.', 'mw-custom-tab' ),
					'item_button_text'    => esc_html__( 'Get a Quote', 'mw-custom-tab' ),
				],
			],
			'title_field' => 'Cat {{{ item_category_index }}}: {{{ item_title }}}',
		] );

		$this->end_controls_section();

		// ===========================================================
		// STYLE TAB CONTROLS
		// ===========================================================

		// -------------------------------------------------------
		// SECTION STYLE: General
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_general', [
			'label' => esc_html__( 'General', 'mw-custom-tab' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'widget_bg_color', [
			'label'     => esc_html__( 'Widget Background', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mw-fabrication-widget' => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'widget_padding', [
			'label'      => esc_html__( 'Widget Padding', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-fabrication-widget' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Tab Navigation
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_nav', [
			'label' => esc_html__( 'Tab Navigation', 'mw-custom-tab' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'nav_typography',
			'label'    => esc_html__( 'Typography', 'mw-custom-tab' ),
			'selector' => '{{WRAPPER}} .mw-tab-nav-item, {{WRAPPER}} .mw-sub-tab-item',
		] );

		$this->add_control( 'nav_bg_color', [
			'label'     => esc_html__( 'Nav Background', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mw-tab-nav'  => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .mw-sub-tabs' => 'background-color: {{VALUE}};',
			],
		] );

		$this->start_controls_tabs( 'nav_tabs_style' );

		$this->start_controls_tab( 'nav_normal_tab', [
			'label' => esc_html__( 'Normal', 'mw-custom-tab' ),
		] );

		$this->add_control( 'nav_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#555555',
			'selectors' => [
				'{{WRAPPER}} .mw-tab-nav-item' => 'color: {{VALUE}};',
				'{{WRAPPER}} .mw-sub-tab-item' => 'color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'nav_active_tab', [
			'label' => esc_html__( 'Active', 'mw-custom-tab' ),
		] );

		$this->add_control( 'nav_active_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [
				'{{WRAPPER}} .mw-tab-nav-item.active' => 'color: {{VALUE}};',
				'{{WRAPPER}} .mw-sub-tab-item.active'  => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'nav_active_indicator_color', [
			'label'     => esc_html__( 'Indicator Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [
				'{{WRAPPER}} .mw-tab-nav-item.active::before' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .mw-sub-tab-item.active::before'  => 'background-color: {{VALUE}};',
			],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'nav_item_padding', [
			'label'      => esc_html__( 'Item Padding', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-tab-nav-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .mw-sub-tab-item'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'indicator_width', [
			'label'      => esc_html__( 'Indicator Width (px)', 'mw-custom-tab' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 1, 'max' => 10 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .mw-tab-nav-item::before' => 'width: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mw-sub-tab-item::before'  => 'width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Top Tab Bar (Style Two)
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_top_tabs', [
			'label'     => esc_html__( 'Top Tab Bar', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'layout_style' => 'style_two' ],
		] );

		$this->add_control( 'top_tab_bg', [
			'label'     => esc_html__( 'Background Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#1a1a1a',
			'selectors' => [ '{{WRAPPER}} .mw-top-tabs' => 'background-color: {{VALUE}};' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'top_tab_typography',
			'selector' => '{{WRAPPER}} .mw-top-tab-btn',
		] );

		$this->start_controls_tabs( 'top_tab_style_tabs' );

		$this->start_controls_tab( 'top_tab_normal', [
			'label' => esc_html__( 'Normal', 'mw-custom-tab' ),
		] );

		$this->add_control( 'top_tab_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#aaaaaa',
			'selectors' => [ '{{WRAPPER}} .mw-top-tab-btn' => 'color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'top_tab_active', [
			'label' => esc_html__( 'Active', 'mw-custom-tab' ),
		] );

		$this->add_control( 'top_tab_active_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [ '{{WRAPPER}} .mw-top-tab-btn.active' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'top_tab_active_border_color', [
			'label'     => esc_html__( 'Underline Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [ '{{WRAPPER}} .mw-top-tab-btn.active' => 'border-bottom-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'top_tab_padding', [
			'label'      => esc_html__( 'Tab Padding', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-top-tab-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_control( 'top_tab_underline_height', [
			'label'      => esc_html__( 'Underline Height (px)', 'mw-custom-tab' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px' ],
			'range'      => [ 'px' => [ 'min' => 1, 'max' => 8 ] ],
			'default'    => [ 'unit' => 'px', 'size' => 3 ],
			'selectors'  => [
				'{{WRAPPER}} .mw-top-tab-btn' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Content Area
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_content', [
			'label' => esc_html__( 'Content Area', 'mw-custom-tab' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'content_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#444444',
			'selectors' => [
				'{{WRAPPER}} .mw-tab-description' => 'color: {{VALUE}};',
				'{{WRAPPER}} .mw-item-description' => 'color: {{VALUE}};',
			],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'content_typography',
			'label'    => esc_html__( 'Typography', 'mw-custom-tab' ),
			'selector' => '{{WRAPPER}} .mw-tab-description, {{WRAPPER}} .mw-item-description',
		] );

		$this->add_control( 'content_bg_color', [
			'label'     => esc_html__( 'Background Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .mw-tab-content-area' => 'background-color: {{VALUE}};',
				'{{WRAPPER}} .mw-tab-right'         => 'background-color: {{VALUE}};',
			],
		] );

		$this->add_responsive_control( 'content_padding', [
			'label'      => esc_html__( 'Padding', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-tab-content-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .mw-tab-right'         => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Image
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_image', [
			'label' => esc_html__( 'Image', 'mw-custom-tab' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_responsive_control( 'image_height', [
			'label'      => esc_html__( 'Image Height', 'mw-custom-tab' ),
			'type'       => Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'vh' ],
			'range'      => [
				'px' => [ 'min' => 100, 'max' => 800 ],
				'vh' => [ 'min' => 10,  'max' => 100 ],
			],
			'default'    => [ 'unit' => 'px', 'size' => 420 ],
			'selectors'  => [
				'{{WRAPPER}} .mw-tab-content-image'  => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mw-tab-image-col'      => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mw-static-image'       => 'height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .mw-carousel-wrapper'   => 'height: {{SIZE}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'image_border',
			'selector' => '{{WRAPPER}} .mw-tab-content-image img, {{WRAPPER}} .mw-static-image img',
		] );

		$this->add_control( 'image_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-tab-content-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .mw-static-image img'       => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .mw-tab-content-image'      => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'image_shadow',
			'selector' => '{{WRAPPER}} .mw-tab-content-image img, {{WRAPPER}} .mw-static-image img',
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Button
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_button', [
			'label'     => esc_html__( 'Button', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'layout_style' => 'style_two' ],
		] );

		$this->add_group_control( Group_Control_Typography::get_type(), [
			'name'     => 'button_typography',
			'selector' => '{{WRAPPER}} .mw-item-btn',
		] );

		$this->start_controls_tabs( 'button_style_tabs' );

		$this->start_controls_tab( 'button_normal_tab', [
			'label' => esc_html__( 'Normal', 'mw-custom-tab' ),
		] );

		$this->add_control( 'button_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mw-item-btn' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_bg_color', [
			'label'     => esc_html__( 'Background Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [ '{{WRAPPER}} .mw-item-btn' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->start_controls_tab( 'button_hover_tab', [
			'label' => esc_html__( 'Hover', 'mw-custom-tab' ),
		] );

		$this->add_control( 'button_hover_text_color', [
			'label'     => esc_html__( 'Text Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#ffffff',
			'selectors' => [ '{{WRAPPER}} .mw-item-btn:hover' => 'color: {{VALUE}};' ],
		] );

		$this->add_control( 'button_hover_bg_color', [
			'label'     => esc_html__( 'Background Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#c56020',
			'selectors' => [ '{{WRAPPER}} .mw-item-btn:hover' => 'background-color: {{VALUE}};' ],
		] );

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control( 'button_padding', [
			'label'      => esc_html__( 'Padding', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', 'em' ],
			'default'    => [ 'top' => '12', 'right' => '28', 'bottom' => '12', 'left' => '28', 'unit' => 'px', 'isLinked' => false ],
			'selectors'  => [
				'{{WRAPPER}} .mw-item-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Border::get_type(), [
			'name'     => 'button_border',
			'selector' => '{{WRAPPER}} .mw-item-btn',
		] );

		$this->add_control( 'button_border_radius', [
			'label'      => esc_html__( 'Border Radius', 'mw-custom-tab' ),
			'type'       => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%' ],
			'default'    => [ 'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px' ],
			'selectors'  => [
				'{{WRAPPER}} .mw-item-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		] );

		$this->add_group_control( Group_Control_Box_Shadow::get_type(), [
			'name'     => 'button_shadow',
			'selector' => '{{WRAPPER}} .mw-item-btn',
		] );

		$this->end_controls_section();

		// -------------------------------------------------------
		// SECTION STYLE: Carousel
		// -------------------------------------------------------
		$this->start_controls_section( 'section_style_carousel', [
			'label'     => esc_html__( 'Carousel', 'mw-custom-tab' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => [ 'layout_style' => 'style_two', 'content_display_type' => 'carousel' ],
		] );

		$this->add_control( 'carousel_nav_color', [
			'label'     => esc_html__( 'Navigation Arrow Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [
				'{{WRAPPER}} .mw-carousel-wrapper .swiper-button-prev' => 'color: {{VALUE}};',
				'{{WRAPPER}} .mw-carousel-wrapper .swiper-button-next' => 'color: {{VALUE}};',
			],
		] );

		$this->add_control( 'carousel_dot_color', [
			'label'     => esc_html__( 'Pagination Dot Color', 'mw-custom-tab' ),
			'type'      => Controls_Manager::COLOR,
			'default'   => '#e07b39',
			'selectors' => [
				'{{WRAPPER}} .mw-carousel-wrapper .swiper-pagination-bullet-active' => 'background: {{VALUE}};',
			],
		] );

		$this->end_controls_section();
	}

	// ===========================================================
	// RENDER
	// ===========================================================

	protected function render() {
		$settings = $this->get_settings_for_display();
		$layout   = $settings['layout_style'];
		$display  = isset( $settings['content_display_type'] ) ? $settings['content_display_type'] : 'static';

		$this->add_render_attribute( 'wrapper', [
			'class'        => [ 'mw-fabrication-widget', 'mw-layout-' . esc_attr( $layout ) ],
			'data-layout'  => esc_attr( $layout ),
			'data-display' => esc_attr( $display ),
		] );
		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'style_one' === $layout ) {
				$this->render_style_one( $settings );
			} else {
				$this->render_style_two( $settings );
			}
			?>
		</div>
		<?php
	}

	// -----------------------------------------------------------
	// STYLE ONE RENDER
	// -----------------------------------------------------------
	private function render_style_one( array $settings ) {
		$tabs = $settings['style_one_tabs'];

		if ( empty( $tabs ) ) {
			echo '<p class="mw-empty-notice">' . esc_html__( 'Please add at least one tab.', 'mw-custom-tab' ) . '</p>';
			return;
		}

		$widget_id = $this->get_id();
		?>
		<div class="mw-style-one-wrapper">
			<!-- Left Nav -->
			<nav class="mw-tab-nav" role="tablist" aria-orientation="vertical" aria-label="<?php esc_attr_e( 'Service tabs', 'mw-custom-tab' ); ?>">
				<?php foreach ( $tabs as $index => $tab ) :
					$is_active = ( 0 === $index );
					$tab_id    = 'mw-s1-tab-' . $widget_id . '-' . $index;
					?>
					<div class="mw-tab-nav-item<?php echo $is_active ? ' active' : ''; ?>"
					     role="tab"
					     id="<?php echo esc_attr( $tab_id ); ?>"
					     aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
					     aria-controls="<?php echo esc_attr( $tab_id . '-panel' ); ?>"
					     tabindex="<?php echo $is_active ? '0' : '-1'; ?>"
					     data-index="<?php echo esc_attr( $index ); ?>">
						<?php echo esc_html( $tab['tab_title'] ); ?>
					</div>
				<?php endforeach; ?>
			</nav>

			<!-- Right Panels -->
			<div class="mw-tab-panels">
				<?php foreach ( $tabs as $index => $tab ) :
					$is_active = ( 0 === $index );
					$tab_id    = 'mw-s1-tab-' . $widget_id . '-' . $index;
					$img_url   = ! empty( $tab['tab_image']['url'] ) ? $tab['tab_image']['url'] : '';
					$img_id    = ! empty( $tab['tab_image']['id'] ) ? $tab['tab_image']['id'] : 0;
					$img_alt   = ! empty( $tab['tab_image']['alt'] ) ? $tab['tab_image']['alt'] : esc_attr( $tab['tab_title'] );
					?>
					<div class="mw-tab-panel<?php echo $is_active ? ' active' : ''; ?>"
					     id="<?php echo esc_attr( $tab_id . '-panel' ); ?>"
					     role="tabpanel"
					     aria-labelledby="<?php echo esc_attr( $tab_id ); ?>"
					     data-index="<?php echo esc_attr( $index ); ?>">
						<?php if ( $img_url ) : ?>
							<div class="mw-tab-content-image">
								<?php if ( $img_id ) {
									echo wp_get_attachment_image( $img_id, 'large', false, [
										'alt'     => $img_alt,
										'loading' => 'lazy',
									] );
								} else { ?>
									<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" />
								<?php } ?>
							</div>
						<?php endif; ?>
						<div class="mw-tab-content-area">
							<div class="mw-tab-description">
								<?php echo wp_kses_post( $tab['tab_description'] ); ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	// -----------------------------------------------------------
	// STYLE TWO RENDER
	// -----------------------------------------------------------
	private function render_style_two( array $settings ) {
		$categories   = $settings['style_two_categories'];
		$items        = $settings['style_two_items'];
		$display_type = $settings['content_display_type'];
		$widget_id    = $this->get_id();

		if ( empty( $categories ) ) {
			echo '<p class="mw-empty-notice">' . esc_html__( 'Please add at least one category.', 'mw-custom-tab' ) . '</p>';
			return;
		}

		// Group items by category index
		$grouped = [];
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$cat_idx            = isset( $item['item_category_index'] ) ? absint( $item['item_category_index'] ) : 0;
				$grouped[ $cat_idx ][] = $item;
			}
		}
		?>
		<div class="mw-style-two-wrapper">

			<!-- Top Horizontal Tab Bar -->
			<div class="mw-top-tabs" role="tablist" aria-orientation="horizontal" aria-label="<?php esc_attr_e( 'Category tabs', 'mw-custom-tab' ); ?>">
				<?php foreach ( $categories as $cat_index => $cat ) :
					$is_active = ( 0 === $cat_index );
					$cat_id    = 'mw-s2-cat-' . $widget_id . '-' . $cat_index;
					?>
					<button class="mw-top-tab-btn<?php echo $is_active ? ' active' : ''; ?>"
					        role="tab"
					        id="<?php echo esc_attr( $cat_id ); ?>"
					        aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
					        aria-controls="<?php echo esc_attr( $cat_id . '-panel' ); ?>"
					        data-cat-index="<?php echo esc_attr( $cat_index ); ?>">
						<?php echo esc_html( $cat['category_name'] ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<!-- Category Panels -->
			<div class="mw-cat-panels">
				<?php foreach ( $categories as $cat_index => $cat ) :
					$is_active  = ( 0 === $cat_index );
					$cat_id     = 'mw-s2-cat-' . $widget_id . '-' . $cat_index;
					$cat_items  = isset( $grouped[ $cat_index ] ) ? $grouped[ $cat_index ] : [];
					?>
					<div class="mw-cat-panel<?php echo $is_active ? ' active' : ''; ?>"
					     id="<?php echo esc_attr( $cat_id . '-panel' ); ?>"
					     role="tabpanel"
					     aria-labelledby="<?php echo esc_attr( $cat_id ); ?>"
					     data-cat-index="<?php echo esc_attr( $cat_index ); ?>"
					     data-display="<?php echo esc_attr( $display_type ); ?>">

						<?php if ( empty( $cat_items ) ) : ?>
							<p class="mw-empty-notice"><?php esc_html_e( 'No items in this category.', 'mw-custom-tab' ); ?></p>
						<?php else : ?>
							<div class="mw-three-col">

								<!-- LEFT: Vertical Sub Tabs -->
								<nav class="mw-sub-tabs" role="tablist" aria-orientation="vertical">
									<?php foreach ( $cat_items as $item_index => $item ) :
										$is_first   = ( 0 === $item_index );
										$item_id    = 'mw-s2-item-' . $widget_id . '-c' . $cat_index . '-i' . $item_index;
										?>
										<div class="mw-sub-tab-item<?php echo $is_first ? ' active' : ''; ?>"
										     role="tab"
										     id="<?php echo esc_attr( $item_id ); ?>"
										     aria-selected="<?php echo $is_first ? 'true' : 'false'; ?>"
										     aria-controls="<?php echo esc_attr( $item_id . '-content' ); ?>"
										     tabindex="<?php echo $is_first ? '0' : '-1'; ?>"
										     data-item-index="<?php echo esc_attr( $item_index ); ?>">
											<?php echo esc_html( $item['item_title'] ); ?>
										</div>
									<?php endforeach; ?>
								</nav>

								<!-- MIDDLE: Image Column -->
								<div class="mw-tab-image-col">
									<?php if ( 'carousel' === $display_type ) : ?>
										<div class="mw-carousel-wrapper swiper"
										     data-cat="<?php echo esc_attr( $cat_index ); ?>">
											<div class="swiper-wrapper">
												<?php foreach ( $cat_items as $item_index => $item ) :
													$img_url = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
													$img_id  = ! empty( $item['item_image']['id'] ) ? $item['item_image']['id'] : 0;
													$img_alt = ! empty( $item['item_image']['alt'] ) ? $item['item_image']['alt'] : esc_attr( $item['item_title'] );
													?>
													<div class="swiper-slide">
														<?php if ( $img_url ) :
															if ( $img_id ) {
																echo wp_get_attachment_image( $img_id, 'large', false, [
																	'alt'     => $img_alt,
																	'loading' => 'lazy',
																] );
															} else { ?>
																<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" />
															<?php }
														else : ?>
															<div class="mw-placeholder-img"></div>
														<?php endif; ?>
													</div>
												<?php endforeach; ?>
											</div>
											<div class="swiper-pagination"></div>
											<div class="swiper-button-prev"></div>
											<div class="swiper-button-next"></div>
										</div>
									<?php else : ?>
										<?php foreach ( $cat_items as $item_index => $item ) :
											$is_first = ( 0 === $item_index );
											$img_url  = ! empty( $item['item_image']['url'] ) ? $item['item_image']['url'] : '';
											$img_id   = ! empty( $item['item_image']['id'] ) ? $item['item_image']['id'] : 0;
											$img_alt  = ! empty( $item['item_image']['alt'] ) ? $item['item_image']['alt'] : esc_attr( $item['item_title'] );
											?>
											<div class="mw-static-image<?php echo $is_first ? ' active' : ''; ?>"
											     data-item-index="<?php echo esc_attr( $item_index ); ?>">
												<?php if ( $img_url ) :
													if ( $img_id ) {
														echo wp_get_attachment_image( $img_id, 'large', false, [
															'alt'     => $img_alt,
															'loading' => 'lazy',
														] );
													} else { ?>
														<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $img_alt ); ?>" loading="lazy" />
													<?php }
												else : ?>
													<div class="mw-placeholder-img"><?php esc_html_e( 'No image set', 'mw-custom-tab' ); ?></div>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

								<!-- RIGHT: Text + Button -->
								<div class="mw-tab-right-col">
									<?php foreach ( $cat_items as $item_index => $item ) :
										$is_first    = ( 0 === $item_index );
										$item_id     = 'mw-s2-item-' . $widget_id . '-c' . $cat_index . '-i' . $item_index;
										$btn_text    = ! empty( $item['item_button_text'] ) ? $item['item_button_text'] : '';
										$btn_url     = ! empty( $item['item_button_link']['url'] ) ? $item['item_button_link']['url'] : '#';
										$is_external = ! empty( $item['item_button_link']['is_external'] );
										$nofollow    = ! empty( $item['item_button_link']['nofollow'] );
										?>
										<div class="mw-tab-right<?php echo $is_first ? ' active' : ''; ?>"
										     id="<?php echo esc_attr( $item_id . '-content' ); ?>"
										     role="tabpanel"
										     aria-labelledby="<?php echo esc_attr( $item_id ); ?>"
										     data-item-index="<?php echo esc_attr( $item_index ); ?>">
											<div class="mw-item-description">
												<?php echo wp_kses_post( $item['item_description'] ); ?>
											</div>
											<?php if ( $btn_text ) :
												$btn_attrs = 'class="mw-item-btn"';
												$btn_attrs .= ' href="' . esc_url( $btn_url ) . '"';
												$btn_attrs .= ' target="' . ( $is_external ? '_blank' : '_self' ) . '"';
												if ( $is_external ) {
													$btn_attrs .= ' rel="noopener' . ( $nofollow ? ' nofollow' : '' ) . '"';
												} elseif ( $nofollow ) {
													$btn_attrs .= ' rel="nofollow"';
												}
												?>
												<a <?php echo $btn_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
													<?php echo esc_html( $btn_text ); ?>
												</a>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>

							</div><!-- .mw-three-col -->
						<?php endif; ?>

					</div><!-- .mw-cat-panel -->
				<?php endforeach; ?>
			</div><!-- .mw-cat-panels -->

		</div><!-- .mw-style-two-wrapper -->
		<?php
	}
}
