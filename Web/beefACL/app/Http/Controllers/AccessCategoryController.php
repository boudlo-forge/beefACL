<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;
use App\AccessCategory;
use Validator;

class AccessCategoryController extends Controller
{
    public static $validations = [
        'name' => 'required',
    ];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = null)
    {
        $accessCategory = new AccessCategory();

        if(!empty($id) && is_numeric($id)) {
            $accessCategory = AccessCategory::findOrFail($id);
        }

        $data = [
            'accessCategory' => $accessCategory,
        ];

        return view('access_categories.form', $data);
    }

    public function list()
    {
        $accessCategories = AccessCategory::where('id','>','0');

        $data = [
            'accessCategories' => $accessCategories->paginate(50)
        ];

        return view('access_categories.list', $data);
    }

    public function store(Request $request)
    {
        $accessCategory = new AccessCategory();

        if(!$request->validate(self::$validations)) {
            return Redirect::back()->withErrors();
        }

        $accessCategory->name = $request->name;

        $accessCategory->save();

        return redirect('access_categories');
    }
}
