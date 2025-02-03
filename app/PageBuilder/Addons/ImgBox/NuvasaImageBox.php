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

class NuvasaImageBox extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'icon-box/nuvasa-image-box.png';
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
            $output .= Text::get([
                'name' => 'section_subtitle_'.$lang->slug,
                'label' => __('Section Subtitle'),
                'value' => $widget_saved_values['section_subtitle_' . $lang->slug] ?? null,
            ]);
            $output .= Textarea::get([
                'name' => 'section_description_'.$lang->slug,
                'label' => __('Section description'),
                'value' => $widget_saved_values['section_description_' . $lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= Text::get([
            'name' => 'btn_url',
            'label' => __('Maps URL'),
            'value' => $widget_saved_values['btn_url'] ?? null,
        ]);

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);

        // $output .= Select::get([
        //     'name' => 'section_image_alignment',
        //     'label' => __('Section Image Alignment'),
        //     'options' => [
        //         'left' => __('Left'),
        //         'right' => __('Right'),
        //     ],
        //     'value' => $widget_saved_values['section_image_alignment'] ?? null,
        //     'info' => __('set alignment of section image')
        // ]);

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
        $section_description = SanitizeInput::esc_html($settings['section_description_'.$current_lang]);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);
        if(!empty($settings['image']['img_alt'])) {
            $image = render_image_markup_by_attachment_id($settings['image'], '" id="myContent');
        } else {
            $image = render_image_markup_by_attachment_id($settings['image'], '" id="myContent" alt="'.$section_title);
        }

        $random = random_int(9999,666666);

        $btn = '';
        if(!empty($settings['btn_url'])) {
            $btn = <<<HTML
                <a href="{$settings['btn_url']}" class="btn btn-thenove" target="_blank">Gmaps</a>
            HTML;
        }
        $category_markup = '
            <section id="nuvasa-box-grid-'.$random.'" style="padding-bottom:'.$padding_bottom.'px;padding-top:'.$padding_top.'px">
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
                    <div class="container px-0">
                        <div class="row gx-0 d-flex flex-column-reverse flex-md-row" data-aos-duration="1500" data-aos="fade-up">
                            <div class="d-none d-md-block col-md col-12"></div>
                            <div class="col-12 col-md-3 px-3 px-lg-0">
                                <div class="d-flex flex-column h-100 justify-content-center pt-3 pt-lg-0">
                                    <h1 class="lh-sm mb-3 fs-2">
                                        '.$section_subtitle.'
                                    </h1>
                                    <p class="fs-12">
                                        '.$section_description.'
                                    </p>
                                </div>
                            </div>
                            <div class="col-md col-12"></div>
                            <div class="col-12 col-md-6">
                                <div class="position-relative">
                                    <div class="position-absolute d-flex flex-column" style="z-index: 1;bottom: 10px;right: 10px;">
                                        <button id="zoomIn" class="btn btn-thenove align-self-end mb-3" style="width: 40px;height:auto;"><i class="fas fa-search-plus"></i></button>
                                        <button id="zoomOut" class="btn btn-thenove align-self-end mb-3" style="width: 40px;height:auto;"><i class="fas fa-search-minus"></i></button>
                                        '.$btn.'
                                    </div>
                                    <div class="embed-responsive embed-responsive-4by3 rounded bg-secondary" id="image-container">
                                        <div id="myViewport" class="embed-responsive-item">
                                            '.$image.'
                                        </div>
                                    </div>
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
        return __('Nuvasa Box Image: 01');
    }

}