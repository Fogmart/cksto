<?php
/**
 * Admin controller
 * @package adk_import
 * @author Advertikon
 * @version 1.2.6
 * 
 * @#source catalog/view/javascript/advertikon/jquery-ui.min.js
 * @#source catalog/view/javascript/advertikon/jquery-ui.min.elf.js
 * @source catalog/view/javascript/advertikon/jquery.js
 * @source catalog/view/javascript/advertikon/advertikon.js
 * 
 * @#source admin/view/javascript/advertikon/jquery.ui.touch-punch.min.js
 * @#source admin/view/javascript/advertikon/elfinder/*
 * @#source admin/view/javascript/advertikon/elfinder_attachment_init.js
 * @source admin/view/javascript/advertikon/adk_import/adk_import.js
 * @source admin/view/javascript/advertikon/support.js
 * 
 * @#source admin/view/stylesheet/advertikon/fileupload/js/jquery.fileupload.js
 * @#source admin/view/stylesheet/advertikon/fileupload/js/jquery.fileupload-ui.js
 * @#source admin/view/stylesheet/advertikon/fileupload/js/jquery.fileupload-process.js
 * @#source admin/view/stylesheet/advertikon/fileupload/css/jquery.fileupload.css
 * @#source admin/view/stylesheet/advertikon/jquery-ui.min.css
 * @#source admin/view/stylesheet/advertikon/jquery-ui.theme.min.css
 * @#source admin/view/stylesheet/advertikon/fa/*
 * @source admin/view/stylesheet/advertikon/adk_import/adk_import.css
 * @#source admin/view/stylesheet/advertikon/images/*
 * @#source admin/view/stylesheet/advertikon/elfinder/*
 * 
 * @source catalog/view/theme/default/stylesheet/advertikon/advertikon.css
 * @source catalog/view/theme/default/stylesheet/advertikon/animate.min.css
 * 
 * @source image/catalog/advertikon/enable_modification.png
 * @source image/catalog/advertikon/refresh_modification.png
 * 
 * @source system/library/advertikon/advertikon.php
 * @source system/library/advertikon/exception/*
 * @source system/library/advertikon/array_iterator.php
 * @source system/library/advertikon/cache.php
 * @source system/library/advertikon/db_result.php
 * @source system/library/advertikon/exception.php
 * @source system/library/advertikon/fs.php
 * @source system/library/advertikon/option.php
 * @source system/library/advertikon/query.php
 * @source system/library/advertikon/renderer.php
 * @source system/library/advertikon/resource_wrapper.php
 * @source system/library/advertikon/task.php
 * @source system/library/advertikon/console.php
 * @source system/library/advertikon/url.php
 * @source system/library/advertikon/image.php
 * @source system/library/advertikon/placeholder.php
 * @source system/library/advertikon/update.php
 * @source system/library/advertikon/load.php
 * @source system/library/advertikon/tax.php
 * @source system/library/advertikon/twig.php
 * @source system/library/advertikon/Twig/*
 * @source system/library/advertikon/profiler.php
 * @source system/library/advertikon/compatibility_check.php
 * @source system/library/advertikon/language.php
 * @source system/library/advertikon/store.php
 * @source system/library/advertikon/parser/*
 * @source system/library/advertikon/tables.php
 * @source system/library/advertikon/adk_import/db_compatibility.php
 * @source system/library/advertikon/translator.php
 * @source system/library/advertikon/accounts.php
 * @source system/library/advertikon/account.php
 * @source system/library/advertikon/customer.php
 * @source system/library/advertikon/affiliate.php
 * @source system/library/advertikon/order.php
 * @source system/library/advertikon/setting.php
 * @source system/library/advertikon/address.php
 * @source system/library/advertikon/htmlentity.php
 * 
 * @source system/library/advertikon/adk_import/*
 * @#source system/library/advertikon/elfinder/*
 * 
 * @source system/library/advertikon/vendor/autoload.php
 * @source system/library/advertikon/vendor/composer/*
 * @source system/library/advertikon/vendor/phpoffice/*
 * @source system/library/advertikon/vendor/psr/*
 * 
 * @source system/library/advertikon/trait_task.php
 * @source system/library/advertikon/trait_update.php
 * @source system/library/advertikon/trait_support.php
 * 
 * @source system/library/advertikon/interface_admin_controller.php
 */
class ControllerModuleAdkImport extends Controller implements \Advertikon\Interface_Admin_Controller {
	use \Advertikon\Trait_Update;
	use \Advertikon\Trait_Task;
	use \Advertikon\Trait_Support;

	public $model = null;
	public $a = null;

	/**
	 * Class constructor
	 * @param Object $registry 
	 */
	public function __construct( Registry $registry ) {
		\Advertikon\Profiler::init();
		\Advertikon\Profiler::record( 'OC admin Controller __construct' );
		parent::__construct( $registry );
		\Advertikon\Profiler::record( 'OC admin Controller __construct' );

		\Advertikon\Profiler::record( 'Advertikon helper load' );
		$this->a = \Advertikon\Adk_Import\Advertikon::instance();
		\Advertikon\Profiler::record( 'Advertikon helper load' );

		\Advertikon\Profiler::record( 'Advertikon admin model load' );
		$this->model = $this->a->load->model( $this->a->full_name );
		\Advertikon\Profiler::record( 'Advertikon admin model load' );

		\Advertikon\Profiler::set_logger( $this->a->console );
		
		$this->a->clear_tmp();
	}

	/**
	 * Index action
	 * @return void
	 */
	public function index() {
		if ( !$this->user->hasPermission( 'access', $this->a->type . '/' . $this->a->code ) ) {
			die;
		}
		
		if( true !== ( $modification_error = $this->check_modifications() ) ) {
			echo $modification_error;
			die;
		}

		// JS scripts
//		$this->document->addScript( $this->a->u()->catalog_script( 'advertikon/jquery-ui.min.js' ) );
//		$this->document->addScript( HTTPS_SERVER . 'view/javascript/advertikon/jquery.ui.touch-punch.min.js' );

//		$this->document->addScript( HTTPS_SERVER . 'view/stylesheet/advertikon/fileupload/js/jquery.fileupload.js' );
//		$this->document->addScript( HTTPS_SERVER . 'view/stylesheet/advertikon/fileupload/js/jquery.fileupload-ui.js' );
//		$this->document->addScript( HTTPS_SERVER . 'view/stylesheet/advertikon/fileupload/js/jquery.fileupload-process.js' );

		$this->document->addScript( $this->a->u()->catalog_script( 'advertikon/advertikon.js' ) );
		$this->document->addScript( HTTPS_SERVER . 'view/javascript/advertikon/adk_import/adk_import.js' );
		// $this->document->addScript( "https://use.fontawesome.com/releases/v5.2.0/js/all.js" );

		// CSS rules
//		$this->document->addStyle( HTTPS_SERVER . 'view/stylesheet/advertikon/fa/css/font-awesome.min.css' );
		// $this->document->addStyle( "https://use.fontawesome.com/releases/v5.2.0/css/all.css" );
		// $this->document->addStyle( "https://use.fontawesome.com/releases/v5.2.0/css/v4-shims.css" );
//		$this->document->addStyle( HTTPS_SERVER . 'view/stylesheet/advertikon/jquery-ui.min.css' );
//		$this->document->addStyle( HTTPS_SERVER . 'view/stylesheet/advertikon/jquery-ui.theme.min.css' );
//		$this->document->addStyle( HTTPS_SERVER . 'view/stylesheet/advertikon/fileupload/css/jquery.fileupload.css' );

		$this->document->addStyle( $this->a->u()->catalog_css( 'advertikon/animate.min.css' ) );
		$this->document->addStyle( $this->a->u()->catalog_css( 'advertikon/advertikon.css' ) );
		$this->document->addStyle( HTTPS_SERVER . 'view/stylesheet/advertikon/adk_import/adk_import.css' );

		$data= [];
		$data['update_button'] = $this->get_update_button();
		$data['support'] = $this->render_support();

		if ( isset( $this->error['warning'] ) ) {
			$data['error_warning'] = $this->error['warning'];

		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->a->__( 'Home' ),
			'href' => $this->a->u( 'common/dashboard' ),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->a->__( 'Modules' ),
			'href' => version_compare( VERSION, '2.3', '>=' ) ?
				$this->a->u( 'extension/extension' ) : $this->a->u( 'extension/module' ),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->a->__( 'heading_title' ),
			'href' => $this->a->u( $this->a->full_name ),
		);

		// $data['action'] = $this->a->u( $this->a->full_name . '/preview' );

		if ( version_compare( VERSION, '3', '>=' ) ) {
			$data['cancel'] = $this->a->u( 'marketplace/extension' );

		} elseif ( version_compare( VERSION, '2.3', '>=' ) ) {
			$data['cancel'] = $this->a->u( 'extension/extension' );

		} else {
			$data['cancel'] = $this->a->u( 'extension/module' );
		}

		$data['locale']             = $this->get_locale();
		$data['version']            = $this->a->version();
		$data['name']               = $this->a->__( 'heading_title' );
		$data['header']             = $this->load->controller( 'common/header' );
		$data['column_left']        = $this->load->controller( 'common/column_left' );
		$data['footer']             = $this->load->controller( 'common/footer' );
		$data['compatibility']      = $this->a->check_compatibility();
		$data['fetch_file_url']     = $this->a->u( '/' );
		$data['query']              = $this->a->u()->parse( $this->a->u( 'fetch_file' ) )->get_query();
		$data['iframe_preload_url'] = $this->a->u( 'iframe_preload' );
		$data['export_url']         = $this->a->u( 'export' );
		$data['status']             = $this->a->load->view( $this->a->full_name . '/status', [] );
		$data['restore_url']        = $this->a->u( 'restore' );
		$data['support'] = $this->render_support();
		
		$this->get_controls( $data );

		$this->response->setOutput( $this->a->load->view( $this->a->full_name, $data ) );
	}
	
	/**
	 * Renders controls
	 * @param array $data
	 */
	private function get_controls( array &$data ) {
		$data['import_button'] = $this->a->r( [
			'type'        => 'button',
			'button_type' => 'success',
			'id'          => 'import-button',
			'icon'        => 'fa-rocket',
			'text_before' => $this->a->__( 'Run' ),
			'custom_data' => [ 'data-url' => $this->a->u( 'import' ) ],
		] );
		
		$data['export_button'] = $this->a->r( [
			'type'        => 'button',
			'button_type' => 'success',
			'id'          => 'export-button',
			'icon'        => 'fa-rocket',
			'text_before' => $this->a->__( 'Run' ),
			'custom_data' => [ 'data-url' => $this->a->u( 'export' ) ],
		] );
		
		$data['export_file'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Select file' ),
			'element' => [
				'type'        => 'file',
				'id'          => 'export-file',
				'class'       => 'adk-file-upload',
				'name'        => 'file[]',
				'custom_data' => [ 'multiple' => 'multiple' ],
			],
		] );
		
//		$data['save_to'] = $this->a->r()->render_form_group( [
//			'label' => $this->a->__( 'Save to' ),
//			'element' => [
//				'type'  => 'select',
//				'id'    => 'save-to-select',
//				'class' => 'form-control',
//				'value' => [
//					\Advertikon\Adk_Import\Item::SOURCE_BROWSER => $this->a->__( 'Browser' ),
//					\Advertikon\Adk_Import\Item::SOURCE_SERVER  => $this->a->__( 'Server' ),
//				],
//			],
//		] );
		
		$data['import_mode'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Import mode' ),
			'element' => [
				'type'  => 'select',
				'id'    => 'import-mode-select',
				'class' => 'form-control',
				'value' => [
					\Advertikon\Adk_Import\Item::MODE_SINGLE_FILE  => $this->a->__( 'Single spreadsheet' ),
					\Advertikon\Adk_Import\Item::MODE_SINGLE_PAGE  => $this->a->__( 'Separate spreadsheet for each item group' ),
					\Advertikon\Adk_Import\Item::MODE_SINGLE       => $this->a->__( 'Separate spreadsheet for each item' ),
				],
			],
		] );
		
		$data['importers'] = \Advertikon\Adk_Import\Importer\Importer::render_items( $this->a );
		$data['exporters'] = \Advertikon\Adk_Import\Exporter\Exporter::render_items( $this->a );
		
		$data['save_to_button'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Select a folder' ),
			'class' => 'select-folder-wrapper',
			'css'   => 'display: none',
			'element' => [
				'type'        => 'button',
				'button_type' => 'primary',
				'icon'        => 'fa-file',
				'text_before' => $this->a->__( 'Save to' ),
				'class'       => 'elfinder-button',
				'custom_data' => [ 'data-url' => $this->a->u( 'file_browser_content' ) ],
			],
		] );
		
		$data['excel_version'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'File format' ),
			'element' => [
				'type'  => 'select',
				'id'    => 'excel-version-select',
				'class' => 'form-control',
				'value' => [
					\Advertikon\Adk_Import\Item::EXCEL_2007  => $this->a->__( 'Excel 2007' ),
					\Advertikon\Adk_Import\Item::EXCEL_2003  => $this->a->__( 'Excel 2003' ),
					\Advertikon\Adk_Import\Item::EXCEL_CSV   => $this->a->__( 'CSV' ),
				],
			],
		] );
		
		$data['import_rows'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Records per file' ),
			'popup' => $this->a->__( 'Records per file' ),
			'element' => [
				'type'  => 'number',
				'id'    => 'import-rows',
				'class' => 'form-control',
				'value' => -1,
			],
			'tooltip' => $this->a->__( '-1 means no limits' ),
		] );
		
		$data['simple_mode'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Simple mode' ),
			'element' => $this->a->r()->render_fancy_checkbox( [
				'value' => '0',
				'id'    => 'import-simple',
			] ),
			'tooltip' => $this->a->__( 'Removes some decorations from a spreadsheet but speeds up a process' ),
		] );

		$data['dump_button'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Create back-up' ),
			'element' => [
				'type'        => 'button',
				'button_type' => 'success',
				'id'          => 'dump-button',
				'icon'        => 'fa-download',
				'text_before' => $this->a->__( 'BackUp' ),
				'custom_data' => [ 'data-url' => $this->a->u( 'dump' ) ],
			],
		] );

		$data['restore_button'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Apply back-up' ),
			'element' => [
				'type'        => 'button',
				'button_type' => 'success',
				'id'          => 'restore-button',
				'icon'        => 'fa-download',
				'text_before' => $this->a->__( 'Restore' ),
				'custom_data' => [ 'data-url' => $this->a->u( 'restore' ) ],
			],
		] );

		$data['dump_file'] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Select back-up file' ),
			'element' => [
				'type'        => 'file',
				'id'          => 'dump-file',
				'class'       => 'adk-file-upload',
				'name'        => 'file',
			],
		] );
		
		$data['dump_list'] = $this->model->get_dump_list_select();
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_MANUFACTURER;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->manufacturer() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}

		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Manufacturer' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when manufacturer does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_STOCK_STATUS;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->stock_status() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Stock status' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when stock status does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_TAX_CLASS;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->tax_class() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Tax class' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when tax status does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_WEIGHT_CLASS;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->weight_class() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Weight class' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when weight class does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_LENGTH_CLASS;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->length_class() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Length class' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when length class does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_ATTRIBUTE;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->attribute() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Attribute' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when attribute does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_DEFAULT_ATTRIBUTE_GROUP;
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Default attribute group' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => array_replace( [ 0 => $this->a->__( 'Select attribute gropup' ), ], $this->a->option()->attribute_group() ),
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'Attribute group to assign newly created attribute to' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_CUSTOMER_GROUP;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->customer_group() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Customer group' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when customer group does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_OPTION;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->option() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Option' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when option does not exist yet' ),
		] );
		
		$name = \Advertikon\Adk_Import\Exporter\Exporter::RULE_CONFIG_CATEGORY;
		$value = [
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_ABORT          => $this->a->__( 'Abort importing' ),
			\Advertikon\Adk_Import\Exporter\Exporter::RULE_PARTIAL_CREATE => $this->a->__( 'Create using available data' ),
		];
		
		foreach( $this->a->option()->category() as $k => $v ) {
			$value[ ':' . $k ] = $this->a->__( 'Use' ) . ' '  .$v;
		}
		
		$data[ $name ] = $this->a->r()->render_form_group( [
			'label' => $this->a->__( 'Category' ),
			'element' => [
				'type'  => 'select',
				'id'    => $name,
				'class' => 'form-control config-control',
				'value' => $value,
				'active' => \Advertikon\Setting::get( $name, $this->a ),
			],
			'description' => $this->a->__( 'What to do when category does not exist yet' ),
		] );
	}
	
	/**
	 * Returns LOCALE data
	 * @return string
	 */
	private function get_locale() {
		return json_encode( array(
			'elfinderAttachmentAction' => $this->a->u( 'attachments_connector' ),

			'elfinderAttachmentHref'  => $this->a->u( 'attachment_href' ),
			'elfinderImgHref'         => $this->a->u( 'img_href' ),
			'status_url'              => $this->a->u( 'status' ),
			'settingUrl'              => $this->a->u( 'set_setting' ),
			'checkMyIsamUrl'          => $this->a->u( 'check_is_myisam' ),

			'imageBase'                 => HTTPS_CATALOG . 'image/',
			'networkError'              => $this->a->__( 'Network error' ),
			'parseError'                => $this->a->__( 'Unable to parse server response string' ),
			'undefServerResp'           => $this->a->__( 'Undefined server response' ),
			'serverError'               => $this->a->__( 'Server error' ),
			'sessionExpired'            => $this->a->__( 'Current session has expired' ),
			'scriptError'               => $this->a->__( 'Script error' ),
			'prefix'                    => $this->a->code . '_',
			'modalHeader'               => $this->language->get( 'heading_title' ),
			'no'                        => $this->a->__( 'No' ),
			'yes'                       => $this->a->__( 'Yes' ),
			'needToFillIn'              => $this->a->__( 'Field is mandatory' ),
			'showTips'                  => 1 == $this->a->config( 'hint' ) ? '1' : '0',
			'fileBrowser'               => $this->a->__( 'File browser' ),
			'deleteItem'                => $this->a->__( 'Delete item?' ),
			'noItem'                    => $this->a->__( 'No one item is selected' ),
			'saveToServer'              => \Advertikon\Adk_Import\Item::SOURCE_SERVER,
			'memoryError'               => $this->a->__( 'The memory limit has been exceeded' ),
			'timeError'                 => $this->a->__( 'The time limit has been exceeded' ),
			'dumpListUrl'               => $this->a->u( 'fetch_dump_list_select' ),
		) ) . PHP_EOL;
	}

	/**
	 * Install action
	 */
	public function install() {
		if ( trait_exists( '\\Advertikon\\Trait_Update' ) ) {
			$this->_install();
		}

		if ( method_exists( $this->model, 'create_tables' ) || property_exists( $this->model, 'create_tables' ) ) {
			$this->model->create_tables();
		}

		if ( @mkdir($this->a->tmp_dir, 0777, true)) {
			$this->a->log(sprintf("Create folder '%s'", $this->a->tmp_dir));
		}

		// Class uses trait task
		if ( trait_exists( '\\Advertikon\\Trait_Task' ) ) {
			$this->a->log( 'Class uses trait task' );
			$task = $this->install_task();
			
			// // Add queue task
			// $task

			// 	// Run Queue each minute with threshold of 600 sec
			// 	->add_task(
			// 		$this->a->get_store_url() . 'index.php?route=' . $this->a->full_name . '/run_queue',
			// 		'* * * * *',
			// 		600,
			// 		$this->a->code
			// 	);
		}
	}

	/**
	 * Uninstall action
	 */
	public function uninstall() {
		if ( trait_exists( '\\Advertikon\\Trait_Update' ) ) {
			$this->_uninstall();
		}

		if ( is_dir( $this->a->data_dir ) ) {
			$fs = new \Advertikon\Fs();
			if ($fs->rmdir($this->a->data_dir)) {
				$this->a->log(sprintf("Deleting folder '%s'", $this->a->data_dir));
			}
		}

		if ( trait_exists( '\\Advertikon\\Trait_Task' ) ) {
			$this->uninstall_task();
		}
	}
	
	/**
	 * Process import request
	 * @api
	 */
	public function import() {
		$ret = [];

		try {
			\Advertikon\Adk_Import\Importer\Importer::process( $this->request->post, $this->a );
			$ret['result'] = \Advertikon\Adk_Import\Importer\Importer::get_result_files();
			
			if ( \Advertikon\Adk_Import\Item::get_source() === \Advertikon\Adk_Import\Item::SOURCE_BROWSER ) {
				$ret['fetch'] = '1';
			}

			$ret['success'] = $this->a->__( 'Import is finished' );
			
		} catch (Exception $e) {
			$ret['error'] = $e->getMessage();
			$this->a->error( $e );

		} catch ( Error $e ) {
			$ret['error'] = $e->getMessage();
			$this->a->error( $e );
		}
		
		$this->response->setOutput( json_encode( $ret ) );
	}
	
	/**
	 * Process import request
	 * @api
	 */
	public function export() {
		$ret = [];
		$ret['target'] = 'export';

		try {
			if ( !$this->a->has_permissions( \Advertikon\Advertikon::PERMISSION_MODIFY ) ) {
				throw new Exception( 'You have no permissions' );
			}

			\Advertikon\Adk_Import\Exporter\Exporter::process( $this->request->post, $this->request->files, $this->a );

			$ret['success'] = $this->a->__( 'Export is finished' );
			
		} catch ( Exception $e ) {
			$ret['error'] = $e->getMessage();
			$this->a->error( $e );

		} catch ( Error $e ) {
			$ret['error'] = $e->getMessage();
			$this->a->error( $e );
		}

		$this->response->setOutput( "<script>window.parent.postMessage( '" . addslashes( json_encode( $ret ) ) . "', '*' );</script>" );
	}
	
	/**
	 * Select attachment connector action 
	 * @return void
	 * @api
	 */
	// public function show_fs() {
	// 	if ( !$this->a->has_permissions( Advertikon\Advertikon::PERMISSION_ACCESS ) ) {
	// 		echo 'You have no acccess permission';
	// 	}

	// 	require_once( $this->a->elfinder_root . 'autoload.php' );

	// 	/**
	// 	 * Simple function to demonstrate how to control file access using "accessControl" callback.
	// 	 * This method will disable accessing files/folders starting from '.' (dot)
	// 	 *
	// 	 * @param  string  $attr  attribute name (read|write|locked|hidden)
	// 	 * @param  string  $path  file path relative to volume root directory started with directory separator
	// 	 * @return bool|null
	// 	 **/
	// 	function access($attr, $path, $data, $volume) {
	// 		return strpos(basename($path), '.') === 0
	// 			? !($attr == 'read' || $attr == 'write')
	// 			:  null;
	// 	}

	// 	$allowed_mime = explode( "\n", str_replace( "\r\n", "\n", $this->config->get( 'config_file_mime_allowed' ) ) );

	// 	$opts = array(
	// 		'debug' => true,
	// 		'roots' => array(
	// 			array(
	// 				'driver'        => 'LocalFileSystem',
	// 				'path'          => dirname( DIR_SYSTEM ),
	// 				'uploadDeny'    => array( 'all' ),
	// 				'uploadAllow'   => $allowed_mime,
	// 				'uploadOrder'   => array( 'deny', 'allow' ),
	// 				'accessControl' => 'access',
	// 				'attributes'    => array(
	// 					array(
	// 						'pattern' => '/\.csv$/',
	// 						'read'    => true,
	// 						'write'   => true,
	// 						'locked'  => true,
	// 						'hidden'  => false,
	// 					)
	// 				)
	// 			)
	// 		)
	// 	);

	// 	// run elFinder
	// 	$connector = new elFinderConnector( new elFinder( $opts ) );
	// 	$this->response->setOutput( json_encode( $connector->run() ) );
	// }
	
	/**
	 * File browser's iFrame contents
	 * @return void
	 * @api
	 */
	public function file_browser_content() {
		$data =[];
		$data['jquery_js'] = $this->a->u()->catalog_script( 'advertikon/jquery-ui.min.elf.js' );
		$data['connector_url'] = $this->a->u( 'show_fs' );
		
		$this->response->setOutput( $this->a->load->view( $this->a->full_name . '/file_browser', $data ) );
	}
	
	public function fetch_file() {
		try {
			if ( !$this->a->has_permissions( \Advertikon\Advertikon::PERMISSION_ACCESS ) ) {
				throw new Exception( 'You have no permission' );
			}

			if ( !isset( $this->request->request['file_name'] ) ) {
				throw new Exception( 'File name is missing' );
			}
			
			$file_name = $this->request->request['file_name'];
			
			\Advertikon\Adk_Import\Importer\Importer::load_file_to_browser( $file_name );
			
		} catch (Exception $ex) {
			$this->a->error( $ex );
			$error = [ 'error' => $ex->getMessage(), 'target' => 'fetch_file' ];

			echo '<script>window.parent.postMessage( "' . json_encode( $error ) . '", "*" );</script>';
		}
	}
	
	public function iframe_preload() {
		echo '';
	}

	public function status() {
		\Advertikon\Profiler::suppress();
		Advertikon\Adk_Import\Status::init( $this->a );
		$this->response->setOutput( Advertikon\Adk_Import\Status::receive_as_string() );
	}
	
	public function dump() {
		$dumper = new \Advertikon\Adk_Import\Dumper( $this->a );
		$ret = [];
		
		try {
			$dumper->dump();
			$ret['success'] = $this->a->__( 'Backup has been created' );

		} catch (Exception $ex) {
			$ret['error'] = $ex->getMessage();
			$this->a->error( $ex );
		}
		
		$this->response->setOutput( json_encode( $ret ) );
	}
	
	public function restore() {
		$ret = [ 'target' => 'reset' ];
		$content = [];
		$dumper = new \Advertikon\Adk_Import\Dumper( $this->a );
		$fp = null;

		try {
			$file = $this->a->post( 'file' );

			if ( $file ) {
				$fp = $dumper->get_dump_file_from_list( $file );

			} else if ( !empty( $this->request->files[ $this->a->get_prefix() . '_file' ] ) ) {
				$fp = fopen( $this->request->files[ $this->a->get_prefix() . '_file' ]['tmp_name'], 'r' );

			} else {
				throw new Exception( $this->a->__( 'Empty dataset' ) );
			}

			if ( !$fp ) {
				throw new Exception( $this->a->__( 'Failed to open dump file' ) );
			}
			
			$dumper->restore( $fp );
			$ret['success'] = $this->a->__( 'Database has been restored' );

		} catch (Exception $ex) {
			$ret['error'] = $ex->getMessage();
			$this->a->error( $ex );
		}
		
		$this->response->setOutput( '<script>window.parent.postMessage( \'' . json_encode( $ret ) . '\', "*" );</script>' );
	}
	
	public function fetch_dump_list_select() {
		$this->response->setOutput( $this->model->get_dump_list_select() );
	}

	public function add_products() {
		$this->load->model( 'catalog/product' );
		$number = 1000;

		$q = $this->a->db->query( 'select count(*) as count from ' . DB_PREFIX . 'product' );

		if ( !$q ) {
			throw new Exception( 'Failed to get products quantity' );
		}

		if ( $q->row['count'] >= $number ) {
			throw new Exception( sprintf( 'Products quantity is already more than %s', $number ) );
		}

		for( $i = 0; $i <= $number; $i++ ) {
			$this->model_catalog_product->copyProduct( 45 );
		}

		$this->db->query( 'update ' . DB_PREFIX . 'product set model = concat(\'product\', product_id)' );
	}

	public function delete_products() {
		$this->load->model( 'catalog/product' );
		$number = 1000;

		$q = $this->db->query( 'select count(*) as count from ' . DB_PREFIX . 'product' );

		if ( !$q ) {
			throw new Exception( 'Failed to get products quantity' );
		}

		for( $i = $number; $i <= max( 10000, $q->row['count'] ); $i++ ) {
			$this->model_catalog_product->deleteProduct( $i );
		}
	}

	public function check_is_myisam() {
		$ret = '';

		try {
			if ( \Advertikon\Adk_Import\Importer\Importer::is_myisam() ) {
				$ret = $this->a->r()->render_info_box( sprintf(
					'%s. <a href="%s" target="_blank">%s</a> %s %s',
					$this->a->__( 'Current database schema does not support transactions. ' .
					'In order to increase the safety of import operations, we recommend you to ' .
					'change table engine to InnoDB to enable transactions support' ),
					'https://dev.mysql.com/doc/refman/8.0/en/innodb-benefits.html',
					$this->a->__( 'More about InnoDB engine' ),
					$this->a->r( [
						'type'        => 'button',
						'button_type' => 'primary',
						'icon'        => 'fa-refresh',
						'text_after'  => $this->a->__( 'Convert to InnoDB' ),
						'custom_data' => [ 'data-url' => $this->a->u( 'convert_to_innodb' ) ],
						'id'          => 'to-innodb-button',
					] ),
					$this->a->__( 'Warning: the process can take a while, run it when your server has low load' )
				) );
			}

		} catch ( Exception $e ) {
			$this->a->error( $e );
		}

		$this->response->setOutput( $ret );
	}

	public function convert_to_innodb() {
		$ret = [];

		try {
			\Advertikon\Adk_Import\Importer\Importer::convert_to_innodb();
			$ret['success'] = $this->a->__( 'Table engine has been successfully changed' );

		} catch ( Exception $e ) {
			$this->a->error( $e );
			$ret['error'] = $e->getMessage();
		}

		$this->response->setOutput( json_encode( $ret ) );
	}
}
