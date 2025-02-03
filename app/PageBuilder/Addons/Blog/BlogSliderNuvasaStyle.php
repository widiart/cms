<?php


namespace App\PageBuilder\Addons\Blog;
use App\Blog;
use App\BlogCategory;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Number;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\PageBuilderBase;
use Illuminate\Support\Str;

class BlogSliderNuvasaStyle extends PageBuilderBase
{

    /**
     * @inheritDoc
     */
    public function preview_image()
    {
        return 'blog/nuvasa-slider-01.png';
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
                'name' => 'readmore_text_'.$lang->slug,
                'label' => __('Read More Text'),
                'value' => $widget_saved_values['readmore_text_' . $lang->slug] ?? null,
            ]);
            $categories = BlogCategory::where(['status' => 'publish','lang'=>$lang->slug])->get()->pluck('name', 'id')->toArray();
            $output .= Select::get([
                'name' => 'categories_' . $lang->slug,
                'multiple' => true,
                'label' => __('Category'),
                'placeholder' => __('Select Category'),
                'options' => $categories,
                'value' => $widget_saved_values['categories_' . $lang->slug] ?? null,
                'info' => __('you can select category for blog, if you want to show all event leave it empty')
            ]);
            $output .= $this->admin_language_tab_content_end();
        }
        

        $output .= $this->admin_language_tab_end(); //have to end language tab

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
            'name' => 'slider_items',
            'label' => __('Slider Item'),
            'value' => $widget_saved_values['slider_items'] ?? 3,
            'info' => __('enter how many item you want to show in a row of slider'),
        ]);
        $output .= Number::get([
            'name' => 'total_items',
            'label' => __('Total Item'),
            'value' => $widget_saved_values['total_items'] ?? 3,
            'info' => __('enter how many item you want to show in a single page'),
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
        $readmore = SanitizeInput::esc_html($settings['readmore_text_'.$current_lang]);
        $category = $settings['categories'] ?? [];

        $section_title_alignment = SanitizeInput::esc_html($settings['section_title_alignment']);
        $order_by = SanitizeInput::esc_html($settings['order_by']);
        $order = SanitizeInput::esc_html($settings['order']);
        // $items = SanitizeInput::esc_html($settings['items']);
        $slider_items = SanitizeInput::esc_html($settings['slider_items']);
        $total_items = SanitizeInput::esc_html($settings['total_items']);
        $padding_top = SanitizeInput::esc_html($settings['padding_top']);
        $padding_bottom = SanitizeInput::esc_html($settings['padding_bottom']);
        $slider_items = SanitizeInput::esc_html($settings['slider_items']);


        $blogs = Blog::query()->where(['status' => 'publish']);
        if (!empty($category)){
            $blogs->whereIn('blog_categories_id', $category);
        }
        $blogs =$blogs->orderBy($order_by,$order)->paginate($total_items);
        if(!empty($items)){
            $blogs = $blogs->take($items);
        }
        $category_markup = '';

        foreach ($blogs as $item){
            $image = render_image_markup_by_attachment_id($item->image,'','grid');
            $day = date_format($item->created_at,'d');
            $month = date_format($item->created_at,'M');
            $route = route('frontend.blog.single',$item->slug);
            $date = date_format(date_create($item->created_at),'D, d M Y');
            $title = SanitizeInput::esc_html($item->title);
            $excerpt = SanitizeInput::esc_html($item->excerpt);
            
            $category_markup .= <<<HTML
                <div class="item my-2 px-3">
                    <div class="blog_container">
                        <a href="{$route}">
                            <div class="blog_name d-flex flex-column justify-content-center mb-3">
                                <h5 style="min-height:40px">{$title}</h5>
                            </div>
                            <div class="blog_date">
                                <p>
                                    {$date}
                                    <br>
                                </p>
                            </div>
                            <div class="blog_image overflow-hidden">
                                {$image}
                            </div>
                            <div class="blog_desc" style="text-overflow: ellipsis; overflow:hidden">
                                <p style="min-height:63px">{$excerpt}</p>
                            </div>
                        </a>
                        <div class="blog_link">
                            <a class="blog_btn" href="{$route}">{$readmore} <i class="fas fa-arrow-right"></i> </a>
                        </div>
                    </div>
                </div>
            HTML;
        }

        if($section_title_alignment == 'left'):
            $title_markup = '
                        <div class="col-md-8 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center text-md-end p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>
                        <div class="col-md-4 col-12"></div>';
        elseif($section_title_alignment == 'right') :
            $title_markup = '
                        <div class="col-md-4 col-12"></div>
                        <div class="col-md-8 col-12 bg-primary text-md-start text-center py-3 heading">
                            <h2 class="text-white text-center text-md-start p-0 ps-lg-4 ps-md-5">'.$section_title.'
                            </h2>
                        </div>
                        ';
        else:
            $title_markup = '
                        <div class="col-lg-12 col-md-12 col-12 bg-primary text-center py-3 heading">
                            <h2 class="text-white text-center p-0 pe-lg-4 pe-md-5">'.$section_title.'
                            </h2>
                        </div>';
        endif;

        $random = random_int(9999,666666);
        $js = "<script>
        $(document).ready(function () {
        ";
        $js .= <<<JS
            $('#blog_part{$random}').owlCarousel({
                loop: false,
                nav: false,
                dots: true,
                autoHeight: true,
                // animateOut: 'fadeOut',
                smartSpeed: 1000,
                mouseDrag: true,
                margin:10,
                items: 1,
                startPosition: 1,
                responsive: {
                    992: {
                        items: {$slider_items},
                        center: true,
                        dots: false,
                        // autoplay: true,
                        autoplayTimeout: 5000,
                    },
                    768: {
                        items: 2,
                        center: true,
                        nav: true,
                        dots: false,
                        // autoplay: true,
                        autoplayTimeout: 3000,
                    }
                }
            });
        JS;
        $js .= "})</script>";
        return <<<HTML
            <style>
                .blog_image img {
                    height: 240px;
                    width: auto;
                }
            </style>
            <section class="blog_section" style="padding-bottom:{$padding_bottom}px;padding-top:{$padding_top}px">
                <div id="nuvasabay">
                    <div class="container-fluid p-0">
                        <div class="row p-0 position-relative bg-primary-before">
                            {$title_markup}
                        </div>
                    </div>
                    <div class="container aos-init aos-animate" data-aos-duration="1500" data-aos="fade-up">
                        <div id="blog_part{$random}" class="row p-0 mt-4 blog_part elevated_carousel2 owl-carousel owl-theme">
                            {$category_markup}
                        </div>
                    </div>
                </div>
            </section>
            {$js}
        HTML;

    }

    /**
     * @inheritDoc
     */
    public function addon_title()
    {
        return __('Nuvasa Blog Slider: 01');
    }
}