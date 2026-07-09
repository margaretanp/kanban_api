<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'board_id' => 'required|exists:boards,id',
            'column_id' => 'required|exists:columns,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'priority' => 'required|string',
            'deadline' => 'required|date',
        ]);

        $board = Board::find($request->board_id);

        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $card = Card::create([
            'board_id' => $request->board_id,
            'column_id' => $request->column_id,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'deadline' => $request->deadline,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Card created successfully.',
            'data' => $card
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        $request->validate([
            'board_id' => 'sometimes|exists:boards,id',
            'column_id' => 'sometimes|exists:columns,id',
            'title' => 'sometimes|string',
            'description' => 'nullable|string',
            'priority' => 'sometimes|string',
            'deadline' => 'sometimes|date',
        ]);

        $board = Board::find($card->board_id);
        
        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $card->update($request->only([
            'board_id', 'column_id', 'title', 'description', 'priority', 'deadline'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Card updated successfully.',
            'data' => $card
        ]);
    }

    /**
     * Move the specified card to a different column.
     */
    public function move(Request $request, Card $card)
    {
        $request->validate([
            'target_column_id' => 'required|exists:columns,id',
        ]);

        $cardBoard = Board::find($card->board_id);
        $targetColumn = Column::find($request->target_column_id);
        $targetColumnBoard = Board::find($targetColumn->board_id);

        if ($cardBoard->user_id !== auth()->id() || $targetColumnBoard->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $card->update([
            'column_id' => $targetColumn->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Card moved successfully.',
            'data' => $card
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        $board = Board::find($card->board_id);
        
        if ($board->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden'
            ], 403);
        }

        $card->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card deleted successfully.'
        ]);
    }
}
