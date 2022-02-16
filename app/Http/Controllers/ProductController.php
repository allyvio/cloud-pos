<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Category;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $active = 'produk';
        $category = Category::all();
        // dd($category);
        return view('admin.product.index', compact('category', 'active'));
    }

    public function getproduk(Request $request)
    {
        if (!empty($request->kategori)) {
            DB::statement(DB::raw('set @rownum=0'));
            $barang = DB::table('products')
                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', 'categories.id', '=', 'category_product.category_id')
                ->where('category_id', $request->get('kategori'))
                ->select([
                    DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                    'products.id',
                    'products.product_name',
                    'products.kode',
                    'products.warna',
                    'products.price',
                    'products.harga_beli',
                    'products.stock',
                    'products.diskon',
                    'products.final_price',
                ]);
            // dd($barang);
        } else {
            DB::statement(DB::raw('set @rownum=0'));
            $barang = DB::table('products')->select([
                DB::raw('@rownum  := @rownum  + 1 AS rownum'),
                'id',
                'product_name',
                'kode',
                'warna',
                'price',
                'harga_beli',
                'stock',
                'diskon',
                'final_price',
            ]);
        }


        // dd($barang)


        $datatables = DataTables::of($barang)
            ->editColumn('product_name', function ($row) {
                return $row->product_name . ' - ' . $row->warna;
            })

            ->editColumn('price', function ($row) {
                return number_format($row->price);
            })
            ->editColumn('harga_beli', function ($row) {
                return number_format($row->harga_beli);
            })
            ->editColumn('final_price', function ($row) {
                return number_format($row->final_price);
            })
            ->addColumn('temp_stock', function ($row) {
                $stock = DB::table('product_shop')->where('product_id', $row->id)->sum('temp_stock');
                $stock_ = $row->stock;
                return $stock_ - $stock;
            })
            ->addColumn('action', function ($row) {
                $btn = '<button id="edit-user" data-toggle="modal" data-target="#editModal" data-harga="' . $row->price . '" data-kode="' . $row->kode . '" data-harga_beli="' . $row->harga_beli . '" data-diskon="' . $row->diskon . '" data-final_price="' . $row->final_price . '" data-nama="' . $row->product_name . '" data-id="' . $row->id . '" data-stock="' . $row->stock . '" class="delete btn btn-info btn-sm">
                <i class="fas fa-edit"></i>
                </button>';
                return $btn;
                // dd($row);
            })
            ->rawColumns(['product_name', 'price', 'harga_beli', 'temp_stock', 'action'])
            ->addIndexColumn();

        return $datatables->make(true);
    }

    public function rekap()
    {
        $orders = \App\Order::paginate(10);

        return view('pimpinan.index', compact('orders'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->get('category'));
        $data = new \App\Product;
        // $data->id = rand();
        $data->kode = $request->get('kode');
        $data->product_name = $request->get('nama');
        $data->warna = $request->get('warna');
        $data->price = $request->get('harga');
        $data->final_price = $request->get('harga');
        $data->harga_beli = $request->get('harga_beli');
        $data->stock = $request->get('stock');
        $data->created_by = Auth::user()->name;

        // $data->categories()->attach($request->get('category'));
        $data->save();
        // dd($data->id);

        DB::table('category_product')->insert([
            'product_id' => $data->id,
            'category_id' => $request->get('category'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);


        return back();
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
        if ($request->get('exampleRadios') == "tambah") {
            $data = \App\Product::findOrFail($request->id);
            $final_stock = $data->stock + $request->get('stock');
            $data->product_name = $request->get('nama');
            $data->price = $request->get('harga');
            $data->harga_beli = $request->get('harga_beli');
            $data->diskon = $request->get('diskon');
            $data->final_price = $request->get('harga') - ($request->get('harga') * $request->get('diskon') / 100);
            $data->stock = $final_stock;

            $data->save();

            return redirect()->route('product.index');
        } else {
            $data = \App\Product::findOrFail($request->id);
            $x = \DB::table('product_shop')->where('product_id', $request->id)->sum('temp_stock');
            $y = $data->stock - $x;
            // dd($y);
            if ($request->get('stock') <= $y) {
                # code...
                $final_stock = $data->stock - $request->get('stock');
                $data->product_name = $request->get('nama');
                $data->price = $request->get('harga');
                $data->harga_beli = $request->get('harga_beli');
                $data->diskon = $request->get('diskon');
                $data->final_price = $request->get('harga') - ($request->get('harga') * $request->get('diskon') / 100);
                $data->stock = $final_stock;

                $data->save();

                return redirect()->route('product.index')->with('success', 'Stock berhasil ditarik');
            } else {
                return back()->with('danger', 'Toko masih memiliki stock product ini');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Product::destroy($request->id);
        return redirect()->route('product.index');
    }
}
