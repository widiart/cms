<?php


namespace App\PageBuilder\Addons\HeaderSlider;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;

class HeaderAreaNuvasaStyleTwo extends PageBuilderBase
{
    use RepeaterHelper;

    public function preview_image()
    {
        return 'nuvasa-slider-02.png';
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
        
        $output .= Text::get([
            'name' => 'main_subtitle',
            'label' => __('Main Subtitle'),
            'value' => $widget_saved_values['main_subtitle'] ?? null,
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
                    'type' => RepeaterField::TEXT,
                    'name' => 'link',
                    'label' => __('Link Target'),
                    'info' => __('example : https://sinarmasland.com')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image'),
                    'dimensions' => '1288x502px'
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

        $random = random_int(9999,666666);
        $output = '
            <section id="evalute_section'.$random.'" style="padding-bottom:'.$padding_bottom.'px;padding-top:'.$padding_top.'px">
                <div class="container p-0" id="nuvasabay" data-aos-duration="1500" data-aos="fade-up">
                    <div class="heading text-center">
                        <h2 class="mb-2 elevated-mobile">'.$all_settings['main_title'].'</h2>
                        <h4 class="elevated-mobile-2" style="font-size: 22px">'.$all_settings['main_subtitle'].'</h4>
                    </div>
                    <div class="carousel">
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
                loop: true,
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
        return __('Nuvasa Slider: 02 (full)');
    }

    private function render_slider_markup(int $index = null): string
    {
        $settings = $this->get_settings();

        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $subtitle = $this->get_repeater_field_value('desc', $index, LanguageHelper::user_lang_slug());
        $link = $this->get_repeater_field_value('link', $index, LanguageHelper::user_lang_slug());

        $img = $this->get_repeater_field_value('image', $index, LanguageHelper::user_lang_slug());

        if(!empty($img['img_alt'])) {
            $image = render_image_markup_by_attachment_id($img);
        } else if(!empty($title)) {
            $image = render_image_markup_by_attachment_id($img,'" alt="'.$title);
        } else {
            $image = render_image_markup_by_attachment_id($img,'" alt="'.$settings['main_title']);
        }
        
        $settings = $this->get_settings();

        return <<<HTML
                <div class="item px-3">
                    <div class="island_container">
                        <div class="row p-0 m-0 g-0">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <a target="_blank" href="{$link}">
                                    {$image}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
        HTML;

    }

}