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

class ContactAreaNuvasaStyle extends PageBuilderBase
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

        $output .= Text::get([
            'name' => 'project_name',
            'label' => __('Project Name'),
            'value' => $widget_saved_values['project_name'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'project_code',
            'label' => __('Project Code'),
            'value' => $widget_saved_values['project_code'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'cluster_code',
            'label' => __('Cluster Code'),
            'value' => $widget_saved_values['cluster_code'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'lead_source_name',
            'label' => __('Lead Source Name'),
            'value' => $widget_saved_values['lead_source_name'] ?? null,
        ]);

        $output .= Switcher::get([
            'name' => 'is_otp',
            'label' => __('Use OTP'),
            'value' => $widget_saved_values['is_otp'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'custom_form_id',
            'label' => __('Custom Form'),
            'placeholder' => __('Select form'),
            'options' => FormBuilder::all()->pluck('title','id')->toArray(),
            'value' =>   $widget_saved_values['custom_form_id'] ?? []
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
        $custom_form_id = SanitizeInput::esc_html($all_settings['custom_form_id']);
        $margin_top = SanitizeInput::esc_html($all_settings['margin_top']);
        $margin_bottom = SanitizeInput::esc_html($all_settings['margin_bottom']);
        $project_name = $all_settings['project_name'] ?? '';
        $project_code = $all_settings['project_code'] ?? '';
        $cluster_code = $all_settings['cluster_code'] ?? '';
        $lead_source_name = $all_settings['lead_source_name'] ?? '';

        $random = random_int(9999,666666);
        
        $form = '';
        if (!empty($custom_form_id)){
            $form .= '<div class="col-lg-8"> <div class="contact-form-wrap">';
            $form_details = FormBuilder::find($custom_form_id);
            $form .= FormBuilderCustom::render_form(optional($form_details)->id,null,null,'boxed-btn');
            $form .= '</div></div>';
        }
        $fields = $this->render_fields($form_details->fields);
        $text_btn = $form_details->button_text;
        $action = $action ?? route('frontend.form.builder.nuvasa.submit');

        $otp = '';
        $btn = <<<HTML
            <button type="submit" class="login-btn">{$text_btn}</button>
        HTML;

        $routeCustom = route('frontend.form.builder.custom.submit');
        $routeOtp = route('frontend.form.builder.nuvasa.otp');
        $routeOtpcek = route('frontend.form.builder.nuvasa.otp.verify');
        $routeCpi = route('frontend.form.builder.nuvasa.cpi');
        $csrf = csrf_token();
        $otp_input = '';
        if(!empty($all_settings['is_otp'])){
            $otp_input = <<<HTML
                <input name='is_otp' value=1 type="hidden">
            HTML;
            $otp = <<<HTML
                <style>
                    .otp {
                        display:inline-block;
                        width:50px;
                        height:50px;
                        text-align:center;
                    }
                </style>
                <script>
                    let digitValidate = function(ele){
                        console.log(ele.value);
                        ele.value = ele.value.replace(/[^0-9]/g,'');
                    }

                    let tabChange = function(val){
                        let ele = document.querySelectorAll('input.otp');
                        if(ele[val-1].value != ''){
                        ele[val].focus()
                        }else if(ele[val-1].value == ''){
                        ele[val-2].focus()
                        }   
                    }

                    function sendCpi() {
                        var form = $('#custom_form_builder_{$random}');
                        var formID = form.attr('id');
                        var msgContainer =  form.find('.error-message');
                        // var msgContainer =  $('#custom_form_otp_{$random} .error-message');
                        var formSelector = document.getElementById(formID);
                        var formData = new FormData(formSelector);
                        $.ajax({
                            url: "{$routeCpi}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{$csrf}",
                            },
                            beforeSend:function (){
                                form.find('.ajax-loading-wrap').addClass('show').removeClass('hide');
                            },
                            processData: false,
                            contentType: false,
                            data:formData,
                            success: function (data) {
                                $('#custom_form_builder_{$random}').trigger("reset")
                            },
                            error: function (data) {
                            }
                        });

                    }

                    function checkOtp() {
                        var form = $('#custom_form_otp_{$random}');
                        var formID = form.attr('id');
                        var msgContainer =  $('#custom_form_otp_{$random} .error-message');
                        var formSelector = document.getElementById(formID);
                        var formData = new FormData(formSelector);
                        $.ajax({
                            url: "{$routeOtpcek}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{$csrf}",
                            },
                            beforeSend:function (){
                                form.find('.ajax-loading-wrap').addClass('show').removeClass('hide');
                            },
                            processData: false,
                            contentType: false,
                            data:formData,
                            success: function (data) {
                                form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                                console.log(data.status)
                                if(data.status == 'ok') {
                                    msgContainer.html('');
                                    $('#otpModal{$random}').modal('hide');
                                    $('#custom_form_builder_{$random}').find('.error-message').html('<div class="alert alert-success">' + data.msg + '</div>')
                                    sendCpi()
                                } else {
                                    msgContainer.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                                }
                            },
                            error: function (data) {
                            }
                        });
                    }

                    function sendOtp() {
                        var form = $('#custom_form_builder_{$random}');
                        var formID = form.attr('id');
                        var msgContainer =  $('#custom_form_otp_{$random} .error-message');
                        var formSelector = document.getElementById(formID);
                        var formData = new FormData(formSelector);
                        $.ajax({
                            url: "{$routeOtp}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{$csrf}",
                            },
                            beforeSend:function (){
                                form.find('.ajax-loading-wrap').addClass('show').removeClass('hide');
                            },
                            processData: false,
                            contentType: false,
                            data:formData,
                            success: function (data) {
                            },
                            error: function (data) {
                            }
                        });
                    }

                    function showOtp() {
                        var form = $('#custom_form_builder_{$random}');
                        var formID = form.attr('id');
                        var msgContainer =  form.find('.error-message');
                        // var msgContainer =  $('#custom_form_otp_{$random} .error-message');
                        var formSelector = document.getElementById(formID);
                        var formData = new FormData(formSelector);
                        $.ajax({
                            url: "{$routeCustom}",
                            type: "POST",
                            headers: {
                                'X-CSRF-TOKEN': "{$csrf}",
                            },
                            beforeSend:function (){
                                form.find('.ajax-loading-wrap').addClass('show').removeClass('hide');
                            },
                            processData: false,
                            contentType: false,
                            data:formData,
                            success: function (data) {
                                form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                                if(data.status == 'ok') {
                                    msgContainer.html('');
                                    $('#otpModal{$random}').modal('show');
                                    sendOtp()
                                } else {
                                    msgContainer.html('<div class="alert alert-'+data.type+'">' + data.msg + '</div>');
                                }
                            },
                            error: function (data) {
                                form.find('.ajax-loading-wrap').removeClass('show').addClass('hide');
                                var errors = data.responseJSON.errors;
                                var markup = '<ul class="alert alert-danger">';
                                $.each(errors,function (index,value){
                                    markup += '<li>'+value+'</li>';
                                })
                                markup += '</ul>';
                                msgContainer.html(markup);
                            }
                        });
                    }

                </script>
                <div class="modal fade" id="otpModal{$random}" tabindex="-1" role="dialog" aria-labelledby="otpModal{$random}Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h5 class="modal-title" id="otpModal{$random}Label">OTP Verification</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p class="text-center">
                                    Please Input OTP Code Send to phone number
                                </p>
                                <form action="" id="custom_form_otp_{$random}" class="custom-form-builder-form text-center mt-5">
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(1)' maxlength=1 >
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(2)' maxlength=1 >
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(3)' maxlength=1 >
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(4)' maxlength=1 >
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(5)' maxlength=1 >
                                    <input class="otp" type="text" name='otp[]' oninput='digitValidate(this)' onkeyup='tabChange(6)' maxlength=1 >
                                    <div class="error-message mt-3"></div>
                                    <input class="otp" type="hidden" name='id' value='{$custom_form_id}' >
                                    <input class="otp" type="hidden" name='otp_id' value='custom_form_id_{$random}' >
                                </form>
                            </div>
                            <div class="modal-footer text-center align-self-center">
                                <button type="button" class="btn btn-primary" onclick="checkOtp()">Verify</button>
                            </div>
                        </div>
                    </div>
                </div>
            HTML;

            $btn = <<<HTML
                <button type="button" onclick="showOtp()" class="login-btn">{$text_btn}</button>
            HTML;
        }


        return <<<HTML
        {$otp}
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"
        />
        <style>
            .iti {
                display: block !important;
            }
        </style>
        <section id="nuvasabay" style="margin-bottom:{$margin_bottom}px;margin-top:{$margin_top}px">
            <div class="container-fluid p-0">
                <div class="w-100 bg-primary text-center py-3 mb-4">
                    <h2 class="text-white text-nowrap">{$title}</h2>
                </div>
                <div class="container" data-aos-duration="1500" data-aos="fade-up">
                    <div class="row g-3 g-lg-5 justify-content-between">
                        <div class="col-12 form_append">
                                <div class="row d-flex mx-0 gx-4 gy-2 form">
                                    <form action="{$action}" {$custom_form_id} method="post" id="custom_form_builder_{$random}" class="custom-form-builder-form" enctype="multipart/form-data">
                                        <input type="hidden" name="custom_form_id" value="{$custom_form_id}">
                                        <input type="hidden" name="id" value="custom_form_id_{$random}">
                                        <input type="hidden" name="captcha_token" id="gcaptcha_token" value="">
                                        {$otp_input}
                                        <input type="hidden" name="project_name" id="project_name" value="{$project_name}">
                                        <input type="hidden" name="project_code" id="project_code" value="{$project_code}">
                                        <input type="hidden" name="cluster_code" id="cluster_code" value="{$cluster_code}">
                                        <input type="hidden" name="cluster_code" id="cluster_code" value="{$cluster_code}">
                                        <input type="hidden" name="lead_source_name" id="lead_source_name" value="{$lead_source_name}">
                                        <input class="otp" type="hidden" name='otp_id' value='custom_form_id_{$random}' >
                                        <div class="error-message"></div>
                                        <div class="row">
                                            {$fields}
                                            <div class="col-12">
                                                <div class="text-center">
                                                    {$btn}
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                        </div>
                        <div class="col-12 align-self-center text-center">
                            <p class="fs-12">Feel free to contact us, please fill this form and we'll be
                                gladly to
                                reach out to you soon.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
        <script>
            const phoneInputField = document.querySelector("#phone-number");
            const phoneInput = window.intlTelInput(phoneInputField, {
                onlyCountries: ["id", "sg", "my"],
                hiddenInput: "phone-number",
                utilsScript:
                    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            });
        </script>
        HTML;

    }

    /**
     * widget_title
     * this method must have to implement by all widget to register widget title
     * @since 1.0.0
     * */
    public function addon_title()
    {
        return __('Contact Area: Nuvasa');
    }

    private function render_fields($fields) :string
    {
        $fields = json_decode($fields);

        $output = '';
        $select_index = 0;
        $options = [];

        foreach ($fields->field_type as $key => $value) {
            if (!empty($value)) {
                if ($value === 'select') {
                    $options = explode("\n", $fields->select_options[$select_index]);
                }
                $required = $fields->field_required->$key ?? '';
                $mimes = isset($fields->mimes_type->$key) ? $fields->mimes_type->$key : '';
                if($this->a < 4) {
                    $output .= $this->get_field_by_type($value, $fields->field_name[$key], $fields->field_placeholder[$key], $options, $required, $mimes);
                } else {
                    break;
                }
                $this->a++;
                if ($value === 'select') {
                    $select_index++;
                }
            }
        }

        return $output;
    }

    private function get_field_by_type($type, $name, $placeholder, $options = [], $requried = null, $mimes = null)
    {
        if (empty($name)){
            return;
        }
        $markup = '';
        $name = SanitizeInput::esc_html($name);
        $placeholder = SanitizeInput::esc_html($placeholder);
        $required_markup_html = 'required="required"';
        
        switch ($type) {
            case('email'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="email" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
                break;
            case('tel'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="tel" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
                break;
            case('date'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group"><label for="' . $name . '">' . __($placeholder) . '</label> <input type="date" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
                break;
            case('url'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group"><label for="' . $name . '">' . __($placeholder) . '</label> <input type="url" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
                break;
            case('textarea'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group textarea"><label for="' . $name . '">' . __($placeholder) . '</label> <textarea name="' . $name . '" id="' . $name . '" cols="30" rows="5" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></textarea></div>';
                break;
            case('file'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $mimes_type_markup = str_replace('mimes:', __('Accept File Type:') . ' ', $mimes);
                $markup = ' <div class="'.$this->class[$this->a].' form-group file"> <label for="' . $name . '">' . __($placeholder) . '</label> <input type="file" id="' . $name . '" name="' . $name . '" ' . $required_markup . ' class="form-control" > <span class="help-info">' . $mimes_type_markup . '</span></div>';
                break;
            case('checkbox'):
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group checkbox">  <input type="checkbox" id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '> <label for="' . $name . '">' . __($placeholder) . '</label></div>';
                break;
            case('select'):
                $option_markup = '';
                $required_markup = !empty($requried) ? $required_markup_html : '';
                foreach ($options as $opt) {
                    $option_markup .= '<option value="' . Str::slug($opt) . '">' . $opt . '</option>';
                }
                $markup = ' <div class="'.$this->class[$this->a].' form-group select"> <label for="' . $name . '">' . __($placeholder) . '</label> <select id="' . $name . '" name="' . $name . '" class="form-control" ' . $required_markup . '>' . $option_markup . '</select></div>';
                break;
            default:
                $required_markup = !empty($requried) ? $required_markup_html : '';
                $markup = ' <div class="'.$this->class[$this->a].' form-group"><label for="' . $name . '">' . __($placeholder) . '</label> <input type="text" id="' . $name . '" name="' . $name . '" class="form-control" placeholder="' . __($placeholder) . '" ' . $required_markup . '></div>';
                break;
        }

        return $markup;
    }
}