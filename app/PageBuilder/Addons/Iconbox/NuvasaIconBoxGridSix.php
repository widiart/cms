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
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;

class NuvasaIconBoxGridSix extends PageBuilderBase
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

        $output .= Switcher::get([
            'name' => 'active',
            'label' => __('Initial Active'),
            'value' => $widget_saved_values['active'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'category',
            'label' => __('Category Group'),
            'value' => $widget_saved_values['category'] ?? null,
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
                    'name' => 'title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'description',
                    'label' => __('Description')
                ],
            ]
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
        $category = SanitizeInput::esc_html($settings['category']);

        $active = !empty($settings['active']) ? 'show active' : '';

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

        return <<<HTML
        <div class="container" id="nuvasabay">
            <div class="row">
                <div class="tab-content">
                    <div class="tab-pane fade {$active}" id="{$category}" role="tabpanel" aria-labelledby="{$category}-tab">
                        <div class="row gx-1">
                            {$category_markup}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Icon Box Grid: 04 (Category Content)');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $url = $this->get_repeater_field_value('url', $index);
        $icon = $this->get_repeater_field_value('icon', $index);
        $image = $this->get_repeater_field_value('image', $index);
        $title = $this->get_repeater_field_value('title', $index);
        $description = $this->get_repeater_field_value('description', $index);

        if(!empty($image['img_alt'])) {
            $image_markup = render_image_markup_by_attachment_id($image,'tabbing-img zoom_img image-hover');
        } else {
            $image_markup = render_image_markup_by_attachment_id($image,'tabbing-img zoom_img image-hover" alt="'.$title.' '.$description);
        }

        return <<<HTML
            <div class="col-lg-4 col-md-6 col-sm-6 col-12 mt-4">
                <div class="img-tab-container position-relative overflow-hidden">
                    {$image_markup}
                    <div class="tabbing-text">
                        <h4>{$title}</h4>
                        <p>{$description}</p>
                    </div>
                </div>
            </div>
        HTML;

    }
}