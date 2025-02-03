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
use App\FileUpload;

class NuvasaTabList extends PageBuilderBase
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
            'name' => 'title',
            'label' => __('List Title'),
            'value' => $widget_saved_values['title'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'group',
            'label' => __('Category Group'),
            'value' => $widget_saved_values['group'] ?? null,
        ]);

        $output .= Switcher::get([
            'name' => 'is_active',
            'label' => __('Initial Active'),
            'value' => $widget_saved_values['is_active'] ?? null,
        ]);

        $file = FileUpload::where(['status' => 'publish'])->get()->pluck('title','id')->toArray();

        $output .= Repeater::get([
            'multi_lang' => false,
            'settings' => $widget_saved_values,
            'id' => 'image_box_grid_four',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'label',
                    'label' => __('Label')
                ],
                [
                    'type' => RepeaterField::SELECT,
                    'name' => 'file',
                    'options' => $file,
                    'label' => __('File')
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
        $title = SanitizeInput::esc_html($settings['title']);
        $group = SanitizeInput::esc_html($settings['group']);
        $active = !empty($settings['is_active']) ? 'active' : '';
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

        return <<<HTML
        <style>
            .nuvasa-tab-list-{$random} .fa,
            .nuvasa-tab-list-{$random} .fas,
            .nuvasa-tab-list-{$random} .fa-solid {
                color: var(--nuvasabay-primary-color);
            }
            .nuvasa-tab-list-{$random} .list {
                border-bottom: 1px solid;
                border-color: var(--nuvasabay-primary-color);
            }
            .text-nuvasa {
                color: var(--nuvasabay-primary-color);
                border-bottom: 1px solid white;
            }
            .text-nuvasa:hover {
                color: var(--nuvasabay-primary-color);
                border-bottom: 1px solid var(--nuvasabay-primary-color);
            }
        </style>
            <section class="container nuvasa-tab-list-{$random}">    
                <div class="tab-content my-3" id="nav-tabContent">
                    <div class="tab-pane fade show {$active}" id="nav-{$group}" role="tabpanel" aria-labelledby="nav-{$group}-tab">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <h2 class="my-3">{$title}</h2>
                                </div>
                                {$category_markup}
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
        return __('Nuvasa Tab: List');
    }

    private function render_slider_markup(int $index)
    {
        $settings = $this->get_settings();

        $label = $this->get_repeater_field_value('label', $index);
        $file = $this->get_repeater_field_value('file', $index);
        $file_url = route('frontend.download.pdf',$file);
        $fileupload = FileUpload::where('id',$file)->first();
        $date = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fileupload->updated_at)->locale('id_ID')->isoFormat('Do MMMM YYYY');
        $view_url = asset(SanitizeInput::esc_url($fileupload->local_file ?? 'not_found'));

        return <<<HTML
            <div class="col-12 col-md-6">
                <div class="container list">
                    <div class="d-flex">
                        <h1 class="p-3">
                            <i class="fa-solid fa-file-pdf fa-lg"></i>
                        </h1>
                        <div class="p-2 w-100">
                            <h4 for="">{$label}</h4>
                            <div class="d-flex justify-content-between">
                                <h5 class="text-secondary">{$date}</h5>
                                <div>
                                    <a class="py-2 mx-2 text-nuvasa" style="letter-spacing: 1px;" target="_blank" href="{$view_url}">View</a> 
                                    <a class="py-2 mx-2 text-nuvasa" style="letter-spacing: 1px;" target="_blank" href="{$file_url}">Download</a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        HTML;

    }
}