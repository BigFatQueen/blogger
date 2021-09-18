<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Brand;
use App\Helper\Log;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() 
    {   
        $categories = Category::orderBy('id', 'desc')->get();
        
        $log = new Log();
        $log->setOpt("Category Log", 1, "Admin");
        $log->setReq('All', "Category", "Select", json_encode($categories));
        $log->store();
        return view('backend.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('backend.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name"=> 'required|min:3|max:50',                      
        ]);
        $category=new Category;
        $category->name =request('name');              
        $category->save();

        $log = new Log();
        $log->setOpt("Category Log", 1, "Admin");
        $log->setReq('All', "Category", "Store", json_encode($category));
        $log->store();

        return redirect()->route('admin.category.index')->with('status','Category was successfully added!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Facility  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Facility  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $id = \App\Helper\Crypt::crypt()->decrypt( $id );
        $category= Category::find($id);
        return view('backend.category.edit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = \App\Helper\Crypt::crypt()->decrypt( $id );
        
        $request->validate([
            "name"=> 'required|min:3|max:50',
        ]);
        $category=Category::find($id);
        $category->name =request('name');         
        $category->save();
        return redirect()->route('admin.category.index')->with('status','Category was successfully updated!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Facility  $facility
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $id = \App\Helper\Crypt::crypt()->decrypt($id);
            $category=Category::find($id);         
            $category->delete();
            return redirect()->route('admin.category.index')->with('status','Category was successfully deleted!!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.category.index')->with('status','You can\'t delete this category.This category was used by others.');
        }
    }

}
