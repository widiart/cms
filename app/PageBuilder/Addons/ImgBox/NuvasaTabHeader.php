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

class NuvasaTabHeader extends PageBuilderBase
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

        $all_languages = LanguageHelper::all_languages();
        $output .= Text::get([
            'name' => 'section_title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['section_title'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'cover',
            'label' => __('Cover Image'),
            'value' => $widget_saved_values['cover'] ?? null,
        ]);

        $output .= <<<HTML
            <style>
                .tambah_atas {
                    display: inline-block;
                    height: 30px;
                    width: 30px;
                    background-color: #339e4b;
                    line-height: 30px;
                    text-align: center;
                    border-radius: 2px;
                    font-weight: 700;
                    color: #fff;
                    margin-bottom: 5px;
                    cursor: pointer;
                }
            </style>
            <div class="text-center">
                <span class="tambah_atas add" onClick="add_atas()"><i class="ti-plus"></i></span>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", () => {
                    $(document).on("keyup","#repeater_image_box_grid_four input",function(){
                        let isi = $(this).val();
                        $(this).attr("value",isi)
                    })
                });
                function add_atas() {
                    let container = $(document).find("#repeater_image_box_grid_four")
                    let baru = container.find('.all-field-wrap').first()
                    let isi = container.html()
                    container.html('')
                    container.append(baru)
                    container.append(isi)
                }
            </script>
            <div id="repeater_image_box_grid_four">
        HTML;
        $output .= Repeater::get([
            'multi_lang' => false,
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

        $output .= "</div>";
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
        $section_title = SanitizeInput::esc_html($settings['section_title']);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
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
                        $category_markup .= $this->render_slider_markup($index); // for multiple array index
                        $this->a++;
                    }
                } else {
                    $title_markup .= $this->render_slider_markup_title(); // for only one index of array
                    $dropdown_markup .= $this->render_slider_markup_dropdown(); // for only one index of array
                    $category_markup .= $this->render_slider_markup(); // for only one index of array
                }
            }
        }

        return <<<HTML
            <style>
                .banner-bg {
                    background-color: var(--nuvasabay-primary-color);
                    /* opacity: 70%; */
                }
                #nuvasabay .nav-tabs .nav-link:focus,
                #nuvasabay .nav-tabs .nav-link:disabled,
                #nuvasabay .nav-tabs .nav-link:active,
                #nuvasabay .nav-tabs .nav-link:hover {
                    border: none;
                }
                #nuvasabay .nav-tabs .nav-link {
                    border: none;
                    color: var(--nuvasabay-third-color);
                }
                #nuvasabay .nav-tabs .nav-link:hover {
                    color: var(--nuvasabay-third-color);
                }
                #nuvasabay .nav-tabs .nav-link.active {
                    border: none;
                    color: var(--nuvasabay-primary-color);
                    border-bottom: 3px solid;
                    border-color: var(--nuvasabay-primary-color);
                }
                #nuvasabay .nav-tabs {
                    border-bottom: 1px solid !important;
                    border-color: var(--nuvasabay-primary-color) !important;
                }
                #nuvasabay .tab-content h2 {
                    color: var(--nuvasabay-third-color);
                }
            </style>
            <section id="nuvasa-tab-{$random}" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div class="container-fluid gx-0 pb-3" id="nuvasabay">
                    <div class="position-relative">
                        {$image_markup}
                        <div class="position-absolute bottom-0 w-100">
                            <div class="text-center">
                                <h2 id="unit-title" class="fs-30 py-3 my-0 banner-bg" style="color: white;"> 
                                    {$section_title} 
                                    <span class="plan-title d-none" style="font-weight:500;">{$this->first_item}</span>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="container pt-3">
                        <div class="nav nav-tabs" role="tablist">
                            {$title_markup}
                        </div>
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
        return __('Nuvasa Tab: Header');
    }

    private function render_slider_markup_title(int $index)
    {
        $settings = $this->get_settings();

        $title = $this->get_repeater_field_value('title', $index);
        $active = $this->a == 1 ? 'active' : '';
        $unit = str_replace(' ','_',$title);
        $unit = str_replace('|','-',$unit);

        return <<<HTML
            <button class="nav-link {$active}" data-bs-toggle="tab" data-bs-target="#nav-{$unit}" type="button" role="tab" aria-controls="nav-{$unit}" aria-selected="true">
                {$title}
            </button>
        HTML;

    }

    private function render_slider_markup_dropdown(int $index)
    {
        $settings = $this->get_settings();

        $title = $this->get_repeater_field_value('title', $index);
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

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $image = $this->get_repeater_field_value('image', $index);
        $image_markup = render_image_markup_by_attachment_id($image,'w-100');
        $title = $this->get_repeater_field_value('title', $index);
        $this->first_item = $this->a == 1 ? $title : $this->first_item;
        $style = $this->a != 1 ? 'style="display: none;"' : '';
        $unit = str_replace(' ','_',$title);
        $unit = str_replace('|','-',$unit);

        return <<<HTML
            <div class="col-12 col-md-8 px-3 unit-col {$unit}" {$style}>
                {$image_markup}
            </div>
        HTML;

    }
}