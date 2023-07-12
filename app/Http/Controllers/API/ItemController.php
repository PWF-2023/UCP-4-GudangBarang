<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\AuthController;
// use App\Http\Controllers\API\ProfileController;
// use App\Http\Controllers\API\ItemController;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $search = request('search');
        if($search){
            $items = Item::with('category')
            ->where('user_id', auth()->user()->id)
            ->where(function ($query) use($search){
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();
            return response()->json([
                'status' => 'success',
                'data' => [
                    'items' => $items,
                    ]
            ], 200);
        }

        $items = Item::with('category')
        ->where('user_id', auth()->user()->id)
        ->latest()
        ->get();
        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $items
            ]
        ], 200);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item = Item::with('category')
        ->where('id', $item->id)
        ->first();
        if ($item->user_id != auth()->user()->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }
        return response()->json([
            'status'=> 'success',
            'data' => [
                'item' => $item,
                ]
            ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}