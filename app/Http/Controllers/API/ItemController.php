<?php

namespace App\Http\Controllers\API;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
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
        try {
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

            $item = Item::with('category')
                ->where('id', $item->id)
                ->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Item created',
                'data' => [
                    'item' => $item,
                ]
            ], 201);

        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }
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
        try {
            $request->validate([
                'name' => 'required|max:255',
                'category_id' => [
                    'nullable',
                    Rule::exists('categories', 'id')->where(function ($query) {
                        $query->where('user_id', auth()->user()->id);
                    })
                ]
            ]);
            if (auth()->user()->id !== $item->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden'
                ], 403);
            }
            $item->update([
                'title' => ucfirst($request->title),
                'category_id' => $request->category_id
            ]);
            $item = Item::with('category')
                ->where('id', $item->id)
                ->first();
            return response()->json([
                'status' => 'success',
                'message' => 'Todo updated',
                'data' => [
                    'todo' => $item,
                ]
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if (auth()->user()->id !== $item->user_id) {

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }
        $item->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Item deleted'
        ], 200);
    }

    public function in(Item $item)
    {
        if (auth()->user()->id !== $item->user_id) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }
        $item->update([
            'is_in' => true
        ]);
        $item = Item::with('category')
            ->where('id', $item->id)
            ->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Item In',
            'data' => [
                'item' => $item,
            ]
        ], 200);
    }

    public function out(Item $item)
    {
        if (auth()->user()->id !== $item->user_id) {

            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden'
            ], 403);
        }
        $item->update([
            'is_in' => false
        ]);
        $item = Item::with('category')
            ->where('id', $item->id)
            ->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Item Out',
            'data' => [
                'item' => $item,
            ]
        ], 200);
    }

    public function deleteAllIn()
    {
        $items = Item::where('user_id', auth()->user()->id)
            ->where('is_in', true)
            ->get();
        if ($items->count() == 0) {
            # code...
            return response()->json([
                'status' => 'error',
                'message' => 'No in item found',
            ], 404);
        }

        foreach ($items as $item) {
            $item->delete();
        }
        return response()->json([
            'status' => 'success',
            'message' => '' . $item->count() . ' in item deleted',
        ], 200);
    }
}
