<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::with('category')->where('user_id', auth()->user()->id)
        ->orderBy('is_in', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();
        $itemsOut = Item::where('user_id', auth()->user()->id)
        ->where('is_in', true)
        ->count();
        return view('item.index', compact('items', 'itemsOut'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();
        return view('item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })
            ]
        ]);

        $item = Item::create([
            'name' => ucfirst($request->name),
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id
        ]);
        return redirect()->route('item.index')->with('Sukses', 'Barang Berhasil Ditambahkan!!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $categories = Category::where('user_id', auth()->user()->id)->get();
        if (auth()->user()->id == $item->user_id) {
            return view('item.edit', compact('item', 'categories'));
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to edit this!!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('user_id', auth()->user()->id);
                })
            ]
        ]);

        $item->update([
            'name' => ucfirst($request->name),
            'category_id' => $request->category_id
        ]);
        return redirect()->route('item.index')->with('Sukses', 'Barang Berhasil di Update!!');
    }

    public function in(Item $item)
    {
        if (auth()->user()->id == $item->user_id) {
            $item->update([
                'is_in' => true,
            ]);
            return redirect()->route('item.index')->with('success', 'Item In successfully!');
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to In this Item!');
        }
    }
    public function out(Item $item)
    {
        if (auth()->user()->id == $item->user_id) {

            $item->update([
                'is_in' => false,
            ]);
            return redirect()->route('item.index')->with('success', 'Item Out successfully!');
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to Out this Item!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if (auth()->user()->id == $item->user_id) {
            $item->delete();
            return redirect()->route('item.index')->with('Sukses', 'Barang Berhasil di Hapus!!');
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to delete this Item!');
        }
    }

    public function destroyOut()
    {
        $itemsOut = Item::where('user_id', auth()->user()->id)
            ->where('is_in', true)
            ->get();
        foreach ($itemsOut as $item) {
            $item->delete();
        }
        // ($itemsCompleted);
        return redirect()->route('item.index')->with('Sukses', 'Semua barang yang sudah selesai berhasil dihapus!');
    }
}
