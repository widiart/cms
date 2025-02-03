<?php


namespace App\WidgetsBuilder;

class WidgetBuilderSetup
{

    public static function instance()
    {
        return new self();
    }

    private static function registerd_widgets()
    {
        return [
            'AboutUsWidget',
            'ContactInfoWidget',
            'NavigationMenuWidget',
            'RawHTMLWidget',
            'ImageWidget',
            'BlogSearchWidget',
            'JobSearchWidget',
            'BlogCategoryWidget',
            'RecentBlogPostWidget',
            'ServiceCategoryWidget',
            'RecentServicesWidget',
            'CustomFormWidget',
            'RecentCaseStudyWidget',
            'CaseStudyCategoryWidget',
            'RecentJobPostWidget',
            'JobCategoryWidget',
            'EventSearchWidget',
            'RecentEventWidget',
            'EventCategoryWidget',
            'KnowledgebaseSearchWidget',
            'KnowledgebaseCategoryWidget',
            'RecentKnowledgebasePostWidget',
            'NewsletterWidget',
            'TextEditor',
            'RecentNewsWidget',
            'NewsCategoryWidget',
            'LatestNewsWidget',
            'FollowUsWidget',
            'ConnectUsWidget',
            'DownloadButtonWidget',
        ];
    }



    private static function registerd_sidebars()
    {
        $default = [
            'footer',
            'blog',
            'service',
            'case_study',
            'career',
            'event',
            'knowledgebase',
            'homepage_sidebar',
            'newspaper_homepage_sidebar',
        ];

        foreach(get_microsite() as $site) {
            array_unshift($default,"footer_$site->slug");
        }

        return $default;

    }

    public static function get_admin_widget_sidebar_list($filter = null)
    {
        $all_sidebar = self::registerd_sidebars();
        $output = '';
        foreach ($all_sidebar as $sidebar) {
            $output .= self::render_admin_sidebar_item($sidebar);
        }
        if(!empty($filter)) {
            $output = self::render_admin_sidebar_item($filter);
        }
        return $output;
    }

    public static function get_admin_panel_widgets()
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget) {
            $namespace = __NAMESPACE__ . "\Widgets\\" . $widget;
            $widget_instance = new  $namespace();
            $widgets_markup .= self::render_admin_widget_item([
                'widget_name' => $widget_instance->widget_name(),
                'widget_title' => $widget_instance->widget_title()
            ]);
        }
        return $widgets_markup;
    }

    private static function render_admin_widget_item($args)
    {
        return '<li class="ui-state-default widget-handler" data-name="' . $args['widget_name'] . '">
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>' . $args['widget_title'] . '</h4>
                </li>';
    }

    public static function render_admin_sidebar_item($sidebar)
    {
        $markup = '<div class="card">
                    <div class="card-header widget-area-header">
                        <h4 class="header-title">' . ucfirst(str_replace(['-','_'],[' ',' '],$sidebar)) . ' ' . __('Widgets Area') . '</h4>
                        <span class="widget-area-expand"><i class="ti-angle-down"></i></span>
                    </div>
                    <div class="card-body widget-area-body hide">
                        <ul id="' . $sidebar . '" class="sortable available-form-field main-fields sortable_widget_location">
                            ' . self::render_admin_saved_widgets($sidebar) . '
                        </ul>
                    </div>
                </div>';
        return $markup;
    }

    public static function render_widgets_by_name_for_admin($args)
    {

        //widget_name
        $widget_class = 'App\WidgetsBuilder\Widgets\\' . $args['name'];
        $instance = new $widget_class($args);
        $before = $args['before'] ?? true;
        $after = $args['after'] ?? true;
        return $instance->admin_render(['before' => $before, 'after' => $after]);
    }

    public static function render_widgets_by_name_for_frontend($args)
    {
        //widget_name
        $widget_class = 'App\WidgetsBuilder\Widgets\\' . $args['name'];
        $instance = new $widget_class($args);
        return $instance->frontend_render();
    }

    public static function render_admin_saved_widgets($location)
    {
        $output = '';
        $all_widgets = \App\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= \App\WidgetsBuilder\WidgetBuilderSetup::render_widgets_by_name_for_admin([
                'name' => $widget->widget_name,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->widget_order,
                'location' => $widget->widget_location
            ]);
        }

        return $output;
    }

    public static function render_frontend_sidebar($location, $args = [])
    {
        $output = '';
        $all_widgets = \App\Widgets::where(['widget_location' => $location])->orderBy('widget_order', 'ASC')->get();
        foreach ($all_widgets as $widget) {
            $output .= \App\WidgetsBuilder\WidgetBuilderSetup::render_widgets_by_name_for_frontend([
                'name' => $widget->widget_name,
                'location' => $location,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }
}