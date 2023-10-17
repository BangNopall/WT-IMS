<?php

namespace App\Http\Controllers;

use PDF;
use App\Discord;
use App\Product;
use App\Category;
use App\Customer;
use App\Product_Keluar;
use Illuminate\Http\Request;
use App\Notifications\wtnotif;
use Yajra\DataTables\DataTables;
use App\Exports\ExportProdukKeluar;


class ProductKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin,staff');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

        $customers = Customer::orderBy('nama','ASC')
            ->get()
            ->pluck('nama','id');

        $invoice_data = Product_Keluar::all();
        return view('product_keluar.index', compact('products','customers', 'invoice_data'));
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
        $this->validate($request, [
           'product_id'     => 'required',
           'customer_id'    => 'required',
           'qty'            => 'required',
           'tanggal'        => 'required'
        ]);

        Product_Keluar::create($request->all());

        $product_name = Product::where('id', $request->product_id)->first();
        $customer_name = Customer::where('id', $request->customer_id)->first();

        $product = Product::findOrFail($request->product_id);
        $product->qty -= $request->qty;
        $product->save();

        $discord = Discord::find(2);
        $productData  = [
            'title' => 'Logs Widthdraw Barang Brankas',
            'id' => $product->id,
            'product_id' => $product_name->nama,
            'customer_id' => $customer_name->nama,
            'qty' => $request->qty,
            'tanggal' => $request->tanggal,
        ];
        $discord->notify(new wtnotif($productData));

        return response()->json([
            'success'    => true,
            'message'    => 'WD Barang ditambahkan'
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product_keluar = Product_Keluar::find($id);
        return $product_keluar;
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
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     Product_Keluar::destroy($id);

    //     return response()->json([
    //         'success'    => true,
    //         'message'    => 'WD Barang berhasil di hapus'
    //     ]);
        
    // }
    public function destroy(Request $request, $id)
    {
        // 1. Dapatkan data produk_keluar yang akan dihapus.
        $productKeluar = Product_Keluar::find($id);

        if (!$productKeluar) {
            return response()->json([
                'success' => false,
                'message' => 'WD Barang tidak ditemukan'
            ], 404);
        }

        $product = Product::find($productKeluar->product_id);
        if ($product) {
            $product->qty += $productKeluar->qty;
            $product->save();
        }

        $productKeluar->delete();    

        return redirect()->route('wdbarang.index')->with('success', 'WD Barang berhasil di hapus');
    }




    public function apiProductsOut(){
        $product = Product_Keluar::all();

        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->nama;
            })
            ->addColumn('customer_name', function ($product){
                return $product->customer->nama;
            })
            ->addColumn('action', function($product){
                // return'<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                return view('product_keluar.button', ['productKeluar' => $product]);

            })
            ->rawColumns(['products_name','customer_name','action'])->make(true);

    }

    public function exportProductKeluarAll()
    {
        $product_keluar = Product_Keluar::all();
        $pdf = PDF::loadView('product_keluar.productKeluarAllPDF',compact('product_keluar'));
        return $pdf->download('wdbarang.pdf');
    }

    public function exportProductKeluar($id)
    {
        $product_keluar = Product_Keluar::findOrFail($id);
        $pdf = PDF::loadView('product_keluar.productKeluarPDF', compact('product_keluar'));
        return $pdf->download($product_keluar->id.'_wdbarang.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukKeluar)->download('product_keluar.xlsx');
    }
}
