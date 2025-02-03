<?php


namespace App\PageBuilder\Addons\Iconbox;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;

class NuvasaIconBoxGridTwo extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'icon-box/nuvasa-02.png';
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

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_five',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Icon')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'subtitle',
                    'label' => __('subtitle')
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'description',
                    'label' => __('Description')
                ],
            ]
        ]);

        $output .= Text::get([
            'name' => 'url',
            'label' => __('Button Url'),
            'value' => $widget_saved_values['url'] ?? null,
        ]);
        $output .= Text::get([
            'name' => 'label',
            'label' => __('Button Label'),
            'value' => $widget_saved_values['label'] ?? null,
        ]);
        $output .= Switcher::get([
            'name' => 'blank',
            'label' => __('Open New Tab'),
            'value' => $widget_saved_values['blank'] ?? null,
        ]);
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 20,
            'max' => 100,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 20,
            'max' => 100,
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
        $button_url = null;
        $button_label = null;
        if(!empty($settings['url'])) {
            $button_url = SanitizeInput::esc_html($settings['url']);
        }
        if(!empty($settings['url'])) {
            $button_label = SanitizeInput::esc_html($settings['label']);
        }
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);

        $category_markup = '';

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

        if($section_title_alignment == 'left'):
            $title_markup = '
                        <div class="col-md-8 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center text-md-end p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>
                        <div class="col-md-4 col-12"></div>';
        elseif($section_title_alignment == 'right') :
            $title_markup = '
                        <div class="col-md-4 col-12"></div>
                        <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                            <h2 class="text-white text-center text-md-start p-0 ps-lg-4 ps-md-5">'.$section_title.'
                            </h2>
                        </div>
                        ';
        else:
            $title_markup = '
                        <div class="col-lg-12 col-md-12 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>';
        endif;

        $random = random_int(9999,666666);
        $js = "<script>
        $(document).ready(function () {
        ";
        $js .= <<<JS
            $('#award_carousel{$random}').owlCarousel({
                loop: true,
                nav: false,
                dots: false,
                autoHeight: true,
                // animateOut: 'fadeOut',
                smartSpeed: 1000,
                mouseDrag: true,
                margin:10,
                items: 1,
                responsive: {
                    992: {
                        items: 5,
                        center: true,
                        autoplay: true,
                        autoplayTimeout: 3000,
                    },
                    768: {
                        items: 3,
                        center: true,
                        autoplay: true,
                        autoplayTimeout: 3000,
                    }
                }
            });
        JS;
        $js .= "})</script>";

        $button = '';
        $blank = !empty($settings['blank']) ? 'target="blank"' : '';
        if(!empty($button_label)) {
            $button .= '
            <div class="row mt-3">
                <div class="col-12">
                    <div class="text-center aos-init aos-animate" data-aos="fade-up" data-aos-duration="1500">
                        <a href="'.$button_url.'" '.$blank.' class="btn load_more_btn">'.$button_label.'</a>
                    </div>
                </div>
            </div>';
        }

        return <<<HTML
            <section id="award_section" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div id="nuvasabay">
                    <div class="container-fluid px-0">
                        <div class="row">
                            {$title_markup}
                        </div>
                    </div>
                    <div class="container">
                        <div class="row p-0 mt-4 awards_container">
                            <div id="award_carousel{$random}" class="award-carousel owl-carousel">
                                {$category_markup}
                            </div>
                        </div>
                        {$button}
                    </div>
                </div>
            </section>
            {$js}
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Icon Box Grid: 02');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $url = $this->get_repeater_field_value('url', $index, LanguageHelper::user_lang_slug());
        $icon = $this->get_repeater_field_value('icon', $index, LanguageHelper::user_lang_slug());
        $image = $this->get_repeater_field_value('image', $index, LanguageHelper::user_lang_slug());
        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        if(!empty($image['img_alt'])) {
            $image_markup = render_image_markup_by_attachment_id($image,'img-fluid');
        } else {
            $image_markup = render_image_markup_by_attachment_id($image,'img-fluid" alt="'.$title);
        }
        $subtitle = $this->get_repeater_field_value('subtitle', $index, LanguageHelper::user_lang_slug());
        $description = $this->get_repeater_field_value('description', $index, LanguageHelper::user_lang_slug());
        $icon_markup = '';
        $a = $this->a;
        $a >= 6 ? $this->a = 1 : $this->a++;
        if (!empty($icon)){
            $icon_markup = <<<HTML
                <div class="icon style-{$a}">
                    <i class="{$icon}"></i>
                </div>
            HTML;

        }
        $a = $this->a;
        $a >= 6 ? $this->a = 1 : $this->a++;

        return <<<HTML
            <div>
                <div class="award_container">
                    <div class="award_img">
                        {$image_markup}
                    </div>
                    <div class="award_name">
                        <p class="blacking">{$title}</p>
                    </div>
                    <div class="award_desc">
                        <p class="blacking">{$subtitle}</p>
                    </div>
                    <div class="award_by">
                        <p class="blacking">{$description}
                        </p>
                    </div>
                </div>
            </div>
        HTML;

    }
}