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
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;

class HeaderVideoAreaNuvasaStyle extends PageBuilderBase
{
    /**
     * preview_image
     * this method must have to implement by all widget to show a preview image at admin panel so that user know about the design which he want to use
     * @since 1.0.0
     * */
    public function preview_image()
    {
        return 'common/header-video-nuvasa-style.png';
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

        $all_languages = LanguageHelper::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $videos = VideoUpload::where(['status' => 'publish'])->get()->pluck('title','id')->toArray();
        $output .= Select::get([
            'name' => 'video_id',
            'multiple' => true,
            'label' => __('Video File'),
            'placeholder' =>  __('Select Video'),
            'options' => $videos,
            'value' => $widget_saved_values['video_id'] ?? null,
            'info' => __('you can select category for case study, if you want to show all case study leave it empty')
        ]);
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 225,
            'max' => 500,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 320,
            'max' => 500,
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
        $video_url = asset(SanitizeInput::esc_url(VideoUpload::where('id',$all_settings['video_id'])->first()->local_file));

        $padding_top = SanitizeInput::esc_html($all_settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($all_settings['padding_bottom']);

        $random = random_int(9999,666666);
        return <<<HTML
            <style>
                #banner_video{$random} {
                    object-fit: fill !important;
                }
            </style>
            <section id="banner_section{$random}" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div class="container-fluid p-0" id="nuvasabay">
                    <div class="banner position-relative">
                        <div class="banner_area">
                            <video muted="" loop="" id="banner_video{$random}" controls="" autoplay="" class="banner_video_index" width="100%" height="100%" __idm_id__="98729985">
                            <source src="{$video_url}" type="video/mp4">
                            Your browser does not support the video tag.
                            </video>
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
        return __('Header Video Area: Nuvasa');
    }



}