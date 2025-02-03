<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\LanguageHelper;
use App\Helpers\NexelitHelpers;
use App\Http\Controllers\Controller;
use App\HomeContactUs;
use Illuminate\Http\Request;
use App\Helpers\SanitizeInput;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContactUsHomeExport;

class ContactUsHomeController extends Controller
{
    const BASE_PATH = 'backend.contact-us-home.';
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index(){
        $all_contact = HomeContactUs::orderBy('id','desc')->paginate(10);
        return view(self::BASE_PATH.'contact-us',compact('all_contact'));
    }
    
    public function delete(Request $request,$id){
        HomeContactUs::find($id)->delete();
        return redirect()->back()->with(NexelitHelpers::item_delete());
    }
    public function bulk_action(Request $request){
        HomeContactUs::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
    public function pagination(Request $request,$filter = null) {
        if($request->ajax()) {
            $all_contact = HomeContactUs::orderBy('id','desc');
            if(!empty($filter)) {
                $all_contact = HomeContactUs::where('name','like','%'.$filter.'%')->orwhere('phone','like','%'.$filter.'%')->orderBy('id','desc');
            }

            if(!empty($request->from)) {
                $all_contact = $all_contact->whereDate('created_at','>=', $request->from)->whereDate('created_at','<=', $request->to);
            }

            $all_contact = $all_contact->paginate($request->pagination);
            $data = [];
            $i = 0;
            foreach($all_contact as $isi) {
                $data[$i]['date'] = date('Y-m-d h:i',strtotime($isi->created_at));
                $data[$i]['name'] = $isi->name ?? '';
                $data[$i]['project_name'] = $isi->project_name ?? '';
                $data[$i]['email'] = $isi->email ?? '';
                $data[$i]['phone'] = $isi->phone ?? '';
                $data[$i]['otp_code'] = $isi->otp_code ?? '';
                $data[$i]['cookie'] = $isi->cookie ?? '---';
                $data[$i++]['ip_client'] = $isi->ip_client ?? '';
            }
            $response = ['data'=>$data,'pagination'=>(String)$all_contact->links()];
            return response()->json($response);
        }
    }
    public function export(Request $request,$type,$filter = null) {
        $all_contact = HomeContactUs::orderBy('id','desc')->get();
        if(!empty($filter)) {
            $all_contact = HomeContactUs::where('name','like','%'.$filter.'%')->orwhere('phone','like','%'.$filter.'%')->orderBy('id','desc')->get();
        }

        if($type == 'excel') {
            $format = \Maatwebsite\Excel\Excel::XLSX;
            return Excel::download(new ContactUsHomeExport($filter),'contact-us-home-'.date('d-m-Y').'-'.$filter.'.xlsx', $format);
        } else if($type == 'csv') {
            $format = \Maatwebsite\Excel\Excel::CSV;
            return Excel::download(new ContactUsHomeExport($filter),'contact-us-home-'.date('d-m-Y').'-'.$filter.'.csv', $format);
        } else {
            return response($all_contact->toJson())
                ->withHeaders([
                    'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-store, no-cache',
                    'Content-Disposition' => 'attachment; filename="contact-us-home-'.date('d-m-Y').'-'.$filter.'.json',
                ]);
        }

    }
}
