<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = Board::where('user_id', auth()->id())->get();

        return response()->json([
            'success' => true,
            'data' => $boards
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $board = Board::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Board created successfully.',
            'data' => $board
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $board->load('columns.cards');

        return response()->json([
            'success' => true,
            'data' => $board
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string',
        ]);

        $board->update([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Board updated successfully.',
            'data' => $board
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $board->delete();

        return response()->json([
            'success' => true,
            'message' => 'Board deleted successfully.'
        ]);
    }
}
