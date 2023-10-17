<?php

namespace App\Http\Controllers;


use PDF;
use App\Discord;
use App\Product;
use App\Customer;
use App\Product_Masuk;
use Illuminate\Http\Request;
use App\Notifications\wtnotif;
use Yajra\DataTables\DataTables;
use App\Exports\ExportProdukMasuk;


class ProductMasukController extends Controller
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

        $invoice_data = Product_Masuk::all();
        return view('product_masuk.index', compact('products','customers','invoice_data'));
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

        Product_Masuk::create($request->all());

        $product_name = Product::where('id', $request->product_id)->first();
        $customer_name = Customer::where('id', $request->customer_id)->first();

        $product = Product::findOrFail($request->product_id);
        $product->qty += $request->qty;
        $product->save();

        $discord = Discord::find(1);
        $productData  = [
            'title' => 'Logs Deposit Barang Brankas',
            'id' => $product->id,
            'product_id' => $product_name->nama,
            'customer_id' => $customer_name->nama,
            'qty' => $request->qty,
            'tanggal' => $request->tanggal,
        ];
        $discord->notify(new wtnotif($productData));

        return response()->json([
            'success'    => true,
            'message'    => 'Barang Ditambahkan'
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
        $product_masuk = Product_Masuk::find($id);
        return $product_masuk;
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
    public function destroy(Request $request, $id)
    {
        $productMasuk = Product_Masuk::find($id);

        if (!$productMasuk) {
            return response()->json([
                'success' => false,
                'message' => 'DP Barang tidak ditemukan'
            ], 404);
        }

        $product = Product::find($productMasuk->product_id);
        
        if ($product) {
            $product->qty -= $productMasuk->qty;
            $product->save();
        }

        $productMasuk->delete();    

        return redirect()->route('dpbarang.index')->with('success', 'DP Barang berhasil di hapus');
    }



    public function apiProductsIn(){
        $product = Product_Masuk::all();

        return Datatables::of($product)
            ->addColumn('products_name', function ($product){
                return $product->product->nama;
            })
            ->addColumn('customer_name', function ($product){
                return $product->customer->nama;
            })
            ->addColumn('action', function($product){
                return view('product_masuk.button', ['productMasuk' => $product]);


            })
            ->rawColumns(['products_name','customer_name','action'])->make(true);

    }

    public function exportProductMasukAll()
    {
        $product_masuk = Product_Masuk::all();
        $pdf = PDF::loadView('product_masuk.productMasukAllPDF',compact('product_masuk'));
        return $pdf->download('dpbarang.pdf');
    }

    public function exportProductMasuk($id)
    {
        $product_masuk = Product_Masuk::findOrFail($id);
        $pdf = PDF::loadView('product_masuk.productMasukPDF', compact('product_masuk'));
        return $pdf->download($product_masuk->id.'_dpbarang.pdf');
    }

    public function exportExcel()
    {
        return (new ExportProdukMasuk)->download('product_masuk.xlsx');
    }
}
