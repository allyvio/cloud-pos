<?php

namespace App\Http\Livewire;

use App\Code;
use App\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ProdukKasir extends Component
{
    public $search;
    use WithPagination;

    public function render()
    {
        $user_id = Auth::user()->id;
        $code = Code::where('user_id', $user_id)->value('code');
        $shopUser = DB::table('shop_user')->where('user_id', $user_id)->first()->shop_id;

        if ($this->search == null) {
            $data = DB::table('products')
                ->join('product_shop', 'products.id', '=', 'product_shop.product_id')
                ->where('product_shop.shop_id', $shopUser)
                ->select('product_shop.temp_stock', 'products.product_name', 'products.warna', 'products.price', 'products.id')
                ->orderBy('product_shop.temp_stock', 'desc')
                ->take(14)
                ->get();
        } else {
            $data = DB::table('products')
                ->join('product_shop', 'products.id', '=', 'product_shop.product_id')
                ->where('product_shop.shop_id', $shopUser)
                ->where('products.product_name', 'like', '%' . $this->search . '%')
                ->select('product_shop.temp_stock', 'products.product_name', 'products.warna', 'products.price', 'products.id')
                ->orderBy('product_shop.temp_stock', 'desc')
                ->get();
        }

        $temp_order = DB::table('orders')
            ->join('products', 'orders.product_id', '=', 'products.id')
            ->where('orders.user_id', $user_id)
            ->where('orders.code', $code)
            ->select('products.product_name', 'orders.qty', 'products.price', 'products.warna', 'orders.id')
            ->get();


        return view('livewire.produk-kasir', ['data' => $data, 'search' => $this->search, 'id_shop' => $shopUser, 'code' => $code, 'temp_order' => $temp_order]);
    }

    public function selectThis($id_product, $id_shop, $code)
    {
        $user_id = Auth::user()->id;
        $x = DB::table('product_shop')->where(['shop_id' => $id_shop, 'product_id' => $id_product])->value('temp_stock');

        $cek = DB::table('orders')->where('product_id', $id_product)->where('code', $code)->where('user_id', $user_id)->first();

        if ($x > 0) {
            if ($cek != null) {
                if ($x > $cek->qty) {
                    $qtyNow = $cek->qty;
                    DB::table('orders')->where('product_id', $id_product)->where('code', $code)->where('user_id', $user_id)->update([
                        'qty' => $qtyNow + 1
                    ]);
                }
            } else {
                DB::table('orders')->insert([
                    'user_id' => $user_id,
                    'code' => $code,
                    'product_id' => $id_product,
                    'qty' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

    public function plusQty($id)
    {
        $currentQty = DB::table('orders')->where('id', $id)->first()->qty;
        DB::table('orders')->where('id', $id)->update([
            'qty' => $currentQty + 1
        ]);
    }

    public function minusQty($id)
    {
        $currentQty = DB::table('orders')->where('id', $id)->first()->qty;
        if ($currentQty == 1) {
            DB::table('orders')->where('id', $id)->delete();
        } else {
            DB::table('orders')->where('id', $id)->update([
                'qty' => $currentQty - 1
            ]);
        }
    }
}
