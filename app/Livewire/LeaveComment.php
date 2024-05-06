<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class LeaveComment extends Component
{
    public $commentText;

    public function render()
    {
        return view('livewire.leave-comment');
    }

    public function addComment($latitude= "40.409264", $longitude= "49.867092"){
        $this->validate([
            'commentText' => 'required|string|max:255',
        ]);

        Comment::create([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'text' => $this->commentText,
            'user_id' => Auth::id(),
            'status' => 1
        ]);

        // $this->emit('commentAdded', [
        //     'latitude' => $latitude,
        //     'longitude' => $longitude,
        //     'commentText' => $this->commentText,
        // ]);

        $this->reset('commentText');

        // Emit a Livewire event to update the UI or map
        $this->dispatch('commentAdded', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'commentText' => $this->commentText,
        ]);

    }
}
