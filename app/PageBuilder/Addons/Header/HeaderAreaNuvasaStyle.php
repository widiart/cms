<?php


namespace App\PageBuilder\Addons\Header;


use App\VideoUpload;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;

class HeaderAreaNuvasaStyle extends PageBuilderBase
{
    /**
     * preview_image
     * this method must have to implement by all widget to show a preview image at admin panel so that user know about the design which he want to use
     * @since 1.0.0
     * */
    public function preview_image()
    {
        return 'common/header-nuvasa-style.png';
    }

    /**
     * admin_render
     * this method must have to implement by all widget to render admin panel widget content
     * @since 1.0.0
     * */
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();
        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image Header'),
            'value' => $widget_saved_values['image'] ?? null,
            'dimensions' => '1000x650'
        ]);

        $output .= $this->admin_language_tab_content_end();

        $output .= Image::get([
            'name' => 'image_mobile',
            'label' => __('Image Mobile View'),
            'value' => $widget_saved_values['image_mobile'] ?? null,
            'dimensions' => '510x510'
        ]);

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = LanguageHelper::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);

            $output .= Text::get([
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_' . $lang->slug] ?? null,
            ]);
            $output .= Summernote::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Slider::get([
            'name' => 'margin_top',
            'label' => __('Margin Top'),
            'value' => $widget_saved_values['margin_top'] ?? 20,
            'max' => 200,
        ]);
        $output .= Slider::get([
            'name' => 'margin_bottom',
            'label' => __('Margin Bottom'),
            'value' => $widget_saved_values['margin_bottom'] ?? 20,
            'max' => 200,
        ]);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    /**
     * frontend_render
     * this method must have to implement by all widget to render frontend widget content
     * @since 1.0.0
     * */
    public function frontend_render(): string
    {
        $all_settings = $this->get_settings();
        $current_lang = LanguageHelper::user_lang_slug();
        // $background_image = render_background_image_markup_by_attachment_id($all_settings['image']);
        $background_image = get_attachment_image_by_id($all_settings['image']);
        $background_mobile = get_attachment_image_by_id($all_settings['image_mobile']??'');
        $title = SanitizeInput::esc_html($all_settings['title_'.$current_lang]);
        $description = SanitizeInput::kses_basic($all_settings['description_'.$current_lang]);

        $margin_top = SanitizeInput::esc_html($all_settings['margin_top']);
        $margin_bottom = SanitizeInput::esc_html($all_settings['margin_bottom']);

        $title_markup = '';
        $style = '';
        $random = random_int(9999,666666);

        if(!empty($title)) {
            $title_markup .= <<<HTML
                <div class="col-12 col-md-8 col-lg-7 d-flex justify-content-center">
                    <div class="new-face-content d-flex justify-content-center flex-column" data-aos="fade-in" data-aos-duration="1500">
                        <h3 class="new-face-box-title">{$title}</h3>
                        <div class="new-face-box-desc">{$description}</div>
                    </div>
                </div>
                <div class="col-12 col-md-4 col-lg-7"></div>
            HTML;
        } else {
            $mobile_img = !empty($background_mobile)? "background-image: url('{$background_mobile['img_url']}');":"";
            $style .= <<<CSS
            @media (max-width: 991px) {
                #banner_section{$random} {
                    $mobile_img
                    height: 100vw;
                    background-size: contain;
                    background-position: top;
                }   
            }
            CSS;
        }

        return <<<HTML
            <style>
            #banner_section{$random} {
                background-image: url('{$background_image['img_url']}');
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center;
                width: 100%;
                height: calc(100vh - 86px);
                margin: 0px 0px 40px;
            }
            #banner_section{$random} #nuvasabay p {
                color: var(--nuvasabay-third-color);
                font-weight: 600;
            }
            {$style}
            </style>
            <section id="banner_section{$random}"  style="margin-bottom:{$margin_bottom}px;margin-top:{$margin_top}px">
                <div class="container-fluid p-0">
                    <div class="banner position-relative">
                        <div class="banner_area">
                            <div class="container">
                                <div class="row" id="nuvasabay">
                                    {$title_markup}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            HTML;

    }

    /**
     * widget_title
     * this method must have to implement by all widget to register widget title
     * @since 1.0.0
     * */
    public function addon_title()
    {
        return __('Header Area: Nuvasa');
    }



}