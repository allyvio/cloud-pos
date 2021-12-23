<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\App;
use App\Code;
use App\User;
use App\Toko;
use App\Exports\RekapExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;


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
        $codes = DB::table('code')->where('user_id', Auth::user()->id)->get()->all();
        $count = count($codes);
        // dd($count);
        if ($count == 0) {
            // $code = Uuid::generate(4);
            $codes = new Code;
            $codes->id = str_random(4);
            $codes->user_id = Auth::user()->id;
            $codes->code = mt_rand(100000, 999999);;
            $codes->save();
        }

        $code = DB::table('code')->where('user_id', Auth::user()->id)->value('code');
        $pegawai = User::whereNotIn('id', ['153127'])->get()->all();
        $shop = Toko::all();
        $rekap = Order::all();
        // dd($pegawai);


        return view('welcome', compact(['code', 'pegawai', 'shop', 'rekap']));
    }

    public function dashboard()
    {
        return view('order.index');
    }

    public function rekapPegawai($id)
    {
        return view('rekap', compact('id'));
    }

    public function getRekapPegawai(Request $request, $id)
    {
        if (!empty($request->from_date)) {
            DB::statement(DB::raw('set @rownum=0'));
            $rekap = DB::table('save_orders')->where('user_id', $id)->whereBetween('tanggal', array($request->from_date, $request->to_date))->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'user_id',
                'product_id',
                'qty',
                'total',
                'tanggal',
            ]);
        } else {
            DB::statement(DB::raw('set @rownum=0'));
            $rekap = DB::table('save_orders')->where('user_id', $id)->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'user_id',
                'product_id',
                'qty',
                'total',
                'tanggal',
            ]);
        }
        // dd($toko)


        $datatables = Datatables::of($rekap)
            ->editColumn('shop_id', function ($row) {
                return DB::table('shops')->where('id', $row->shop_id)->value('name');
            })
            ->editColumn('user_id', function ($row) {
                return DB::table('users')->where('id', $row->user_id)->value('name');
            })
            ->editColumn('product_id', function ($row) {
                return DB::table('products')->where('id', $row->product_id)->value('product_name');
            })
            ->editColumn('total', function ($row) {
                return number_format($row->total);
            })
            ->rawColumns(['shop_id', 'product_id', 'total', 'user_id']);
        // ->addIndexColumn();

        return $datatables->make(true);
    }

    public function rekap()
    {
        return view('admin.rekap');
    }

    public function rekap_pdf(Request $request)
    {
        // dd($request->all());
        $data = Order::whereBetween('tanggal', [$request->from_date, $request->to_date])->get()->all();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin.rekap_pdf', compact('data'));
        return $pdf->stream('rekap.pdf');
        // dd($data);
    }

    public function rekap_excel(Request $request)
    {
        $tgl_awal = $request->from_date;
        $tgl_akhir = $request->to_date;
        $tanggal = date('d M Y', strtotime(Carbon::now()));
        $total = Order::whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->sum('total');
        $data = Order::whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->get()->all();
        
        return Excel::download(new RekapExport($data, $tgl_awal, $tgl_akhir, $total), "rekap_$tanggal.xlsx");
    }

    public function rekap_toko_excel(Request $request)
    {
        // dd($request->all());
        $id = $request->shop_id;
        $tgl_awal = $request->from_date;
        $tgl_akhir = $request->to_date;
        $total = Order::where('shop_id', $id)->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->sum('total');
        $tanggal = date('d M Y', strtotime(Carbon::now()));
        $data = Order::where('shop_id', $id)->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->get()->all();
        // dd($data);
        return Excel::download(new RekapExport($data, $tgl_awal, $tgl_akhir, $total), "rekap_$tanggal.xlsx");
    }

    public function rekap_pegawai_excel(Request $request)
    {
        // dd($request->all());
        $id = $request->user_id;
        $tgl_awal = $request->from_date;
        $tgl_akhir = $request->to_date;
        $tanggal = date('d M Y', strtotime(Carbon::now()));
        $data = Order::where('user_id', $id)->whereBetween('tanggal', [$tgl_awal, $tgl_akhir])->get()->all();
        // dd($data);
        return Excel::download(new RekapExport($data, $tgl_awal, $tgl_akhir), "rekap_$tanggal.xlsx");
    }

    public function getrekap(Request $request)
    {
        if (!empty($request->from_date)) {
            DB::statement(DB::raw('set @rownum=0'));
            $rekap = DB::table('save_orders')->whereBetween('tanggal', array($request->from_date, $request->to_date))->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'product_id',
                'qty',
                'total',
                'tanggal',
            ]);
        } else {
            DB::statement(DB::raw('set @rownum=0'));
            $rekap = DB::table('save_orders')->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'product_id',
                'qty',
                'total',
                'tanggal',
            ]);
        }
        // dd($toko)


        $datatables = Datatables::of($rekap)
            ->editColumn('shop_id', function ($row) {
                return DB::table('shops')->where('id', $row->shop_id)->value('name');
            })
            ->editColumn('product_id', function ($row) {
                $product = DB::table('products')->where('id', $row->product_id)->value('product_name');
                $warna = DB::table('products')->where('id', $row->product_id)->value('warna');
                return $product . ' - ' . $warna;
            })
            ->editColumn('total', function ($row) {
                return number_format($row->total);
            })
            ->addColumn('action', function ($row) {
                $btn = '
            <button class="delete btn btn-danger btn-sm" data-id="' . $row->id . '" data-target="#hapusModal" data-toggle="modal">
            <i class="fas fa-trash"></i>
            </button>
            </a>';
                return $btn;
                // dd($row);
            })
            ->rawColumns(['shop_id', 'product_id', 'total', 'action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }

    public function hapus_rekap(Request $request)
    {
        // dd($request->all());
        $data = Order::findOrFail($request->id);
        $data->delete();

        return back()->with('success', 'Rekap berhasil dihapus');
    }
}
