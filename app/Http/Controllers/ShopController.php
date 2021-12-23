<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Toko;
use Illuminate\Support\Facades\DB;
use DataTables;
use App\Product;
use App\User;
use App\Order;
use App\Exports\RekapStockExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Toko::all();
        // dd($category);
        return view('admin.shop.index', compact('category'));
    }
    public function getshop(Request $request)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $toko = DB::table('shops')->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'name',
            'city',
            'region',
            'street_address',
        ]);

        // dd($toko)


        $datatables = Datatables::of($toko)
            ->editColumn('name', function ($toko) {

                $id = $toko->id;
                return '<span id="' . $id . '" style="cursor:pointer;" class="btn-toko">' . $toko->name;
            })
            ->addColumn('action', function ($row) {
                $btn = '<button id="edit-user" data-toggle="modal" data-target="#editModal" data-city="' . $row->city . '" data-name="' . $row->name . '" data-id="' . $row->id . '" data-region="' . $row->region . '" data-street_address="' . $row->street_address . '" class="delete btn btn-info btn-sm">
                <i class="fas fa-edit"></i>

                <a href="shop/' . $row->id . '">
                </button>
            <button class="delete btn btn-primary btn-sm">
            <i class="fas fa-info-circle"></i>
            </button>
            </a>';
                return $btn;
                // dd($row);
            })
            ->rawColumns(['name', 'action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $new = new \App\Toko;
        $new->region = $request->get('region');
        $new->city = $request->get('city');
        $new->name = $request->get('name');
        $new->street_address = $request->get('street_address');
        $new->save();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = \App\Product::all();
        $user = \App\User::all();
        $shop_id = $id;
        $temp_user = count(\DB::table('shop_user')->where('shop_id', $shop_id)->get()->all());
        $temp_product = count(\DB::table('product_shop')->where('shop_id', $shop_id)->get()->all());
        $rekap = count(Order::where('shop_id', $shop_id)->get()->all());
        // dd($temp_product);
        return view('admin.shop.singleshop', compact(['product', 'user', 'shop_id', 'temp_product', 'temp_user', 'rekap']));
    }
    public function getproductcat($id)
    {
        $product = \App\Category::with('products')->where('id', $id)->get()->all();
        // dd();
        return json_encode([$product[0]->products->pluck('product_name', 'id'), $product[0]->products->pluck('warna')]);
    }

    public function rekapPegawai($id)
    {
        return view('admin.shop.rekap_pegawai', compact('id'));
    }

    public function getrekappegawai(Request $request, $id)
    {


        DB::statement(DB::raw('set @rownum=0'));
        $rekap = DB::table('save_orders')->where('shop_id', $id)->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'shop_id',
            'product_id',
            'user_id',
            'qty',
            'total',
            'tanggal',
        ]);

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
            ->editColumn('user_id', function ($row) {
                return DB::table('users')->where('id', $row->user_id)->value('name');
            })
            ->editColumn('total', function ($row) {
                return number_format($row->total);
            })
            ->rawColumns(['shop_id', 'product_id', 'total']);
        // ->addIndexColumn();

        return $datatables->make(true);
    }

    public function rekaptoko($id)
    {
        return view('admin.shop.rekap', compact('id'));
    }

    public function getrekap(Request $request, $id)
    {

        if (!empty($request->from_date)) {
            DB::statement(DB::raw('set @rownum=0'));
            $rekap = DB::table('save_orders')->where('shop_id', $id)->whereBetween('tanggal', array($request->from_date, $request->to_date))->select([
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
            $rekap = DB::table('save_orders')->where('shop_id', $id)->select([
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
            ->rawColumns(['shop_id', 'product_id', 'total']);
        // ->addIndexColumn();

        return $datatables->make(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        $data = \App\Toko::findOrFail($request->id);
        $data->city = $request->get('city');
        $data->name = $request->get('name');
        $data->region = $request->get('region');
        $data->street_address = $request->get('street_address');
        $data->save();

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function addproduct(Request $request)
    {
        // dd($request->all());
        if ($request->get('qty') <= $request->get('stock_')) {
            # code...

            $product_id = $request->get('product_id');
            $shop_id = $request->get('shop_id');
            $qty = $request->get('qty');

            $cek = count(\DB::table('product_shop')->where(['product_id' => $product_id,  'shop_id' => $shop_id])->get());
            if ($cek > 0) {
                $qtyNow = \DB::table('product_shop')->where(['product_id' => $product_id,  'shop_id' => $shop_id])->value('temp_stock');
                \DB::table('product_shop')->where(['product_id' => $product_id,  'shop_id' => $shop_id])->update([
                    'temp_stock' => $qtyNow + $qty
                ]);
            } else {
                \DB::table('product_shop')->insert([
                    'shop_id' => $shop_id,
                    'product_id' => $product_id,
                    'temp_stock' => $qty,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }

            return back()->with('success', 'Produk berhasil ditambahkan');
        } elseif ($request->get('qty') > $request->get('stock_')) {
            return back()->with('danger', 'Stock yang dimasukkan melebihi stock yang ada');
        }
    }

    public function product($id)
    {
        $id_ = $id;
        $product = Product::all();
        return view('admin.shop.product', compact(['id_', 'product']));
    }

    public function getproduct(Request $request, $id)
    {
        if (!empty($request->from_date)) {
            DB::statement(DB::raw('set @rownum=0'));
            $barang = DB::table('product_shop')->where('shop_id', $id)->whereBetween('tanggal', array($request->from_date, $request->to_date))->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'product_id',
                'temp_stock',
            ]);
        } else {

            DB::statement(DB::raw('set @rownum=0'));
            $barang = DB::table('product_shop')->where('shop_id', $id)->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'shop_id',
                'product_id',
                'temp_stock',
            ]);
        }

        // dd($barang)


        $datatables = Datatables::of($barang)
            ->editColumn('product_id', function ($row) {
                $name = DB::table('products')->where('id', $row->product_id)->value('product_name');
                $warna = DB::table('products')->where('id', $row->product_id)->value('warna');
                return $name . ' - ' . $warna;
            })
            ->addColumn('harga', function ($row) {
                return number_format(DB::table('products')->where('id', $row->product_id)->value('price'));
            })
            ->addColumn('action', function ($row) {
                $btn = '<button id="edit-user" data-toggle="modal" data-target="#editModal" data-name="' . DB::table('products')->where('id', $row->product_id)->value('product_name') . '" data-warna="' . DB::table('products')->where('id', $row->product_id)->value('warna') . '" data-id="' . $row->id . '" data-product_id = "' . DB::table('products')->where('id', $row->product_id)->value('id') . '" data-harga="' . DB::table('products')->where('id', $row->product_id)->value('price') . '" data-stock = "' . $row->temp_stock . '" class="delete btn btn-info btn-sm">
                <i class="fas fa-edit"></i>
                </button>';
                return $btn;
            })
            ->rawColumns(['product_id', 'harga', 'action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }

    public function getstock($id)
    {
        $stock_ = Product::where('id', $id)->value('stock');
        $stock__ = \DB::table('product_shop')->where('product_id', $id)->sum('temp_stock');
        $stock = ($stock_ - $stock__);
        return response()->json([
            'stock' => $stock,
        ]);
    }


    public function addstock(Request $request)
    {
        // dd($request->all());
        if ($request->get('exampleRadios') == "tambah") {
            $stock = DB::table('product_shop')->where('id', $request->id)->value('temp_stock');
            $add = $stock + $request->get('stock');
            if ($request->get('stock') <= $request->get('temp_stock_')) {
                $data = DB::table('product_shop')->where('id', $request->id)->update([
                    'temp_stock' => $add
                ]);

                return back()->with('success', 'Stock berhasil ditambahkan');
            } else {
                return back()->with('danger', 'Stock yang dimasukkan melebihi stock yang ada');
            }
        } else {
            // dd($request->id);
            $stock = DB::table('product_shop')->where('id', $request->id)->value('temp_stock');
            $add = $stock - $request->get('stock');
            if ($request->get('stock') <= $stock) {
                $data = DB::table('product_shop')->where('id', $request->id)->update([
                    'temp_stock' => $add
                ]);

                return back()->with('success', 'Stock berhasil ditarik');
            } else {
                return back()->with('danger', 'Stock yang dimasukkan melebihi stock yang ada');
            }
        }
    }
    public function user($id)
    {
        $id_ = $id;
        $pegawai = User::whereNotIn('id', ['153127'])->get()->all();
        return view('admin.shop.pegawai', compact(['id_', 'pegawai']));
    }

    public function adduser(Request $request)
    {
        // dd($request->all());
        $user_id = $request->get('user_id');
        $shop_id = $request->get('shop_id');

        $cek = count(\DB::table('shop_user')->where(['user_id' => $user_id])->get());
        if ($cek > 0) {
            return back()->with('danger', 'Pegawai Sudah di Tambahkan');
        } else {
            \DB::table('shop_user')->insert([
                'shop_id' => $shop_id,
                'user_id' => $user_id,
            ]);
            return back()->with('success', 'Pegawai berhasil ditambahkan');
        }
    }

    public function getuser($id)
    {
        DB::statement(DB::raw('set @rownum=0'));
        $barang = DB::table('shop_user')->where('shop_id', $id)->select([
            DB::raw('@rownum  := @rownum  + 1 AS rownum'),
            'id',
            'shop_id',
            'user_id',
        ]);

        // dd($barang)


        $datatables = Datatables::of($barang)
            ->editColumn('user_id', function ($row) {
                // $product_id = $barang->id;
                return DB::table('users')->where('id', $row->user_id)->value('name');
            })
            ->addColumn('email', function ($row) {
                return DB::table('users')->where('id', $row->user_id)->value('email');
            })
            ->addColumn('action', function ($row) {
                $btn = '<button id="edit-user" data-toggle="modal" data-target="#editModal" data-id="' . $row->id . '"   class="delete btn btn-danger btn-sm">
                <i class="fas fa-trash"></i>
                </button>';
                return $btn;
            })
            ->rawColumns(['user_id', 'email', 'action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }
    public function deletuser(Request $request)
    {
        $data = DB::table('shop_user')->where('id', $request->get('id'))->delete();
        // $data->delete();
        return back()->with('success', 'Pegawai berhasil di hapus');
    }

    public function rekapstocktoko($id)
    {
        // dd($id);
        $tanggal = date('d M Y', strtotime(Carbon::now()));
        $data = \DB::table('product_shop')->where('shop_id', $id)->get()->all();
        // dd($data);
        return Excel::download(new RekapStockExport($data), "rekap_stock_$tanggal.xlsx");
    }

    public function rekapstock()
    {
        $tanggal = date('d M Y', strtotime(Carbon::now()));
        $data = \DB::table('products')->get()->all();
        // dd($data);
        return Excel::download(new RekapStockExport($data), "rekap_stock_$tanggal.xlsx");
    }
}
