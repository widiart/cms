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
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;
use App\ProductCategory;

class NuvasaAcordionList extends PageBuilderBase
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

        $output .= Text::get([
            'name' => 'id_head',
            'label' => __('Header Identifier'),
            'value' => $widget_saved_values['id_head'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'title',
            'label' => __('Category Group'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Switcher::get([
            'name' => 'is_active',
            'label' => __('Initial Active'),
            'value' => $widget_saved_values['is_active'] ?? null,
        ]);

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_four',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'image',
                    'label' => __('Image')
                ],
            ]
        ]);

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
        $id_head = SanitizeInput::esc_html($settings['id_head']);
        $title = SanitizeInput::esc_html($settings['title']);
        $style = empty($settings['is_active']) ? 'style="display: none;"' : '';
        $unit = str_replace(' ','_',$title);
        $unit = str_replace('|','-',$unit);

        $random = random_int(9999,666666);

        $title_markup = $dropdown_markup = $category_markup = '';
        $this->args['settings'] = RepeaterField::remove_default_fields($settings);
        foreach ($this->args['settings'] as $key => $setting){
            if (is_array($setting)){
                $this->args['repeater'] = $setting;
                $array_lang_item = $setting[array_key_last($setting)];
                if (!empty($array_lang_item) && is_array($array_lang_item) && count($array_lang_item) > 0) {
                    foreach ($array_lang_item as $index => $value) {

                        $category_markup .= $this->render_slider_markup($index); // for multiple array index
                        $this->a++;
                    }
                } else {
                    $category_markup .= $this->render_slider_markup(); // for only one index of array
                }
            }
        }

        $js = <<<JS
            $(document).ready(function () {
                $("#{$id_head}").append( $("#nuvasa-acordion-list-{$random}").html())
                $("#nuvasa-acordion-list-{$random}").html('');
                $('#{$id_head} .elevated_carousel2').owlCarousel({
                    loop: false,
                    nav: true,
                    dots: true,
                    autoHeight: true,
                    // animateOut: 'fadeOut',
                    smartSpeed: 1000,
                    mouseDrag: false,
                    margin:10,
                    items: 1,
                });
            });
        JS;

        return <<<HTML
            <div id="nuvasa-acordion-list-{$random}">
                <div class="col-12 col-md-8 px-3 unit-col {$unit}" {$style} id="nuvasabay">
                    <div class="elevated_carousel2 owl-carousel owl-theme">
                        {$category_markup}
                    </div>
                </div>
            </div>
            <script>
                {$js}
            </script>
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Acordion: 01 (List Only)');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $title = SanitizeInput::esc_html($settings['title']);
        $image = $this->get_repeater_field_value('image', $index);

        if(!empty($image['img_alt'])) {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100');
        } else {
            $image_markup = render_image_markup_by_attachment_id($image,'w-100" alt="'.$title.' '.$index+1);
        }

        return <<<HTML
            <div class="item">
                {$image_markup}
            </div>
        HTML;

    }
}