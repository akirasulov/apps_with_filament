<?php

namespace App\Models;

use App\Enums\Region;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Conference extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'region' => Region::class,
        'venue_id' => 'integer',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(Speaker::class);
    }

    public function talks(): BelongsToMany
    {
        return $this->belongsToMany(Talk::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\Tabs::make()
                ->columnSpanFull()
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Conference Details')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->columnSpanFull()
                            // ->url()
                                ->label('Conference Name')
                                ->default('Name')
                            // ->prefix('https://example')
                            // ->suffix('.com')
                            // ->prefixIcon('heroicon-o-rectangle-stack')
                                ->required()
                            // ->hint('Here is the hint')
                            // ->hintIcon('heroicon-o-rectangle-stack')
                            // ->helperText('The name of the conference.')
                            // ->markAsRequired(false)
                                ->maxLength(60),
                            // RichEditor
                            // ->disableToolbarButtons(['italic'])
                            // ->toolbarButtons(['h2', 'bold'])
                            Forms\Components\MarkDownEditor::make('description')
                                ->columnSpanFull()
                                ->helperText('Hello')
                                ->required(),
                            Forms\Components\DateTimePicker::make('start_date')
                                ->native(false)
                                ->required(),
                            Forms\Components\DateTimePicker::make('end_date')
                                ->native(false)
                                ->required(),
                            Forms\Components\Fieldset::make('Status')
                                ->columns(1)
                                ->schema([
                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'archived' => 'Archived',
                                        ])
                                        ->required(),
                                    Forms\Components\Toggle::make('is_published')
                                        ->default(true),
                                    // Forms\Components\Checkbox::make('is_published')
                                    //     ->default(true),
                                ]),
                        ]),
                    Forms\Components\Tabs\Tab::make('Location')
                        ->schema([

                            Forms\Components\Select::make('region')
                                ->live()
                                ->enum(Region::class)
                                ->options(Region::class),
                            Forms\Components\Select::make('venue_id')
                                ->searchable()
                                ->preload()
                                ->createOptionForm(Venue::getForm())
                                ->editOptionForm(Venue::getForm())
                                ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
                                    return $query->where('region', $get('region'));
                                }),

                        ]),

                ]),

            // Forms\Components\Section::make('Conference Details')
            // // ->aside()
            //     ->collapsible()
            //     ->description('Conference Details Description')
            //     ->icon('heroicon-o-rectangle-stack')
            // // ->columns(['md' => 2, 'lg' => '2'])
            //     ->columns(2)

            // Forms\Components\Section::make('Location')
            //     ->columns(2)
            //     ->schema([
            //         Forms\Components\Select::make('region')
            //             ->live()
            //             ->enum(Region::class)
            //             ->options(Region::class),
            //         Forms\Components\Select::make('venue_id')
            //             ->searchable()
            //             ->preload()
            //             ->createOptionForm(Venue::getForm())
            //             ->editOptionForm(Venue::getForm())
            //             ->relationship('venue', 'name', modifyQueryUsing: function (Builder $query, Forms\Get $get) {
            //                 return $query->where('region', $get('region'));
            //             }),
            //     ]),

        ];
    }
}
