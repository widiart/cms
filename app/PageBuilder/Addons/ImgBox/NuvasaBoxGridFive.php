<?php


namespace App\PageBuilder\Addons\ImgBox;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;

class NuvasaBoxGridFive extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/nuvasa-grid-05.png';
    }

    /**
     * @inheritDoc
     */
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = LanguageHelper::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'section_title_'.$lang->slug,
                'label' => __('Section Title'),
                'value' => $widget_saved_values['section_title_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= Select::get([
            'name' => 'section_title_alignment',
            'label' => __('Section Title Alignment'),
            'options' => [
                'left' => __('Left'),
                'center' => __('Center'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['section_title_alignment'] ?? null,
            'info' => __('set alignment of section title')
        ]);

        $output .= $this->admin_language_tab_end(); //have to end language tab
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_four',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'logo',
                    'label' => __('Logo')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'description',
                    'label' => __('Description')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Button Url'),
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'label',
                    'label' => __('Button Label'),
                ],
            ]
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 110,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 110,
            'max' => 200,
        ]);

        // add padding option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    /**
     * @inheritDoc
     */
    public function frontend_render()
    {
        $settings = $this->get_settings();
        $current_lang = LanguageHelper::user_lang_slug();
        $section_title = SanitizeInput::esc_html($settings['section_title_'.$current_lang]);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);

        $random = random_int(9999,666666);
        $style = <<<HTML
            <style>
                #nuvasa-box-grid-three-{$random} .desc p {
                    color: var(--nuvasabay-third-color);
                }

                #nuvasa-box-grid-three-{$random} .desc p span{
                    font-weight: 600;
                }
            </style>
        HTML;
        
        
        $category_markup = $style . '
            <section id="nuvasa-box-grid-three-'.$random.'" style="padding-bottom:'.$padding_bottom.'px;padding-top:'.$padding_top.'px">
                <div class="container-fluid p-0" id="nuvasabay">
                    <div class="row p-0 position-relative bg-primary-before">';

        if($section_title_alignment == 'left'):
            $category_markup .= '
                        <div class="col-md-8 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center text-md-end p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>
                        <div class="col-md-4 col-12"></div>';
        elseif($section_title_alignment == 'right') :
            $category_markup .= '
                        <div class="col-md-4 col-12"></div>
                        <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                            <h2 class="text-white text-center text-md-start p-0 ps-lg-4 ps-md-5">'.$section_title.'
                            </h2>
                        </div>
                        ';
        else:
            $category_markup .= '
                        <div class="col-lg-12 col-md-12 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>';
        endif;

        $category_markup .='
                    </div>
                </div>
                <div class="container" id="nuvasabay">
                    <div class="row mt-3 residential_col aos-init aos-animate" data-aos-duration="1500" data-aos="fade-up">';

        $this->args['settings'] = RepeaterField::remove_default_fields($settings);
        foreach ($this->args['settings'] as $key => $setting){
            if (is_array($setting)){
                $this->args['repeater'] = $setting;
                $array_lang_item = $setting[array_key_last($setting)];
                if (!empty($array_lang_item) && is_array($array_lang_item) && count($array_lang_item) > 0) {
                    foreach ($array_lang_item as $index => $value) {

                        $category_markup .= $this->render_slider_markup($index); // for multiple array index
                    }
                } else {
                    $category_markup .= $this->render_slider_markup(); // for only one index of array
                }
            }
        }

        $category_markup .= '
                    </div>
                </div>
            </section>';

        return $category_markup;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Box Grid: 05');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $url = $this->get_repeater_field_value('url', $index, LanguageHelper::user_lang_slug());
        $label = $this->get_repeater_field_value('label', $index, LanguageHelper::user_lang_slug());
        $image = $this->get_repeater_field_value('image', $index, LanguageHelper::user_lang_slug());
        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        
        if(!empty($image['img_alt'])) {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100 uniqueness-img');
        } else {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100 uniqueness-img" alt="'.$title);
        }

        $logo = $this->get_repeater_field_value('logo', $index, LanguageHelper::user_lang_slug());
        $logo_markup = render_image_markup_by_attachment_id($logo,'img-fluid');
        $description = $this->get_repeater_field_value('description', $index, LanguageHelper::user_lang_slug());
        $button_markup = '';

        if(!empty($label)){
            $button_markup = <<<HTML
                <div class="position-absolute bottom-0 start-50 translate-middle-x">
                    <a href="{$url}" target="_blank" class="btn bg-primary">{$label}</a>
                </div>
            HTML;
        }

        return <<<HTML
        
            <div class="col-12 col-lg-6 overflow-hidden position-relative mb-3">
                <div class="mb-5">
                    <div class="mb-2 position-relative overflow-hidden">
                        {$image_markup}
                        <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                            <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">{$title}</h5>
                        </div>
                    </div>
                    <p class="fs-12">
                        {$description}
                    </p>
                </div>
                {$button_markup}
            </div>
        HTML;

    }
}