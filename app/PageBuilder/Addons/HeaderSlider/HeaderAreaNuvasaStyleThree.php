<?php


namespace App\PageBuilder\Addons\HeaderSlider;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;

class HeaderAreaNuvasaStyleThree extends PageBuilderBase
{
    use RepeaterHelper;

    public function preview_image()
    {
        return 'nuvasa-slider-03.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'main_title',
            'label' => __('Main Title'),
            'value' => $widget_saved_values['main_title'] ?? null,
        ]);

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

        $output .= Switcher::get([
            'name' => 'nav_button',
            'label' => __('Enable/Disable Nav Button'),
            'value' => $widget_saved_values['nav_button'] ?? null,
            'info' => __('your can show/hide navigation button'),
        ]);

        $output .= Switcher::get([
            'name' => 'nav_dots',
            'label' => __('Enable/Disable Nav Dots'),
            'value' => $widget_saved_values['nav_dots'] ?? null,
            'info' => __('your can show/hide navigation dots'),
        ]);

        $output .= Switcher::get([
            'name' => 'autoplay',
            'label' => __('Enable/Disable Autoplay'),
            'value' => $widget_saved_values['autoplay'] ?? null,
            'info' => __('your can Enable/Disable auto slide function'),
        ]);

        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id'=> 'header-seventeen',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image One'),
                    'dimensions' => '1920x1080px'
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image_2',
                    'label' => __('Image Two'),
                    'dimensions' => '1920x1080px'
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title'),
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'subtitle',
                    'label' => __('Subtitle'),
                ],
                [
                    'type' => RepeaterField::SUMMERNOTE,
                    'name' => 'description',
                    'label' => __('Description'),
                ],
            ]
        ]);
        
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 30,
            'max' => 100,
        ]);
        
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 30,
            'max' => 100,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render(): string
    {
        $all_settings = $this->get_settings();

        $nav = !empty($all_settings['nav_button']) ? 'true' : 'false';
        $dots = !empty($all_settings['nav_dots']) ? 'true' : 'false';
        $autoplay = !empty($all_settings['autoplay']) ? 'true' : 'false';

        $padding_top = SanitizeInput::esc_html($all_settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($all_settings['padding_bottom']);
        $section_title_alignment = SanitizeInput::esc_html($all_settings['section_title_alignment']);

        $random = random_int(9999,666666);
        $style = <<<HTML
            <style>
                #evalute_section{$random} #nuvasabay .elevated_carousel2 .island_container {
                    height: auto !important;
                }
            </style>
        HTML;
        $output = $style . '
            <section id="evalute_section'.$random.'" style="padding-bottom:'.$padding_bottom.'px;padding-top:'.$padding_top.'px">
                <div id="nuvasabay">
                    <div class="container-fluid p-0">
                        <div class="heading text-center">';
        if($section_title_alignment == 'left'):
            $output .= '
                            <div class="col-md-8 col-12 bg-primary text-center py-3 heading">
                                <h2 class="text-white text-center text-md-end p-0 pe-lg-4 pe-md-5">'.$all_settings['main_title'].'
                                </h2>
                            </div>
                            <div class="col-md-4 col-12"></div>';
        elseif($section_title_alignment == 'right') :
            $output .= '
                            <div class="col-md-4 col-12"></div>
                            <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                                <h2 class="text-white text-center text-md-start p-0 ps-lg-4 ps-md-5">'.$all_settings['main_title'].'
                                </h2>
                            </div>
                            ';
        else:
            $output .= '
                            <div class="col-lg-12 col-md-12 col-12 bg-primary text-center py-3 heading">
                                <h2 class="text-white text-center p-0 pe-lg-4 pe-md-5">'.$all_settings['main_title'].'
                                </h2>
                            </div>';
        endif;
            
                        
        $output .= '    </div>
                    </div>
                    <div class="container">
                        <div id="elevated_carousel2'.$random.'" class="elevated_carousel2 owl-carousel owl-theme">';

        $this->args['settings'] = RepeaterField::remove_default_fields($all_settings);
        foreach ($this->args['settings'] as $key => $setting){
            if (is_array($setting)){
                $this->args['repeater'] = $setting;
                $array_lang_item = $setting[array_key_last($setting)];
                if (!empty($array_lang_item) && is_array($array_lang_item) && count($array_lang_item) > 0) {
                    foreach ($array_lang_item as $index => $value) {

                        $output .= $this->render_slider_markup($index); // for multiple array index
                    }
                } else {
                    $output .= $this->render_slider_markup(); // for only one index of array
                }
            }
        }
        $output .= '
                        </div>
                    </div>
                </div>
            </section>';
        $output .= "<script>
        $(document).ready(function () {
        ";
        $output .= <<<JS
            $('#elevated_carousel2{$random}').owlCarousel({
                loop: false,
                nav: {$nav},
                dots: {$dots},
                autoplay: {$autoplay},
                autoplayTimeout: 3000,
                autoHeight: true,
                // animateOut: 'fadeOut',
                smartSpeed: 1000,
                mouseDrag: false,
                margin:10,
                items: 1,
            });
        JS;
        $output .= "})</script>";
        return $output;
    }

    public function addon_title()
    {
        return __('Nuvasa Slider: 03');
    }

    private function render_slider_markup(int $index = null): string
    {
        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $subtitle = $this->get_repeater_field_value('subtitle', $index, LanguageHelper::user_lang_slug());
        $description = $this->get_repeater_field_value('description', $index, LanguageHelper::user_lang_slug());

        $img = $this->get_repeater_field_value('image', $index, LanguageHelper::user_lang_slug());
        if(!empty($img['img_alt'])) {
            $image = render_image_markup_by_attachment_id($img,'w-100 px-2 py-2');
        } else {
            $image = render_image_markup_by_attachment_id($img,'w-100 px-2 py-2" alt="'.$title);
        }
        
        $img2 = $this->get_repeater_field_value('image_2', $index, LanguageHelper::user_lang_slug());
        if(!empty($img2['img_alt'])) {
            $image_2 = render_image_markup_by_attachment_id($img2,'w-100 px-2 py-2');
        } else {
            $image_2 = render_image_markup_by_attachment_id($img2,'w-100 px-2 py-2" alt="'.$title);
        }
        
        $settings = $this->get_settings();

        return <<<HTML
            <div class="item px-2">
                <div class="island_container">
                    <div class="row m-0 g-0">
                        <div class="col-lg-4 col-12 d-flex flex-column justify-content-center">
                            {$image}
                        </div>
                        <div class="col-lg-5 col-12">
                            {$image_2}
                        </div>
                        <div class="col-lg-3 col-12">
                            <div class="elevated-slide-content">
                                <h3 class="slide-title fs-4 my-0 lh-sm">{$title}</h3>
                                <h3 class="fs-15 lh-sm mb-3">{$subtitle}</h3>
                                <div class="fs-12">
                                    {$description}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;

    }

}