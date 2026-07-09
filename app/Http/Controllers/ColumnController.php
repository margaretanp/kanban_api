<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $boardId)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $board = Board::find($boardId);

        if (!$board) {
            return response()->json([
                'success' => false,
                'message' => 'Board not found'
            ], 404);
        }

        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $column = Column::create([
            'board_id' => $board->id,
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Column created successfully.',
            'data' => $column
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Column $column)
    {
        // Check ownership through board relationship
        if ($column->board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $request->validate([
            'title' => 'required|string',
        ]);

        $column->update([
            'title' => $request->title,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Column updated successfully.',
            'data' => $column
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Column $column)
    {
        // Check ownership through board relationship
        if ($column->board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        // Check if column has any cards
        if ($column->cards()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Kolom tidak dapat dihapus karena masih memiliki card'
            ], 400);
        }

        $column->delete();

        return response()->json([
            'success' => true,
            'message' => 'Column deleted successfully.'
        ]);
    }
}
