<?php


namespace App\PageBuilder\Addons\Iconbox;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;

class NuvasaIconBoxGridFive extends PageBuilderBase
{
    protected $a = 1;
    use RepeaterHelper;
    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'service/category-grid-03.png';
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

        $output .= Text::get([
            'name' => 'section_title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['section_title'] ?? null,
        ]);
        $output .= Image::get([
            'name' => 'section_image',
            'label' => __('Section Image'),
            'value' => $widget_saved_values['section_image'] ?? null,
        ]);

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_five',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'id',
                    'label' => __('Category')
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
        $section_title = SanitizeInput::esc_html($settings['section_title']);
        $section_image = render_image_markup_by_attachment_id($settings['section_image'],'Developer_globe');
        
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);

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

        $random = random_int(9999,666666);
        return <<<HTML
            <section id="property-group" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div id="nuvasabay" class="py-3">
                    <div class="container-fluid bg-primary">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="text-center property-heading mt-3 mb-3" style="font-size: 20px !important;">{$section_title}</h2>
                                </div>
                                <div class="col-lg-4 col-md-12 col-sm-12 tabbing-map">
                                    {$section_image}
                                </div>
                                <div class="col-lg-8 col-md-12 col-sm-12 tabbing-main">
                                    <ul class="nav nav-tabs property-tab py-md-0 pb-5" id="myTab" role="tablist">
                                        <div class="owl-carousel">
                                            {$category_markup}
                                        </div>
                                    </ul>
                                </div>
                            </div>
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
        return __('Nuvasa Icon Box Grid: 04 (Category Header)');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $image = $this->get_repeater_field_value('image', $index);
        $image_markup = render_image_markup_by_attachment_id($image,'Indonesia');
        $id = $this->get_repeater_field_value('id', $index);

        if($this->a == 1) {
            $status = 'active';
            $this->a++;
        } else {
            $status = '';
        }

        return <<<HTML
            <li class="nav-item" role="presentation">
                <button class="nav-link {$status}" id="{$id}-tab" data-bs-toggle="tab" data-bs-target="#{$id}" type="button" role="tab" aria-controls="{$id}" aria-selected="true">
                    {$image_markup}
                </button>
            </li>
        HTML;

    }
}