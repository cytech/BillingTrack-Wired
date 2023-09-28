<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Categories\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Categories\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the product.
     */
    public function index()
    {
        $modulefullname = Category::class;
        return view('categories.index')->with('modulefullname', $modulefullname);
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created product in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $categories = new Category;
        $categories->name = $request->name;
        $categories->save();

        return redirect()->route('categories.index')->with('alertInfo', trans('bt.record_successfully_created'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $categories = Category::find($id);

        return view('categories.edit', compact('categories'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $categories = Category::find($id);
        $categories->name = $request->name;
        $categories->save();

        return redirect()->route('categories.index')->with('alertInfo', trans('bt.record_successfully_updated'));
    }
}
