<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sample Fieldtype
 *
 * @author    Eli Van Zoeren <eli@elivz.com>
 * @copyright Copyright (c) 2014 Eli Van Zoeren
 * @license   http://creativecommons.org/licenses/by-sa/3.0/ Attribution-Share Alike 3.0 Unported
 */

class Sample_fieldtype_ft extends EE_Fieldtype {

    public $info = array(
        'name'    => 'Sample Fieldtype',
        'version' => '1.0'
    );

    public $field_type = 'sample_fieldtype';
    public $has_array_data = FALSE; // Set to TRUE if the field can be used as a tag pair


    // --------------------------------------------------------------------


    /**
     * Fieldtype Constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Load the language file
        ee()->lang->loadfile($this->field_type);

        // Put any other initialization code here
    }

    /*
     * Register acceptable content types
     */
    public function accepts_content_type($name)
    {
        return ($name == 'channel' || $name == 'grid');
    }


    // --------------------------------------------------------------------


    /**
     * Include the JS and CSS files, but only the first time
     */
    private function _include_js_css()
    {
        if ( ! ee()->session->cache(__CLASS__, 'js_css'))
        {
            // Output stylesheet
            $css = file_get_contents(PATH_THIRD . '/' . $this->field_type . '/assets/styles.css');
            ee()->cp->add_to_head('<style type="text/css">' . $css . '</style>');

            // Output Javascript
            $scripts = file_get_contents(PATH_THIRD . '/' . $this->field_type . '/assets/scripts.js');
            ee()->javascript->output($scripts);

            // Make sure we only load them once
            ee()->session->set_cache(__CLASS__, 'js_css', TRUE);
        }
    }


    // --------------------------------------------------------------------


    /**
     * Display Field Settings
     */
    public function display_settings($settings)
    {
        $canhaz = isset($settings['canhaz']) && $settings['canhaz'] == 'y';

        $settings_ui = array(
            lang('canhaz', 'canhaz'),
            form_radio('canhaz', 'y', $canhaz, 'id="canhaz_yes"') . ' ' .
            form_label(lang('yes'), 'canhaz_yes') .
            '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
            form_radio('canhaz', 'n', !$canhaz, 'id="canhaz_no"') . ' ' .
            form_label(lang('no'), 'canhaz_no')
        );
        ee()->table->add_row($settings_ui);
    }

    /**
     * Display Grid Cell Settings
     */
    public function grid_display_settings($settings)
    {
        $canhaz = isset($settings['canhaz']) ? $settings['canhaz'] : FALSE;

        return array(
            $this->grid_checkbox_row(
                lang('canhaz'),
                'canhaz',
                'y',
                $canhaz
            )
        );
    }

    /**
     * Display Matrix Cell Settings
     */
    public function display_cell_settings($settings)
    {
        $canhaz = isset($settings['canhaz']) ? $settings['canhaz'] : FALSE;

        return array(
            array(
                lang('canhaz'),
                form_checkbox('canhaz', 'y', $canhaz)
            )
        );
    }

    /**
     * Display Low Variable Settings
     */
    public function display_var_settings($settings)
    {
        return $this->display_cell_settings($settings);
    }


    // --------------------------------------------------------------------


    /**
     * Save Field Settings
     */
    public function save_settings($data)
    {
        return array(
            'canhaz' => ee()->input->post('canhaz'),
        );
    }

    /**
     * Save Matrix Cell Settings
     */
    function save_cell_settings($data)
    {
        return $data;
    }

    /**
     * Save Low Variables Settings
     */
    public function save_var_settings()
    {
        return $this->save_settings();
    }


    // --------------------------------------------------------------------


    /**
     * Display Field on Publish
     *
     * @access   public
     * @param    existing data
     * @return   field html
     *
     */
    function display_field($data)
    {
        $this->_include_js_css();

        $form  = '';
        $form .= form_input($this->field_name, $data, 'id="' . $this->field_name . '"');

        return $form;
    }

    /**
     * Display Matrix Cell
     */
    public function display_cell($data)
    {
        return $this->display_field($data);
    }

    /**
     * Display Low Variable
     */
    public function display_var_field($data)
    {
        return $this->display_field($data);
    }


    // --------------------------------------------------------------------


    /**
     * Validate Field
     */
    public function validate($data)
    {
        if ($data == 'fail')
        {
            return lang('fail');
        }

        return TRUE;
    }

    /**
     * Validate Matrix Cell
     */
    function validate_cell($data)
    {
        return $this->validate($data);
    }


    // --------------------------------------------------------------------


    /**
     * Save Field
     */
    public function save($data)
    {
        // Do any processing on the data

        return $data;
    }

    /**
     * Save Matrix Cell
     */
    public function save_cell($data)
    {
        return $this->save($data);
    }

    /**
     * Save Low Variable
     */
    public function save_var_field($data)
    {
        return $this->save($data);
    }


    // --------------------------------------------------------------------


    /**
     * Replace template tag
     */
    public function replace_tag($data, $params=array(), $tagdata=FALSE)
    {
        if ($data == '') return;

        if ($tagdata)
        {
            // Handle tag pair - set $has_array_data to TRUE if you plan to use this
            $vars = array(
                'canhaz' => $data,
            );

            return ee()->TMPL->parse_variables_row($tagdata, $vars);
        }
        else
        {
            // Handle single tag
            return $data;
        }
    }

    /**
     * Replace two-part tag ({field:hazwhat})
     */
    public function replace_hazwhat($data, $params=array(), $tagdata=FALSE)
    {
        return 'I can haz ' . $data;
    }

    /**
     * Replace the tag for Low Variables
     */
    public function display_var_tag($data, $params=array(), $tagdata=FALSE)
    {
        return $this->replace_tag($data, $params, $tagdata);
    }

}