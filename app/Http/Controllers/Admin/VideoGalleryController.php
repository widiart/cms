<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LanguageHelper;
use App\Helpers\NexelitHelpers;
use App\Http\Controllers\Controller;
use App\VideoGallery;
use App\VideoUpload;
use App\FileUpload;
use Illuminate\Http\Request;
use App\Helpers\SanitizeInput;

class VideoGalleryController extends Controller
{
    const BASE_PATH = 'backend.video-gallery.';
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $all_gallery = VideoGallery::all();
        return view(self::BASE_PATH.'video-gallery',compact('all_gallery'));
    }

    public function upload(){
        $all_gallery = VideoUpload::all();
        return view(self::BASE_PATH.'video-upload',compact('all_gallery'));
    }

    public function upload_file(){
        $all_gallery = FileUpload::all();
        return view(self::BASE_PATH.'file-upload',compact('all_gallery'));
    }

    public function store(Request $request){
        if(!empty($request->embed_code)) {
            $this->validate($request,[
                'title' => 'nullable|string',
                'embed_code' => 'required|string',
                'status' => 'required|string',
            ]);
            VideoGallery::create([
                'title' => $request->title,
                'embed_code' => $request->embed_code,
                'status' => $request->status
            ]);
        } else {
            $model = !empty($request->jenis) ? 'App\FileUpload' : 'App\VideoUpload';
            $validator = !empty($request->jenis) ? '' : '|mimetypes:video/mp4';
            $this->validate($request,[
                'title' => 'nullable|string',
                'local_file' => 'required|file'.$validator,
                'status' => 'required|string',
            ]);

            $file = $request->file('local_file');
            if(!empty($request->jenis)) {
                $upload_dir = 'assets/uploads/files/';
            } else {
                $upload_dir = 'assets/uploads/videos/';
            }
            $filename = 'not-uploaded';

            if(!empty($file)) {
                $filename = SanitizeInput::esc_url($upload_dir.$file->getClientOriginalName());
                $file->move($upload_dir,$filename);
                
            }

            $model::create([
                'title' => $request->title,
                'local_file' => $filename,
                'status' => $request->status
            ]);
        }
        return redirect()->back()->with(NexelitHelpers::item_new());
    }
    public function update(Request $request){
        
        if(!empty($request->embed_code)) {
            $this->validate($request,[
                'title' => 'nullable|string',
                'embed_code' => 'required|string',
                'status' => 'required|string',
            ]);
            VideoGallery::find($request->id)->update([
                'title' => $request->title,
                'embed_code' => $request->embed_code,
                'status' => $request->status
            ]);
        } else {
            $this->validate($request,[
                'title' => 'nullable|string',
                'local_file' => 'required|file|mimetypes:video/mp4',
                'status' => 'required|string',
            ]);

            $file = $request->file('local_file');
            $upload_dir = 'assets/uploads/videos/';
            $filename = 'not-uploaded';

            if(!empty($file)) {
                $filename = SanitizeInput::esc_url($upload_dir.$file->getClientOriginalName());
                $file->move($upload_dir,$filename);
                
            }

            VideoUpload::find($request->id)->update([
                'title' => $request->title,
                'local_file' => $filename,
                'status' => $request->status
            ]);
        }
        return redirect()->back()->with(NexelitHelpers::item_update());
    }
    public function delete(Request $request,$id){
        VideoGallery::find($id)->delete();
        return redirect()->back()->with(NexelitHelpers::item_delete());
    }
    public function delete_upload(Request $request,$id){
        VideoUpload::find($id)->delete();
        return redirect()->back()->with(NexelitHelpers::item_delete());
    }
    public function delete_file(Request $request,$id){
        FileUpload::find($id)->delete();
        return redirect()->back()->with(NexelitHelpers::item_delete());
    }

    public function bulk_action(Request $request){
        VideoGallery::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function page_settings(){
        return view(self::BASE_PATH.'video-gallery-page-settings')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_page_settings(Request $request){
        $this->validate($request,[
            'site_video_gallery_post_items' => 'required',
            'site_video_gallery_order' => 'required',
            'site_video_gallery_order_by' => 'required',
        ]);
        $all_fields  = [
            'site_video_gallery_post_items',
            'site_video_gallery_order',
            'site_video_gallery_order_by'
        ];

        foreach ($all_fields as $field){
            update_static_option($field,$request->$field);
        }

        return redirect()->back()->with(['msg' => __('Settings Updated...'),'type' => 'success']);
    }
}
