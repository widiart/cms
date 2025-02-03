<?php

namespace App\Http\Controllers;

use App\Blog;
use App\BlogCategory;
use App\Language;
use App\Microsite;
use App\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MicrositesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(){
        $all_microsite = Microsite::all()->groupBy('lang');
        $all_language = Language::all();
        return view('backend.pages.microsite.index')->with([
            'all_microsite' => $all_microsite,
            'all_languages' => $all_language,
        ]);
    }
    public function new_microsite(){
        $all_language = Language::all();
        return view('backend.pages.microsite.new')->with(['all_languages' => $all_language]);
    }

    public function slug_check(Request $request){
        $this->validate($request,[
           'slug' => 'required|string',
           'type' => 'required|string',
        //    'lang' => 'required|string',
        ]);

        $pre_made_microsites_slug = ['video_gallery','about','service','work','team','faq','price_plan','blog','contact','career_with_us','events','knowledgebase','donation','product','testimonial','feedback','clients_feedback','image_gallery','donor','appointment','quote','courses','support_ticket'];
        $matched_pre_made_microsite_slug = false;
        $user_given_slug = $request->slug;
        foreach($pre_made_microsites_slug as $microsite_slug){
            if ($request->slug === get_static_option($microsite_slug.'_microsite_slug')){
                $matched_pre_made_microsite_slug = true;
            }
        }

        if ($matched_pre_made_microsite_slug){
            $user_given_slug .= '-'.random_int(1,9);
        }

        $query = Microsite::where(['slug' => $user_given_slug]);
        if (!empty($request->lang)){
            $query->where('lang' , $request->lang);
        }
        $slug_count = $query->count();

        if ($request->type === 'new' && $slug_count > 0){
            return $user_given_slug.'-'.$slug_count;
        }elseif ($request->type === 'update' && $slug_count > 1){
            return $user_given_slug.'-'.$slug_count;
        }
        return $user_given_slug;
    }


    public function store_new_microsite(Request $request){

        $this->validate($request,[
            'name' => 'required',
            'slug' => 'nullable',
            'status' => 'required|string|max:191',
            'home_page_variant' => 'required',
            'navbar_variant' => 'required',
        ]);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title,$request->lang);

        Microsite::create([
            'slug' => $slug,
            'name' => $request->name,
            'home_page_variant' => $request->home_page_variant,
            'navbar_variant' => $request->navbar_variant,
        ]);
        \Illuminate\Support\Facades\Cache::forget('microsite');

        return redirect()->back()->with([
            'msg' => __('New Microsite Created...'),
            'type' => 'success'
        ]);
    }
    public function edit_microsite($id){
        $microsite_post = Microsite::find($id);
        $all_language = Language::all();
        return view('backend.pages.microsite.edit')->with([
            'microsite_post' => $microsite_post,
            'all_languages' => $all_language
        ]);
    }
    public function update_microsite(Request $request,$id){

        $oldslug = Microsite::where('id',$id)->first()->slug;
        $this->validate($request,[
            'name' => 'required',
            'site_logo_'.$oldslug => 'nullable',
            'slug' => 'nullable',
            'status' => 'required|string|max:191',
            'home_page_variant' => 'required',
            'navbar_variant' => 'required',
        ]);

        $slug = !empty($request->slug) ? $request->slug : Str::slug($request->title,$request->lang);

        Microsite::where('id',$id)->update([
            'name' => $request->name,
            'slug' => $slug,
            'site_logo' => $request->{'site_logo_'.$oldslug},
            'status' => $request->status,
            'home_page_variant' => $request->home_page_variant,
            'navbar_variant' => $request->navbar_variant,
        ]);

        \Illuminate\Support\Facades\Cache::forget('microsite');
        return redirect()->back()->with([
            'msg' => __('Microsite updated...'),
            'type' => 'success'
        ]);
    }
    public function delete_microsite(Request $request,$id){
        Microsite::find($id)->delete();
        return redirect()->back()->with([
            'msg' => __('Microsite Delete Success...'),
            'type' => 'danger'
        ]);
    }

    public function bulk_action(Request $request){
        Microsite::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
