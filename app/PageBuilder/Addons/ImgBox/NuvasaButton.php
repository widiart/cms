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
use App\FileUpload;

class NuvasaButton extends PageBuilderBase
{
    protected $a = 1;
    protected $first_item = '';
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/button-01.png';
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

        $videos = FileUpload::where(['status' => 'publish'])->get()->pluck('title','id')->toArray();
        $output .= Select::get([
            'name' => 'file_id',
            'multiple' => true,
            'label' => __('File Attachment Brochure'),
            'placeholder' =>  __('Select File'),
            'options' => $videos,
            'value' => $widget_saved_values['file_id'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'url',
            'label' => __('Link Button E-Catalogue'),
            'value' => $widget_saved_values['url'] ?? null,
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
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $file_url = asset(SanitizeInput::esc_url(FileUpload::where('id',$settings['file_id'])->first()->local_file ?? 'not_found'));
        $url = SanitizeInput::esc_html($settings['url']);
        $random = random_int(9999,666666);
        return <<<HTML
            <style>
                @media (max-width: 991px) {
                    #nuvasa-button-{$random} .login-btn{
                        max-width:100%;
                    }
                    #nuvasa-button-{$random} .container-login{
                        padding: 0px 20px;
                    }
                }
            </style>
            <section id="nuvasa-button-{$random}" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mb-3 text-center" id="nuvasabay">
                    <div class="container-login">
                        <a class="login-btn spec-btn-200 d-block py-2" style="letter-spacing: 1px;" target="_blank" href="{$file_url}">GET BROCHURE</a>
                    </div>
                    <div class="container-login">
                        <a class="login-btn spec-btn-200 d-block py-2" style="letter-spacing: 1px;" target="_blank" href="{$url}">E-CATALOG</a>
                    </div>
                </div>
            </section>
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Button: 01');
    }

}