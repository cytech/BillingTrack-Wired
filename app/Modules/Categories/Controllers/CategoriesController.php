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
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
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
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created product in storage.
     *
     * @return RedirectResponse
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
     * @return Application|Factory|View
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
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $categories = Category::find($id);
        $categories->name = $request->name;
        $categories->save();

        return redirect()->route('categories.index')->with('alertInfo', trans('bt.record_successfully_updated'));
    }

    public function delete($id)
    {
        Category::destroy($id);

        return redirect()->route('categories.index')
            ->with('alert', trans('bt.record_successfully_deleted'));
    }
}
