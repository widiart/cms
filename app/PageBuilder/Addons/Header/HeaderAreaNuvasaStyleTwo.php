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

class HeaderAreaNuvasaStyleTwo extends PageBuilderBase
{
    /**
     * preview_image
     * this method must have to implement by all widget to show a preview image at admin panel so that user know about the design which he want to use
     * @since 1.0.0
     * */
    public function preview_image()
    {
        return 'common/header-nuvasa-style-02.png';
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

        $output .= Text::get([
            'name' => 'maps_url',
            'label' => __('Url When Clicked'),
            'value' => $widget_saved_values['maps_url'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'whatsapp',
            'label' => __('Social Whatsapp'),
            'value' => $widget_saved_values['whatsapp'] ?? null,
            'info' => __('wa.me/62xxx')
        ]);

        $output .= Text::get([
            'name' => 'email',
            'label' => __('Email Contact'),
            'value' => $widget_saved_values['email'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image Header'),
            'value' => $widget_saved_values['image'] ?? null,
            'dimensions' => '1000x650'
        ]);

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
        $url = url("/contact-message");

        $maps_url = SanitizeInput::esc_html($all_settings['maps_url']);
        $whatsapp = SanitizeInput::esc_html($all_settings['whatsapp']);
        $email = SanitizeInput::esc_html($all_settings['email']);
        $margin_top = SanitizeInput::esc_html($all_settings['margin_top']);
        $margin_bottom = SanitizeInput::esc_html($all_settings['margin_bottom']);

        $random = random_int(9999,666666);
        return <<<HTML
            <div id="nuvasabay" style="margin-bottom:{$margin_bottom}px;margin-top:{$margin_top}px">
                <footer class="product-footer">
                    <div class="footer-img">
                        <a href="{$maps_url}" target="_blank"><img src="{$background_image['img_url']}" alt="product-footer" class="product-footer-img"></a>
                    </div>
                    <div class="social-share">
                        <div class="d-none">
                            <a href="{$whatsapp}"><i class="fa-brands fa-whatsapp"></i></a>
                            <button class="border-0 bg-transparent" data-bs-toggle="modal" data-bs-target="#exampleModal"><a><i class="fa-solid fa-mobile"></i></a></button>
                            <a href="mailto:{$email}"><i class="fa-regular fa-envelope"></i></a>
                        </div>
                        <div class="m-0">
                            <p href="#" onClick="socialButton()"><i class="fas fa-chevron-up"></i></p>
                        </div>
                    </div>
                </footer>

            </div>
        HTML;

    }

    /**
     * widget_title
     * this method must have to implement by all widget to register widget title
     * @since 1.0.0
     * */
    public function addon_title()
    {
        return __('Header Area: Nuvasa (Full Width)');
    }



}