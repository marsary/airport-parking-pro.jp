<?php

namespace App\Http\Controllers\Manage\Master;

use App\Exports\AgencyExport;
use App\Http\Controllers\Manage\Controller;
use App\Http\Requests\Manage\Master\AgencyRequest;
use App\Http\Requests\Manage\Master\AgencyUploadRequest;
use App\Imports\AgencyImport;
use App\Models\Agency;
use App\Services\UploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgenciesController extends Controller
{
    //
    public function index(Request $request)
    {
        $agencies = Agency::where('office_id', config('const.commons.office_id'))
            ->when($request->input('name'), function($query, $search){
                $query->where('name', 'LIKE', '%' . $search . '%');
            })
            ->when($request->input('code'), function($query, $search){
                $query->where('code', $search);
            })
            ->when($request->input('tel'), function($query, $search){
                $query->where('tel', $search);
            })
            ->when($request->input('keyword'), function($query, $search){
                $query->where('keyword', 'LIKE', '%' . $search . '%');
            })
            ->orderBy('name','desc')->get();


        return view('manage.master.agencies', [
            'agencies' => $agencies,
        ]);
    }

    public function download($id)
    {
        /** @var AgencyExport $export */
        $export = new AgencyExport([$id]);

        $fileName = Carbon::today()->format('Ymd') . '_search_agencies.csv';
        return $export->download($fileName, \Maatwebsite\Excel\Excel::CSV);
    }

    public function upload(AgencyUploadRequest $request)
    {
        // dd($request->all());
        (new AgencyImport)->import($request->file('csvFileInput'), null, \Maatwebsite\Excel\Excel::CSV);

        return redirect()->back();
    }


    public function store(AgencyRequest $request)
    {
        DB::transaction(function () use($request) {
            $agency = Agency::create([
                'office_id' => config('const.commons.office_id'),
                'name' => $request->name,
                'code' => $request->code,
                'zip' => $request->zip,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'tel' => $request->tel,
                'keyword' => $request->keyword,
                'branch' => $request->branch,
                'department' => $request->department,
                'position' => $request->position,
                'person' => $request->person,
                'email' => $request->email,
                'payment_site' => $request->payment_site,
                'payment_destination' => $request->payment_destination,
                'memo' => $request->memo,
                'monthly_fixed_cost_flag' => $request->monthly_fixed_cost_flag,
                'monthly_fixed_cost' => $request->monthly_fixed_cost,
                'incentive_flag' => $request->incentive_flag,
                'incentive' => $request->incentive,
                'banner_comment_set' => $request->banner_comment_set,
                'title_set' => $request->title_set,
            ]);

            if($request->has('logo_image')) {
                $logoPath = UploadService::saveFile($request->file('logo_image'), '/agencies/' . $agency->id);
                $agency->logo_image = $logoPath;
            }
            if($request->has('campaign_image')) {
                $campaignPath = UploadService::saveFile($request->file('campaign_image'), '/agencies/' . $agency->id);
                $agency->campaign_image = $campaignPath;
            }
            $agency->save();
        });


        return redirect()->back();
    }


    public function update(AgencyRequest $request, $id)
    {
        $agency = Agency::findOrFail($id);

        if($request->has('logo_image')) {
            if($agency->logo_image && Storage::disk('uploads')->exists($agency->logo_image)){
                Storage::disk('uploads')->delete($agency->logo_image);
            }
            $logoPath = UploadService::saveFile($request->file('logo_image'), '/agencies/' . $agency->id);
            $agency->logo_image = $logoPath;
        }
        if($request->has('campaign_image')) {
            if($agency->campaign_image && Storage::disk('uploads')->exists($agency->campaign_image)){
                Storage::disk('uploads')->delete($agency->campaign_image);
            }
            $campaignPath = UploadService::saveFile($request->file('campaign_image'), '/agencies/' . $agency->id);
            $agency->campaign_image = $campaignPath;
        }

        $agency->fill([
            'name' => $request->name,
            'code' => $request->code,
            'zip' => $request->zip,
            'address1' => $request->address1,
            'address2' => $request->address2,
            'tel' => $request->tel,
            'keyword' => $request->keyword,
            'branch' => $request->branch,
            'department' => $request->department,
            'position' => $request->position,
            'person' => $request->person,
            'email' => $request->email,
            'payment_site' => $request->payment_site,
            'payment_destination' => $request->payment_destination,
            'memo' => $request->memo,
            'monthly_fixed_cost_flag' => $request->monthly_fixed_cost_flag,
            'monthly_fixed_cost' => $request->monthly_fixed_cost,
            'incentive_flag' => $request->incentive_flag,
            'incentive' => $request->incentive,
            'banner_comment_set' => $request->banner_comment_set,
            'title_set' => $request->title_set,
        ])->save();

        return redirect()->back();
    }


    public function destroy($id)
    {
        $agency = Agency::findOrFail($id);

        if($agency->logo_image && Storage::disk('uploads')->exists($agency->logo_image)){
            Storage::disk('uploads')->delete($agency->logo_image);
        }
        if($agency->campaign_image && Storage::disk('uploads')->exists($agency->campaign_image)){
            Storage::disk('uploads')->delete($agency->campaign_image);
        }

        $agency->delete();

        return redirect()->back();
    }

}
