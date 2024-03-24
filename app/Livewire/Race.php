<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\UserClickedEvent;
use App\Events\UserResetClicksEvent;
use App\Events\StartUserClicksEvent;
use App\Models\User;

class Race extends Component
{

    public $user;

    public function mount($user)
    {
        $this->user = $user;
        auth()->login($this->user);
    }

    public function increment()
    {
        $this->user->clicks++;
        $this->user->save();
        UserClickedEvent::dispatch($this->user);
    }

    public function resetClicks() {
        $all_users = User::all();
        foreach ($all_users as $user) {
            $user->clicks = 0;
            $user->save();
        }
        UserResetClicksEvent::dispatch();
        $this->user->clicks = 0;
    }

    public function startUserClicks() {
        StartUserClicksEvent::dispatch();
    }

    public function render()
    {
        return view('livewire.race');
    }
}
