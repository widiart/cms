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

class NuvasaBoxGridSix extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/nuvasa-grid-06.png';
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
            $output .= Textarea::get([
                'name' => 'section_subtitle_'.$lang->slug,
                'label' => __('Section Subtitle'),
                'value' => $widget_saved_values['section_subtitle_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Image::get([
            'name' => 'top_left',
            'label' => __('Image Top Left'),
            'value' => $widget_saved_values['top_left'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'top_left_label',
            'label' => __('Top Left Label'),
            'value' => $widget_saved_values['top_left_label'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'bottom_left',
            'label' => __('Image Bottom Left'),
            'value' => $widget_saved_values['bottom_left'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'bottom_left_label',
            'label' => __('Bottom Left Label'),
            'value' => $widget_saved_values['bottom_left_label'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'center',
            'label' => __('Image Center'),
            'value' => $widget_saved_values['center'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'center_label',
            'label' => __('Center Label'),
            'value' => $widget_saved_values['center_label'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'top_right',
            'label' => __('Image Top Right'),
            'value' => $widget_saved_values['top_right'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'top_right_label',
            'label' => __('Top Right Label'),
            'value' => $widget_saved_values['top_right_label'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'bottom_right',
            'label' => __('Image Bottom Right'),
            'value' => $widget_saved_values['bottom_right'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'bottom_right_label',
            'label' => __('Bottom Right Label'),
            'value' => $widget_saved_values['bottom_right_label'] ?? null,
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
        $section_subtitle = SanitizeInput::esc_html($settings['section_subtitle_'.$current_lang]);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);
        
        $top_left_label = SanitizeInput::esc_html($settings['top_left_label']);
        $bottom_left_label = SanitizeInput::esc_html($settings['bottom_left_label']);
        $center_label = SanitizeInput::esc_html($settings['center_label']);
        $top_right_label = SanitizeInput::esc_html($settings['top_right_label']);
        $bottom_right_label = SanitizeInput::esc_html($settings['bottom_right_label']);

        if(!empty($settings['top_left']['img_alt'])) {
            $top_left = render_image_markup_by_attachment_id($settings['top_left'],'w-100 uniqueness-img" height="350');
        } else {
            $top_left = render_image_markup_by_attachment_id($settings['top_left'],'w-100 uniqueness-img" height="350" alt="'.$section_title);
        }

        if(!empty($settings['bottom_left']['img_alt'])) {
            $bottom_left = render_image_markup_by_attachment_id($settings['bottom_left'],'w-100 uniqueness-img" height="350');
        } else {
            $bottom_left = render_image_markup_by_attachment_id($settings['bottom_left'],'w-100 uniqueness-img" height="350" alt="'.$section_title);
        }

        if(!empty($settings['top_right']['img_alt'])) {
            $top_right = render_image_markup_by_attachment_id($settings['top_right'],'w-100 uniqueness-img" height="350');
        } else {
            $top_right = render_image_markup_by_attachment_id($settings['top_right'],'w-100 uniqueness-img" height="350" alt="'.$section_title);
        }
        
        if(!empty($settings['bottom_right']['img_alt'])) {
            $bottom_right = render_image_markup_by_attachment_id($settings['bottom_right'],'w-100 uniqueness-img" height="350');
        } else {
            $bottom_right = render_image_markup_by_attachment_id($settings['bottom_right'],'w-100 uniqueness-img" height="350" alt="'.$section_title);
        }
        
        if(!empty($settings['center']['img_alt'])) {
            $center = render_image_markup_by_attachment_id($settings['center'],'w-100 uniqueness-img" height="708');
        } else {
            $center = render_image_markup_by_attachment_id($settings['center'],'w-100 uniqueness-img" height="708" alt="'.$section_title);
        }

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
                    <div class="row p-0 mt-4 mx-0">
                        <div class="d-flex col-12 col-md-8mx-auto justify-content-center">
                            <p class="text-center fs-12">'.$section_subtitle.'
                            </p>
                        </div>
                    </div>
                    <div class="row mt-3 residential_col aos-init aos-animate" data-aos-duration="1500" data-aos="fade-up">
                        <div class="col-lg-4">
                            <div class="mb-2 position-relative overflow-hidden">
                                '.$top_left.'
                                <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                                    <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">'.$top_left_label.'</h5>
                                </div>
                            </div>
                            <div class="position-relative overflow-hidden">
                                '.$bottom_left.'
                                <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                                    <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">'.$bottom_left_label.'</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="position-relative overflow-hidden">
                                '.$center.'
                                <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                                    <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">'.$center_label.'</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-2 position-relative overflow-hidden">
                                '.$top_right.'
                                <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                                    <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">'.$top_right_label.'</h5>
                                </div>
                            </div>
                            <div class="position-relative overflow-hidden">
                                '.$bottom_right.'
                                <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                                    <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">'.$bottom_right_label.'</h5>
                                </div>
                            </div>
                        </div>
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
        return __('Nuvasa Box Grid: 06');
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
        
            <div class="col-12 col-lg-6 overflow-hidden position-relative">
                <div class="mb-2 position-relative overflow-hidden">
                    {$image_markup}
                    <div class="position-absolute top-0 start-0 ps-3 py-2 bg-grad pe-none" href="">
                        <h5 class="" style="font-family: poppinBold, sans-serif;font-weight: 700 !important; color: white;">{$title}</h5>
                    </div>
                </div>
                <p class="fs-12">
                    {$description}
                </p>
                {$button_markup}
            </div>
        HTML;

    }
}