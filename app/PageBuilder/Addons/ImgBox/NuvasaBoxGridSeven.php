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

class NuvasaBoxGridSeven extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/nuvasa-grid-07.png';
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
                    'type' => RepeaterField::SELECT,
                    'name' => 'image_position',
                    'label' => __('Image Position'),
                    'options' => [
                        'left' => __('Left'),
                        'right' => __('Right'),
                    ],
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'logo',
                    'label' => __('Logo Image'),
                    'info' => __('optional')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'description',
                    'label' => __('Description')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'bottom_description',
                    'label' => __('Additional Description'),
                    'info' => __('Start from ...')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'url',
                    'label' => __('Button Url')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'label',
                    'label' => __('Button Label')
                ],
                [
                    'type' => RepeaterField::SWITCHER,
                    'name' => 'social_share',
                    'label' => __('Social Media Share Button')
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
                #nuvasa-box-grid-two-{$random} .desc p {
                    color: var(--nuvasabay-third-color);
                    line-height: 1.5em;
                }
                #nuvasa-box-grid-two-{$random} h3 {
                    color: var(--nuvasabay-third-color);
                    font-size: 28px;
                }
                #nuvasa-box-grid-two-{$random} .desc ul {
                    line-height: 1.5em;
                }
                #nuvasa-box-grid-two-{$random} p,
                #nuvasa-box-grid-two-{$random} p span {
                    font-weight:600;
                }
            </style>
        HTML;
        $category_markup = $style . '
            <section id="nuvasa-box-grid-two-'.$random.'" style="padding-bottom:'.$padding_bottom.'px;padding-top:'.$padding_top.'px">
                <div id="nuvasabay">
                    <div class="container-fluid p-0">
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
                    <div class="container px-0 pt-lg-4">';

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
        return __('Nuvasa Box Grid: 07');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();
        $current_lang = LanguageHelper::user_lang_slug();
        $section_title = SanitizeInput::esc_html($settings['section_title_'.$current_lang]);
        
        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $image = $this->get_repeater_field_value('image', $index, LanguageHelper::user_lang_slug());
        if(!empty($settings['bottom_right']['img_alt'])) {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100');
        } else if(!empty($title)) {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100" alt="'.$title);
        } else {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100" alt="'.$section_title);
        }

        $logo = $this->get_repeater_field_value('logo', $index, LanguageHelper::user_lang_slug());
        $logo_markup = render_image_markup_by_attachment_id($logo,'" style="width:auto;height:150px');
        $description = $this->get_repeater_field_value('description', $index, LanguageHelper::user_lang_slug());
        $bottom_description = $this->get_repeater_field_value('bottom_description', $index, LanguageHelper::user_lang_slug());
        $image_position = $this->get_repeater_field_value('image_position', $index, LanguageHelper::user_lang_slug());
        $url = $this->get_repeater_field_value('url', $index, LanguageHelper::user_lang_slug());
        $label = $this->get_repeater_field_value('label', $index, LanguageHelper::user_lang_slug());

        if($image_position == 'right') {
            $title_order =  "order-1 order-md-2";
            $desc_order =  "order-2 order-md-1";
        } else {
            $title_order =  "order-1 order-md-1";
            $desc_order =  "order-2 order-md-2";
        }
        
        $social_share = '';
        $route = request()->getSchemeAndHttpHost();
        if($this->get_repeater_field_value('social_share', $index, LanguageHelper::user_lang_slug())) {
            $social_share = <<<HTML
                <p class="fs-12">
                    <ul class="d-flex align-items-center gap-2 share-single" style="list-style: none;"> 
                        <li> <button class="btn share border" href="#" onclick="share()"><i class="fa-solid fa-share-nodes"></i></button> </li> 
                        <li> <a class="btn facebook" href="https://www.facebook.com/sharer/sharer.php?u={$route}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                        <li> <a class="btn twitter" href="https://twitter.com/intent/tweet?text=Nuvasabay&url={$route}" target="_blank"><i class="fab fa-twitter"></i></a></li>
                        <li> <a class="btn linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url={$route}&title=Nuvasabay" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                    </ul>
                </p>
            HTML;
        }

        return <<<HTML
            <div class="row gx-0 gx-lg-4">
                <div class="col-12 col-md-8" data-aos-duration="1500" data-aos="fade-up">
                    <div class="d-flex justify-content-center pt-3">
                        <div class="row mx-0 mobile-container">
                            <div class="col-12 px-0">
                                {$image_markup}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4 px-3 px-lg-4" data-aos-duration="1500" data-aos="fade-up">
                    <div class="row h-100 py-3">
                        <div class="col-12 align-self-start">
                            <div class="text-center">
                                {$logo_markup}
                            </div>
                            <div class="mb-md-5 mt-2">
                                <h3 class="text-left mb-3" style="line-height:1.5em">{$title}</h3>
                                <div class="fs-12">
                                    {$description}
                                </div>
        
                            </div>
                        </div>
                        <div class="col-12 align-self-end">
                            {$social_share}
                            <h3 class="fs-4 lh-sm mb-3 fw-bold" style="color: black;">{$bottom_description}</h3>
                            <a class="fs-12 fw-bold text-uppercase bg-primary text-white py-2 px-4 rounded-4" style="letter-spacing: 1px;" href="{$url}">{$label}</a>
                        </div>
                    </div>
                </div>
            </div>
        HTML;

    }
}