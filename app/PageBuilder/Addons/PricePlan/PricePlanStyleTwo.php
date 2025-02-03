<?php


namespace App\PageBuilder\Addons\PricePlan;
use App\Blog;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\ColorPicker;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Summernote;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use App\PricePlan;
use App\PricePlanCategory;
use App\ProductCategory;
use App\Testimonial;

class PricePlanStyleTwo extends PageBuilderBase
{

    /**
     * @inheritDoc
     */
    public function preview_image()
    {
       return 'price-plan/slider-02.png';
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
            $output .= Summernote::get([
                'name' => 'section_description_'.$lang->slug,
                'label' => __('Section Description'),
                'value' => $widget_saved_values['section_description_' . $lang->slug] ?? null,
            ]);
            $categories = PricePlanCategory::where(['status' => 'publish','lang' => $lang->slug])->get()->pluck('name', 'id')->toArray();
            $output .= NiceSelect::get([
                'name' => 'categories_'.$lang->slug,
                'multiple' => true,
                'label' => __('Category'),
                'placeholder' => __('Select Category'),
                'options' => $categories,
                'value' => $widget_saved_values['categories_'.$lang->slug] ?? null,
                'info' => __('you can select category for price plan, if you want to show all price plan leave it empty')
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab
        $output .= Select::get([
            'name' => 'section_title_content_alignment',
            'label' => __('Section Title Content Alignment'),
            'options' => [
                'justify-content-start' => __('Left Align'),
                'justify-content-center' => __('Center Align'),
                'justify-content-end' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_content_alignment'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'section_title_alignment',
            'label' => __('Section Title Alignment'),
            'options' => [
                'left-align' => __('Left Align'),
                'center-align' => __('Center Align'),
                'right-align' => __('Right Align'),
            ],
            'value' => $widget_saved_values['section_title_alignment'] ?? null,
            'info' => __('set alignment of section title')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);
        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Number::get([
            'name' => 'slider_items',
            'label' => __('Slider Item'),
            'value' => $widget_saved_values['slider_items'] ?? 3,
            'info' => __('enter how many item you want to show in a row of slider'),
        ]);

        $output .= Image::get([
            'name' => 'background_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['background_image'] ?? null,
        ]);

        $output .= ColorPicker::get([
            'name' => 'title_color',
            'label' => __('Title Color'),
            'value' => $widget_saved_values['title_color'] ?? null,
        ]);
        $output .= ColorPicker::get([
            'name' => 'description_color',
            'label' => __('Description Color'),
            'value' => $widget_saved_values['description_color'] ?? null,
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
        $section_description = SanitizeInput::esc_html($settings['section_description_'.$current_lang]);
        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);
        $section_title_content_alignment = SanitizeInput::esc_html($settings['section_title_content_alignment']);
        $order_by = SanitizeInput::esc_html($settings['order_by']);
        $order = SanitizeInput::esc_html($settings['order']);
        $items = SanitizeInput::esc_html($settings['items']);
        $slider_items = SanitizeInput::esc_html($settings['slider_items']);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $background_image = SanitizeInput::esc_html($settings['background_image']);
        $title_color = SanitizeInput::esc_html($settings['title_color'] ?? '');
        $title_color_markup = !empty($title_color) ? 'color:'.$title_color.';' : '';
        $description_color = SanitizeInput::esc_html($settings['description_color'] ?? '');
        $description_color_markup = !empty($description_color) ? 'color:'.$description_color.';' : '';
        $background_image = render_background_image_markup_by_attachment_id($background_image);
        $category = $settings['categories_'.$current_lang] ?? [];

        $price_plan = PricePlan::query()->where(['lang' => $current_lang,'status' => 'publish']);
        if (!empty($category)){
            $price_plan->whereIn('categories_id', $category);
        }
        $price_plan = $price_plan->orderBy($order_by,$order)->get();
        if(!empty($items)){
            $price_plan = $price_plan->take($items);
        }
        $category_markup = '';

        foreach ($price_plan as $plan){
            $highlight = $plan->highlight ? 'style-01 active' : '';
            $title = $plan->title;
            $price = amount_with_currency_symbol($plan->price);
            $type = $plan->type;
            $feature_list = '';
            $features = explode("\n",$plan->features);
            foreach($features as $item){
                $feature_list .= ' <li>'.$item.'</li>';
            }

            $button_markup = '';
            $url = !empty($plan->url_status) ? route('frontend.plan.order',['id' => $plan->id]) : $plan->btn_url;
            if (!empty($url)){
                $button_markup .= '<a href="'.$url.'" class="boxed-btn">'.$plan->btn_text.'</a>';
            }


            $category_markup .= <<<HTML
<div class="single-price-plan-01 {$highlight}">
    <div class="price-header">
        <div class="name-box">
            <h4 class="name">{$title}</h4>
        </div>
        <div class="price-wrap">
            <span class="price">{$price}</span><span class="month">{$type}</span>
        </div>
    </div>
    <div class="price-body">
        <ul>
           {$feature_list}
        </ul>
    </div>
    <div class="btn-wrapper">
       {$button_markup}
    </div>
</div>
HTML;
        }
        $section_title_markup = '';
        if (!empty($section_title)){

            $section_title_markup .= <<<HTML
<div class="row {$section_title_content_alignment}">
    <div class="col-lg-8">
        <div class="section-title desktop-center margin-bottom-60 {$section_title_alignment}">
            <h2 class="title" style="{$title_color_markup}">{$section_title}</h2>
            <div class="description" style="{$description_color_markup}">{$section_description}</div>
        </div>
    </div>
</div>
HTML;
        }
        return <<<HTML
<div class="pricing-plan-area bg-liteblue price-inner" data-padding-top="{$padding_top}" data-padding-bottom="{$padding_bottom}" {$background_image}>
    <div class="container">
        {$section_title_markup}
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial-carousel-area margin-top-10">
                    <div class="global-carousel-init price-plan-slider logistic-dots"
                         data-loop="true"
                         data-desktopitem="{$slider_items}"
                         data-mobileitem="1"
                         data-tabletitem="1"
                         data-dots="false"
                         data-nav="true"
                         data-autoplay="true"
                         data-margin="30"
                    >
                    {$category_markup}
                    </div>
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
        return __('Price Plan Slider: 02');
    }
}