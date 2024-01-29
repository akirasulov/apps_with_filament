<?php

namespace App\Livewire;

use App\Models\Attendee;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ConferenceSignUpPage extends Component implements HasForms, HasActions
{
    use InteractsWithActions, InteractsWithForms;

    public int $conferenceID;
    public int $price = 5000;
    public function mount()
    {
        $this->conferenceID = 1;

    }
    public function render()
    {
        return view('livewire.conference-sign-up-page');
    }

    public function signUpAction(): Action
    {
        return Action::make('signUp')
            ->slideOver()
            ->form([
                Placeholder::make('total_price')
                    ->hiddenLabel()
                    ->content(function (Get $get) {
                        return '$' . count($get('attendees')) * 500;
                    }),
                Repeater::make('attendees')
                    ->schema(Attendee::getForm()),
                TextInput::make('name'),
            ])
            ->action(function (array $data) {
                collect($data['attendees'])->each(function ($data) {
                    Attendee::create([
                        'conference_id' => $this->conferenceID,
                        'ticket_cost' => $this->price,
                        'name' => $data['name'],
                        'email' => $data['email'],
                        'is_paid' => true,
                    ]);
                });
            })
            ->after(function () {
                Notification::make()
                    ->success()
                    ->title('Success')
                    ->body(new HtmlString('You have successfully signed up.'))
                    ->send();
            });
    }
}
