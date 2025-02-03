<?php


namespace App\PageBuilder\Addons\Slider;
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

class NuvasaSliderBoxGrid extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'slider/nuvasa-slider-box-grid.png';
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
                    'name' => 'image_l',
                    'label' => __('Image Left')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title_l',
                    'label' => __('Title Left')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'desc_l',
                    'label' => __('Description Left')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image_t',
                    'label' => __('Image Top')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title_t',
                    'label' => __('Title Top')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'desc_t',
                    'label' => __('Description Top')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image_b',
                    'label' => __('Image Bottom')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title_b',
                    'label' => __('Title Bottom')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'desc_b',
                    'label' => __('Description Bottom')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image_r',
                    'label' => __('Image Right')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title_r',
                    'label' => __('Title Right')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'desc_r',
                    'label' => __('Description Right')
                ],
            ]
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
            $('#place_carousel{$random}').owlCarousel({
                loop: true,
                margin: 10,
                nav: false,
                dots: true,
                rewind: true,
                mouseDrag: false,
                touchDrag: false,
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                slideSpeed: 5000,
                paginationSpeed: 5000,
                autoplay:true,
                autoplayTimeout:5000,
                responsive: {
                    0: {
                        items: 1,
                        dots: false,
                        // autoHeight: true,
                        mouseDrag: false,
                        touchDrag: true
                    },
                    600: {
                        loop: false,
                        items: 1,
                        dots: false,
                        // autoHeight: true,
                        mouseDrag: false,
                        touchDrag: true
                    },
                    1025: {
                        items: 1,
                        // loop: true,
                        // autoHeight: true,
                        mouseDrag: false,
                        touchDrag: true
                    }
                }
            });
        JS;
        $js .= "})</script>";

        return <<<HTML
            <style>
                #place_carousel{$random} .place_text p {
                    color: #fff !important;
                    font-weight: 600;
                }
                @media (min-width:1025px) {
                    #middle-image .place_container{
                        height: calc(575px / 2);
                    }
                    #middle-image img{
                        height: 100%;
                    }
                }
            </style>
            <section id="nuvasa_slider_box_{$random}" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div id="nuvasabay">
                    <div class="container-fluid px-0">
                        <div class="row">
                            {$title_markup}
                        </div>
                    </div>
                    <div class="container">
                        <div class="row p-0 mt-4">
                            <div class="col-12 p-0">
                                <div id="place_carousel{$random}" class="place_carousel owl-carousel owl-theme">
                                    {$category_markup}
                                </div>
                            </div>
                        </div>
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
        return __('Nuvasa Slider Box Grid: 01');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $title_l = $this->get_repeater_field_value('title_l', $index, LanguageHelper::user_lang_slug());
        $title_t = $this->get_repeater_field_value('title_t', $index, LanguageHelper::user_lang_slug());
        $title_b = $this->get_repeater_field_value('title_b', $index, LanguageHelper::user_lang_slug());
        $title_r = $this->get_repeater_field_value('title_r', $index, LanguageHelper::user_lang_slug());
        
        $image = $this->get_repeater_field_value('image_l', $index, LanguageHelper::user_lang_slug());
        
        if(!empty($image['img_alt'])) {
            $image_markup_l = render_image_markup_by_attachment_id($image,'" style="height: 592px;"', LanguageHelper::user_lang_slug());
        } else {
            $image_markup_l = render_image_markup_by_attachment_id($image,'" style="height: 592px;" alt="'.$title_l.'', LanguageHelper::user_lang_slug());
        }
        
        $image = $this->get_repeater_field_value('image_r', $index, LanguageHelper::user_lang_slug());
        if(!empty($image['img_alt'])) {
            $image_markup_r = render_image_markup_by_attachment_id($image,'" style="height: 592px;"', LanguageHelper::user_lang_slug());
        } else {
            $image_markup_r = render_image_markup_by_attachment_id($image,'" style="height: 592px;" alt="'.$title_r.'', LanguageHelper::user_lang_slug());
        }
        $image = $this->get_repeater_field_value('image_t', $index, LanguageHelper::user_lang_slug());
        if(!empty($image['img_alt'])) {
            $image_markup_t = render_image_markup_by_attachment_id($image, LanguageHelper::user_lang_slug());
        } else {
            $image_markup_t = render_image_markup_by_attachment_id($image,'" alt="'.$title_t.'', LanguageHelper::user_lang_slug());
        }
        $image = $this->get_repeater_field_value('image_b', $index, LanguageHelper::user_lang_slug());
        if(!empty($image['img_alt'])) {
            $image_markup_b = render_image_markup_by_attachment_id($image, LanguageHelper::user_lang_slug());
        } else {
            $image_markup_b = render_image_markup_by_attachment_id($image,'" alt="'.$title_b.'', LanguageHelper::user_lang_slug());
        }

        $desc_l = $this->get_repeater_field_value('desc_l', $index, LanguageHelper::user_lang_slug());
        $desc_t = $this->get_repeater_field_value('desc_t', $index, LanguageHelper::user_lang_slug());
        $desc_b = $this->get_repeater_field_value('desc_b', $index, LanguageHelper::user_lang_slug());
        $desc_r = $this->get_repeater_field_value('desc_r', $index, LanguageHelper::user_lang_slug());

        return <<<HTML
            <div class="item">
                <div class="row g-3 p-0">
                    <div class="col-xl-4 col-12 place-col">
                        <div class="place_container" data-aos-duration="1500" data-aos="fade-up">
                            {$image_markup_l}
                            <div class="place_text bg-place-primary">
                                <h4>{$title_l}</h4>
                                <p>{$desc_l}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-12 place-col d-lg-block d-md-flex" id="middle-image">
                        <div class="place_container mb-md-0 mb-lg-3 me-md-2 me-lg-0 mb-4 mb-md-0" data-aos-duration="1500" data-aos="fade-up">
                            {$image_markup_t}
                            <div class="place_text bg-place-primary">
                                <h4>{$title_t}</h4>
                                <p>{$desc_t}</p>
                            </div>
                        </div>
                        <div class="place_container ms-md-2 ms-lg-0" data-aos-duration="1500" data-aos="fade-up">
                            {$image_markup_b}
                            <div class="place_text bg-place-primary">
                                <h4>{$title_b}</h4>
                                <p>{$desc_b}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-12 place-col">
                        <div class="place_container" data-aos-duration="1500" data-aos="fade-up">
                            {$image_markup_r}
                            <div class="place_text bg-place-primary">
                                <h4>{$title_r}</h4>
                                <p>{$desc_r}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;

    }
}