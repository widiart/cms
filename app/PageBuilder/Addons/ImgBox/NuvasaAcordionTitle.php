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

class NuvasaAcordionTitle extends PageBuilderBase
{
    protected $a = 1;
    protected $first_item = '';
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/nuvasa-accordion.png';
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

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Text::get([
            'name' => 'id_head',
            'label' => __('Identifier'),
            'value' => $widget_saved_values['id_head'] ?? null,
            'info' => __('For connection with list only widget')
        ]);

        $output .= Image::get([
            'name' => 'cover',
            'label' => __('Cover Image'),
            'value' => $widget_saved_values['cover'] ?? null,
        ]);

        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_four',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'title',
                    'label' => __('Title')
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
        $id_head = SanitizeInput::esc_html($settings['id_head']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $image_markup = render_image_markup_by_attachment_id($settings['cover'],'overflow-hidden" style="object-fit: cover; overflow: hidden;height:205px;width:100%');

        $random = random_int(9999,666666);
        $style = <<<HTML
            <style>
                #nuvasa-box-grid-two-{$random} .desc p {
                    color: var(--nuvasabay-third-color);
                    line-height: 1.5em;
                }
                #nuvasa-box-grid-two-{$random} h3 {
                    color: var(--nuvasabay-third-color);
                    font-size: 20px;
                }
                #nuvasa-box-grid-two-{$random} .desc ul {
                    line-height: 1.5em;
                }
                @media (max-width: 767px) {
                    #nuvasa-acordion-{$random} .elevated_carousel2 .owl-nav button.owl-next,
                    #nuvasa-acordion-{$random} .elevated_carousel2 .owl-nav button.owl-prev {
                        top: 96px !important;
                    }
                }
            </style>
        HTML;

        $title_markup = $dropdown_markup = $category_markup = '';
        $this->args['settings'] = RepeaterField::remove_default_fields($settings);
        foreach ($this->args['settings'] as $key => $setting){
            if (is_array($setting)){
                $this->args['repeater'] = $setting;
                $array_lang_item = $setting[array_key_last($setting)];
                if (!empty($array_lang_item) && is_array($array_lang_item) && count($array_lang_item) > 0) {
                    foreach ($array_lang_item as $index => $value) {

                        $title_markup .= $this->render_slider_markup_title($index); // for multiple array index
                        $dropdown_markup .= $this->render_slider_markup_dropdown($index); // for multiple array index
                        $this->a++;
                    }
                } else {
                    $title_markup .= $this->render_slider_markup_title(); // for only one index of array
                    $dropdown_markup .= $this->render_slider_markup_dropdown(); // for only one index of array
                }
            }
        }

        $js = <<<JS
            $(document).ready(function () {
                $(document).find("#nuvasa-acordion-{$random} a.unit-btn").click(function () {
                    $(document).find('#nuvasa-acordion-{$random} .unit-btn').removeClass('active');
                    $(this).addClass('active');

                    $(document).find('#nuvasa-acordion-{$random} .unit-col').hide();
                    $(document).find('#nuvasa-acordion-{$random} .' + $(this).data('unit')).show();

                    $(document).find('#nuvasa-acordion-{$random} .plan-title').text($(this).data('title'));
                    $(document).find("#nuvasa-acordion-{$random} #btn-label").html($(this).data('title'));
                });
            });
        JS;

        return <<<HTML
            {$style}
            <section id="nuvasa-acordion-{$random}" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div class="container-fluid gx-0 pb-3" id="nuvasabay">
                    <div class="position-relative">
                        {$image_markup}
                        <div class="position-absolute bottom-0 w-100">
                            <div class="text-center">
                                <h2 id="unit-title" class="fs-30 py-3 my-0 banner-bg" style="color: white;"> 
                                    {$section_title} 
                                    <span class="plan-title" style="font-weight:500;">{$this->first_item}</span>
                                </h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container px-0" data-aos-duration="1500" data-aos="fade-up">
                    <div class="row unit gx-0 gx-lg-5 gy-3" id="{$id_head}">
                        <div class="col-12 col-md-3 px-3 px-lg-0">
                            <div class="text-lg-end text-start unit-menu">
                                <div class="flex-column d-none d-md-flex">
                                    {$title_markup}
                                </div>
                                <div class="d-block d-md-none">
                                    <div class="container-fluid">
                                        <div class="dropdown text-center row" id="plan-unit">
                                            <button class="btn btn-block btn-thenove dropdown-toggle" id="btn-label" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                {$this->first_item}
                                            </button>
                                            <ul class="dropdown-menu w-100">
                                                {$dropdown_markup}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-none d-md-block col-md col-12 border-end border-2"></div>
                        {$category_markup}
                        <div class="col-md col-12"></div>
                    </div>
                </div>
                <script>
                    {$js}
                </script>
            </section>
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Acordion: 01 (Title Only)');
    }

    private function render_slider_markup_title(int $index)
    {
        $settings = $this->get_settings();

        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $active = $this->a == 1 ? 'active' : '';
        $this->first_item = $this->a == 1 ? $title : $this->first_item;
        $unit = str_replace(' ','_',$title);
        $unit = str_replace('|','-',$unit);

        return <<<HTML
            <a data-unit="{$unit}" data-title="{$title}" class="text-decoration-none text-dark fs-16 fs-sm-12 py-2 fw-bold unit-btn {$active}" style="cursor: pointer;">
                {$title}
            </a>
        HTML;

    }

    private function render_slider_markup_dropdown(int $index)
    {
        $settings = $this->get_settings();

        $title = $this->get_repeater_field_value('title', $index, LanguageHelper::user_lang_slug());
        $active = $this->a == 1 ? 'active' : '';
        $unit = str_replace(' ','_',$title);
        $unit = str_replace('|','-',$unit);

        return <<<HTML
            <li>
                <a data-unit="{$unit}" data-title="{$title}" class="dropdown-item text-decoration-none text-dark fs-16 fs-sm-12 py-2 fw-bold unit-btn {$active}" style="cursor: pointer;">
                    {$title}
                </a>
            </li>
        HTML;

    }

}