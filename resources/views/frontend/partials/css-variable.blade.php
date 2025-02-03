<style>
    @import url("https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,400;1,500;1,700;1,900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Outfit:wght@200;300;400;500;600;700;800;900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Source+Serif+Pro:wght@200;300;400;600;700;900&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap");
    @import url("https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700;800&display=swap");
    :root {
        --main-color-one: {{filter_static_option_value('site_color',$global_static_field_data) ?? '#ff8a73'}};
        --main-color-two: {{filter_static_option_value('site_main_color_two',$global_static_field_data) ?? '#5580ff'}};
        --portfolio-color: {{filter_static_option_value('portfolio_home_color',$global_static_field_data) ?? '#FF4757'}};
        --logistic-color: {{filter_static_option_value('logistics_home_color',$global_static_field_data) ?? '#FF3F39'}};
        --industry-color: {{filter_static_option_value('industry_home_color',$global_static_field_data) ?? '#FF5820'}};
        --secondary-color: {{filter_static_option_value('site_secondary_color',$global_static_field_data) ?? '#1d2228'}};
        --heading-color: {{filter_static_option_value('site_heading_color',$global_static_field_data) ?? '#353539'}};
        --paragraph-color: {{filter_static_option_value('site_paragraph_color',$global_static_field_data) ??  '#878a95'}};
        --construction-color: {{filter_static_option_value('construction_home_color',$global_static_field_data) ?? '#FFBC13'}};

        --lawyer-color: {{filter_static_option_value('lawyer_home_color',$global_static_field_data) ?? '#C89D66'}};
        --political-color: {{filter_static_option_value('political_home_color',$global_static_field_data) ?? '#E70F47'}};
        --medical-color: {{filter_static_option_value('medical_home_color',$global_static_field_data) ?? '#47CBF1'}};
        --medical-two-color: {{filter_static_option_value('medical_home_color_two',$global_static_field_data) ?? '#FC6285'}};
        --fruits-color: {{filter_static_option_value('fruits_home_color',$global_static_field_data) ?? '#4CA338'}};
        --fruits-heading-color: {{filter_static_option_value('fruits_home_heading_color',$global_static_field_data) ?? '#014019'}};
        --portfolio-dark-one: {{filter_static_option_value('portfolio_home_dark_color',$global_static_field_data) ?? '#202334'}};
        --portfolio-dark-two: {{filter_static_option_value('portfolio_home_dark_two_color',$global_static_field_data) ?? '#191C47'}};
        --charity-color: {{filter_static_option_value('charity_home_color',$global_static_field_data) ?? '#D1322E'}};
        --dagency-color: {{filter_static_option_value('dagency_home_color',$global_static_field_data) ?? '#FF8947'}};
        --cleaning-color: {{filter_static_option_value('cleaning_home_color',$global_static_field_data) ?? '#FEE026'}};
        --cleaning-two-color: {{filter_static_option_value('cleaning_home_two_color',$global_static_field_data) ?? '#20BDEA'}};
        --course-color: {{filter_static_option_value('course_home_color',$global_static_field_data) ?? '#21BBF7'}};
        --course-two-color: {{filter_static_option_value('course_home_two_color',$global_static_field_data) ?? '#FDA909'}};
        --grocery-color: {{filter_static_option_value('grocery_home_color',$global_static_field_data) ?? '#80B82D'}};
        --grocery-heading-color: {{filter_static_option_value('grocery_home_two_color',$global_static_field_data) ?? '#014019'}};
        @php $heading_font_family = !empty(filter_static_option_value('heading_font',$global_static_field_data)) ? filter_static_option_value('heading_font_family',$global_static_field_data) :  filter_static_option_value('body_font_family',$global_static_field_data) @endphp
        --heading-font: '{{$heading_font_family}}',sans-serif;
        --body-font: '{{filter_static_option_value('body_font_family',$global_static_field_data)}}',sans-serif;


        --main-color-three: {{get_static_option('main_color_three','#ff805d')}} ;
        --main-color-three-rgb: {{get_static_option('main_color_three_rgb','#FF805D')}};
        --main-color-four: {{get_static_option('main_color_four','#ff1747')}};
        --main-color-four-rgb: {{get_static_option('main_color_four_rgb','#FF1747')}};
        --main-color-five: {{get_static_option('main_color_five','#fcda69')}};
        --main-color-five-rgb: {{get_static_option('main_color_five_rgb','#FFDA69')}} ;
        
        --heading-color-home-19: #1B1C25;
        --light-color: #666666;
        --extra-light-color: #999999;
        --review-color: #FABE50;
        --stock-color: #5AB27E;
        --heading-font-home-19: "Outfit", sans-serif;
        --heading-font-home-20: "Source Serif Pro", serif;
        --heading-font-home-21: "Space Grotesk", sans-serif;
        --body-font-home-19: "Roboto", sans-serif;
        --body-font-home-21: "Manrope", sans-serif;
        --roboto-font: "Roboto", sans-serif;

    }


</style>
