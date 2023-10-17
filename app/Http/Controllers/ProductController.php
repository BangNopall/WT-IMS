<?php

namespace App\Http\Controllers;

use App\Product;
use App\Category;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
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
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

        return view('products.index', compact('category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id');

            $editteks = "Tambah Barang";

        return view('products.create', compact('category', 'editteks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
         // Validasi input data
         $validator = Validator::make($request->all(), [
             'nama' => 'required|string',
             'image' => 'required',
             'category_id' => 'required',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'success' => false,
                 'message' => 'Terjadi kesalahan dalam validasi input',
                 'errors' => $validator->errors()
             ], 422);
         }

         // Mengunggah gambar
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $imageName = time() . '.' . $image->getClientOriginalExtension();
             $imagePath = 'upload/products/';
             $image->move(public_path($imagePath), $imageName);
         } else {
             return response()->json([
                 'success' => false,
                 'message' => 'Gambar wajib diunggah'
             ], 422);
         }
     
         // Membuat entri produk baru
         $product = new Product();
         $product->nama = $request->input('nama');
         $product->image = $imagePath . $imageName;
         $product->category_id = $request->input('category_id');
         $product->save();

         return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
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
        // Ambil data barang berdasarkan ID
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('barang.index')->with('error', 'Data barang tidak ditemukan');
        }

        // Ambil data kategori (sesuaikan dengan model kategori Anda)
        $category = Category::orderBy('name','ASC')
            ->get()
            ->pluck('name','id'); 

        $editteks = "Edit Barang";

        return view('products.edit', compact('product', 'category', 'editteks'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request)
     {
         // Validasi input form
         $request->validate([
             'nama' => 'required',
             'category_id' => 'required',
             'image' => '', // Sesuaikan dengan kebutuhan
         ]);
     
         // Ambil data barang berdasarkan ID
         $product = Product::find($request->input('id'));
     
         if (!$product) {
             return redirect()->route('barang.index')->with('error', 'Data barang tidak ditemukan');
         }
     
         // Handle jika ada gambar baru diunggah
         if ($request->hasFile('image')) {
             $image = $request->file('image');
     
             // Validasi gambar
             if (!$image->isValid()) {
                 return response()->json([
                     'success' => false,
                     'message' => 'Gambar tidak valid'
                 ], 422);
             }
     
             // Generate nama file yang unik
             $imageName = time() . '.' . $image->getClientOriginalExtension();
             $imagePath = 'upload/products/' . $imageName;
     
             // Pindahkan gambar ke direktori yang sesuai
             $image->move(public_path('upload/products'), $imageName);
     
             // Hapus gambar lama jika ada
             if (!empty($product->image) && file_exists(public_path($product->image))) {
                 unlink(public_path($product->image));
             }
     
             // Simpan path gambar baru ke dalam kolom 'image' di database
             $product->image = $imagePath;
         }
     
         // Update data barang
         $product->nama = $request->input('nama');
         $product->category_id = $request->input('category_id');
         $product->save();
     
         return redirect()->route('barang.index')->with('success', 'Data barang berhasil diupdate');
     }
     



//     public function update(Request $request, $id)
//     {
//         $category = Category::orderBy('name','ASC')
//             ->get()
//             ->pluck('name','id');

//         $this->validate($request , [
//             'nama'          => 'required|string',
// //            'image'         => 'required',
//             'category_id'   => 'required',
//         ]);

//         $input = $request->all();
//         $produk = Product::findOrFail($id);

//         $input['image'] = $produk->image;

//         if ($request->hasFile('image')){
//             if (!$produk->image == NULL){
//                 unlink(public_path($produk->image));
//             }
//             $input['image'] = '/upload/products/'.str_slug($input['nama'], '-').'.'.$request->image->getClientOriginalExtension();
//             $request->image->move(public_path('/upload/products/'), $input['image']);
//         }

//         $produk->update($input);

//         return response()->json([
//             'success' => true,
//             'message' => 'Barang berhasil diupdate'
//         ]);
//     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (!$product->image == NULL){
            unlink(public_path($product->image));
        }

        Product::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Barang dihapus'
        ]);
    }

    public function apiProducts(){
        $product = Product::all();

        return Datatables::of($product)
            ->addColumn('category_name', function ($product){
                return $product->category->name;
            })
            ->addColumn('show_photo', function($product){
                if ($product->image == NULL){
                    return 'No Image';
                }
                return '<img class="rounded-square" width="50" height="50" src="'. url($product->image) .'" alt="">';
            })
            ->addColumn('action', function($product){
                return'<a href="/barang/edit/'. $product->id .'" class="btn btn-primary btn-xs"><i class="glyphicon glyphicon-edit"></i> Edit</a> ' .
                    '<a onclick="deleteData('. $product->id .')" class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            })
            ->rawColumns(['category_name','show_photo','action'])->make(true);

    }
}
