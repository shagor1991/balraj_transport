<?php

namespace App\Http\Controllers;

use App\Group;
use App\Invoice;
use App\ItemList;
use App\Models\BankDetail;
use App\Models\CostCenter;
use App\Models\MasterAccount;
use App\PartyInfo;
use App\ProfitCenter;
use App\ProjectDetail;
use App\Setting;
use App\Style;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $counter_sales= Invoice::where('')
        return view('home');
    }

    public function under_construction(){
        return view('under-construction');
    }


    public function pdf($id)
    {
        if ($id == "bankDetails") {
            $bankDetails = BankDetail::latest()->get();
            return view('backend/pdf/bankDetailsPdf', compact('bankDetails'));
        }

        if ($id == "projDetails") {
            $projDetails = ProjectDetail::where('proj_type', '!=', "Draft")->latest()->get();
            return view('backend/pdf/projDetailsPdf', compact('projDetails'));
        }

        if ($id == "MasterAccDetails") {
            $masterDetails = MasterAccount::where('mst_ac_code', '!=', 'Draft')->latest()->get();
            return view('backend/pdf/MasterAccDetailsPdf', compact('masterDetails'));
        }

        if ($id == "costCenter") {
            $costCenters = CostCenter::latest()->get();
            return view('backend/pdf/costCentersPdf', compact('costCenters'));
        }


        if ($id == "profitCenter") {
            $profitDetails = ProfitCenter::where('activity', '!=', 'Draft')->latest()->get();
            return view('backend/pdf/profitCentersPdf', compact('profitDetails'));
        }

        if ($id == "partyCenter") {
            $partyInfos = PartyInfo::orderBy('id','DESC')->get();
            return view('backend/pdf/partyInfoPdf', compact('partyInfos'));
        }
    }




    public function SearchAjax(Request $request, $id)
    {

        if ($id == "masterAcc") {
            $masterDetails = MasterAccount::where('mst_ac_code', 'like', "%{$request->q}%")
                ->orWhere('mst_ac_head', 'like', "%{$request->q}%")
                ->orWhere('mst_definition', 'like', "%{$request->q}%")
                ->orWhere('mst_ac_type', 'like', "%{$request->q}%")
                ->orWhere('vat_type', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;

            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.masterAccTbody', ['masterDetails' => $masterDetails, 'i' => $i])->render()]);
            }
        }


        if ($id == "costCenter") {
            $costCenters = CostCenter::where('cc_code', 'like', "%{$request->q}%")
                ->orWhere('cc_name', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.costCenterTbody', ['costCenters' => $costCenters, 'i' => $i])->render()]);
            }
        }

        if ($id == "projectDetails") {
            $projDetails = ProjectDetail::where('proj_no', 'like', "%{$request->q}%")
                ->orWhere('proj_name', 'like', "%{$request->q}%")
                ->orWhere('cont_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;

            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.projectDetailsTbody', ['projDetails' => $projDetails, 'i' => $i])->render()]);
            }
        }

        if ($id == "bankDetails") {
            $bankDetails = BankDetail::where('bank_code', 'like', "%{$request->q}%")
                ->orWhere('bank_name', 'like', "%{$request->q}%")
                ->orWhere('ac_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.bankDetailsTbody', ['bankDetails' => $bankDetails, 'i' => $i])->render()]);
            }
        }


        if ($id == "profitCenter") {
            $profitDetails = ProfitCenter::where('pc_code', 'like', "%{$request->q}%")
                ->orWhere('pc_name', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.profitCenterTbody', ['profitDetails' => $profitDetails, 'i' => $i])->render()]);
            }
        }


        if ($id == "partyCenter") {
            $partyInfos = PartyInfo::where('pi_code', 'like', "%{$request->q}%")
                ->orWhere('pi_name', 'like', "%{$request->q}%")
                ->orWhere('trn_no', 'like', "%{$request->q}%")
                ->latest()
                ->take(40)
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.partyInfoTbody', ['partyInfos' => $partyInfos, 'i' => $i])->render()]);
            }
        }
        // work by mominul
        if ($id == "group") {
            $groups = Group::where('group_no', 'like', "%{$request->q}%")->orWhere('group_name', 'like', "%{$request->q}%")->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.group', ['groups' => $groups, 'i' => $i])->render()]);
            }
        }
        if ($id == "style") {
            $styles = Style::where('style_no', 'like', "%{$request->q}%")->orWhere('style_name', 'like', "%{$request->q}%")->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.style', ['styles' => $styles, 'i' => $i])->render()]);
            }
        }

        if ($id == "iteList") {
            $itme_lists = ItemList::orderBy('barcode', 'asc')
                ->where('barcode', 'like', "%{$request->q}%")
                ->orWhere('item_name', 'like', "%{$request->q}%")
                ->orWhere('unit', 'like', "%{$request->q}%")
                ->orWhere('sell_price', 'like', "%{$request->q}%")
                ->orWhere('vat_amount', 'like', "%{$request->q}%")
                ->latest()
                ->get();
            $i = 1;
            if ($request->ajax()) {
                return Response()->json(['page' => view('backend.ajax.itemList', ['itme_lists' => $itme_lists, 'i' => $i])->render()]);
            }
        }



    }
}
