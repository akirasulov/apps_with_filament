<?php

namespace App\Models;

use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Talk extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'speaker_id' => 'integer',
    ];

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Speaker::class);
    }

    public function conferences(): BelongsToMany
    {
        return $this->belongsToMany(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Section::make('Conference Details')
                ->collapsible()
                ->description('Provide some basic information about the conference.')
                ->icon('heroicon-o-information-circle')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('abstract')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('speaker_id')
                        ->relationship('speaker', 'name')
                        ->required(),
                ]),
            Actions::make([
                Action::make('star')
                    ->label('Fill with Factory Data')
                    ->icon('heroicon-m-star')
                    ->visible(function (string $operation) {
                        if ($operation !== 'create') {
                            return false;
                        }
                        if (!app()->environment('local')) {
                            return false;
                        }
                        return true;
                    })
                    ->action(function ($livewire) {
                        $data = Talk::factory()->make()->toArray();
                        $livewire->form->fill($data);
                    }),
            ]),

        ];
    }
}
