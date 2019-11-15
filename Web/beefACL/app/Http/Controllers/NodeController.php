<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Node;
use App\AccessCategory;
use Validator;

class NodeController extends Controller
{
    public static $validations = [
        'name' => 'required',
        'mac_address' => 'regex:/\A^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$\z/i',
        'access_category_id' => 'exists:access_categories,id',
        'status_id' => 'required',
    ];

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function form($id = null)
    {
        $node = new Node();

        if(!empty($id) && is_numeric($id)) {
            $node = Node::findOrFail($id);
        }

        $data = [
            'node' => $node,
            'statuses' => Node::$statuses,
            'accessCategoryIds' => AccessCategory::listForSelect(),
        ];

        return view('nodes.form', $data);
    }

    public function list()
    {
        $nodes = Node::where('id','>','0')->paginate(50);

        $data = [
            'statuses' => Node::$statuses,
            'nodes' => $nodes,
        ];

        return view('nodes.list', $data);
    }

    public function store($id = null, Request $request)
    {
        $node = empty($id) ? new Node() : Node::findOrFail($id);

        if(!$request->validate(self::$validations)) {
            return Redirect::back()->withErrors();
        }

        $node->name                 = $request->name;
        $node->description          = $request->description;
        $node->mac_address          = $request->mac_address;
        $node->vendor_ref           = $request->config_flags;
        $node->access_category_id   = $request->access_category_id;
        $node->status_id            = $request->status_id;

        $node->save();

        return redirect('nodes');
    }
}
