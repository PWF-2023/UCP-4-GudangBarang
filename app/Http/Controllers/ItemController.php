<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangs = Item::with('category')->where('user_id', auth()->user()->id)
        ->orderBy('is_in', 'asc')
        ->orderBy('created_at', 'desc')
        ->get();
        $barangsComplete = Item::where('user_id', auth()->id)
        ->where('is_in', true)
        ->count();
        return view('item.index', compact('items', 'itemsCompleted'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('user_id', auth()->id)->get();
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
            'name' => ucfirst($request->title),
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
            'name' => ucfirst($request->title),
            'category_id' => $request->category_id
        ]);
        return redirect()->route('barang.index')->with('Sukses', 'Barang Berhasil di Update!!');
    }

    public function in(Item $item)
    {
        if (auth()->user()->id == $item->user_id) {
            $item->update([
                'is_in' => true,
            ]);
            return redirect()->route('item.index')->with('success', 'Todo completed successfully!');
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to complete this todo!');
        }
    }
    public function out(Item $item)
    {
        if (auth()->user()->id == $item->user_id) {

            $item->update([
                'is_out' => false,
            ]);
            return redirect()->route('item.index')->with('success', 'Todo uncompleted successfully!');
        } else {
            return redirect()->route('item.index')->with('danger','You are not authorized to uncomplete this todo!');
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
            return redirect()->route('item.index')->with('danger','You are not authorized to delete this todo!');
        }
    }

    public function destroyCompleted()
    {
        $itemsCompleted = Item::where('user_id', auth()->user()->id)
            ->where('is_in', true)
            ->get();
        foreach ($itemsCompleted as $item) {
            $item->delete();
        }
        // ($todosCompleted);
        return redirect()->route('item.index')->with('Sukses', 'Semua barang yang sudah selesai berhasil dihapus!');
    }
}
