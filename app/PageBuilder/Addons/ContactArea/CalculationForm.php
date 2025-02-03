<?php


namespace App\PageBuilder\Addons\ContactArea;


use App\FormBuilder;
use App\VideoUpload;
use App\Helpers\FormBuilderCustom;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\PageBuilder\Fields\NiceSelect;
use App\PageBuilder\Fields\Select;
use App\PageBuilder\Fields\IconPicker;
use App\PageBuilder\Fields\Image;
use App\PageBuilder\Fields\Repeater;
use App\PageBuilder\Fields\Slider;
use App\PageBuilder\Fields\Text;
use App\PageBuilder\Fields\Switcher;
use App\PageBuilder\Fields\Textarea;
use App\PageBuilder\Helpers\RepeaterField;
use App\PageBuilder\Helpers\Traits\RepeaterHelper;
use App\PageBuilder\PageBuilderBase;

class CalculationForm extends PageBuilderBase
{
    private $a = 0;
    private $class = ['col-12 col-md-4','col-12 col-md-4','col-12 col-md-4','col-12'];
    /**
     * preview_image
     * this method must have to implement by all widget to show a preview image at admin panel so that user know about the design which he want to use
     * @since 1.0.0
     * */
    public function preview_image()
    {
        return 'contact-area/nuvasa.png';
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
            'name' => 'title',
            'label' => __('Section Title'),
            'value' => $widget_saved_values['title'] ?? null,
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

        $title = SanitizeInput::esc_html($all_settings['title']);
        $margin_top = SanitizeInput::esc_html($all_settings['margin_top']);
        $margin_bottom = SanitizeInput::esc_html($all_settings['margin_bottom']);

        $random = random_int(9999,666666);
        $script = <<<HTML
            <script>
                function calculate() {
                    let loan = $("#loan").val()
                    let dp = $("#dp").val()
                    let term = $("#term").val()
                    let interest = $("#interest").val()

                    let calculation = (loan - dp) * interest / (term * 12);
                    $("#calculation").val(Intl.NumberFormat('en-US').format(calculation) + ' / month')
                }
            </script>
        HTML;
        
        return <<<HTML
        <section id="calculate-here" style="margin-bottom:{$margin_bottom}px;margin-top:{$margin_top}px">
            <div class="container-fluid p-0" id="nuvasabay">
                <div class="w-100 bg-primary text-center py-3 mb-4">
                    <h2 class="text-white text-nowrap">{$title}</h2>
                </div>
                <div class="container" data-aos-duration="1500" data-aos="fade-up">
                    <div class="mb-3 text-center">
                        <h1 class="fs-4 lh-sm" style="color: black !important;">MAKE IT YOURS!</h1>
                        <h1 class="fs-4 lh-sm mb-3" style="color: black !important;">Financing Available</h1>
                        <p class="fs-12 fw-bold mb-0">Sinarmas Land is the largest and most diversed property developer in Indonesia.</p>
                    </div>
                    <div class="row g-3 g-lg-5 justify-content-between">
                        <div class="col-12 form_append">
                            <div class="row d-flex mx-0 gx-4 gy-2 form">
                                <form action="" method="post" id="custom_form_builder_{$random}" class="custom-form-builder-form" enctype="multipart/form-data">
                                    <div class="error-message"></div>
                                    <div class="row">
                                        <div class="col-12 col-md-3 offset-md-3 form-group"> 
                                            <label for="loan">Loan Amount/ Property Price</label> 
                                            <input type="text" id="loan" name="loan" class="form-control" placeholder="Enter Loan Amount/ Property Price">
                                        </div>
                                        <div class="col-12 col-md-3 form-group"> 
                                            <label for="dp">Down Payment</label> 
                                            <input type="text" id="dp" name="dp" class="form-control" placeholder="Enter Down Payment Amount">
                                        </div>
                                        <div class="col-12 col-md-3 offset-md-3 form-group"> 
                                            <label for="term">Loan Term (Year)</label> 
                                            <input type="text" id="term" name="term" class="form-control" placeholder="Enter Loan Term">
                                        </div>
                                        <div class="col-12 col-md-3 form-group"> 
                                            <label for="interest">Interest Rate</label> 
                                            <input type="text" id="interest" name="interest" class="form-control" placeholder="Enter Interest Rate">
                                        </div>
                                        <div class="col-12 col-md-4 offset-md-4 form-group text-center"> 
                                            <label for="calculation">Calculation</label> 
                                            <input type="text" id="calculation" name="calculation" class="form-control text-center" readonly>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-center mt-4">
                                                <button type="button" onclick="calculate()" class="login-btn">Calculate</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-12 align-self-center text-center">
                            <p class="fs-12"></p>
                        </div>
                    </div>
                </div>
            </div>
            {$script}
        </section>
        HTML;

    }

    /**
     * widget_title
     * this method must have to implement by all widget to register widget title
     * @since 1.0.0
     * */
    public function addon_title()
    {
        return __('Calculation Area: Nuvasa');
    }

}