<?php

namespace App\Models;

use Filament\Forms;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Speaker extends Model
{
    use HasFactory;

    protected $casts = [
        'id' => 'integer',
        'qualifications' => 'array',
        'conference_id' => 'integer',
    ];

    public function conference(): BelongsTo
    {
        return $this->belongsTo(Conference::class);
    }

    public static function getForm(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('bio')
                ->required()
                ->maxLength(65535)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('twitter_handle')
                ->required()
                ->maxLength(255),
            Forms\Components\Select::make('conference_id')
                ->relationship('conference', 'name')
                ->required(),
            Forms\Components\CheckboxList::make('qualifications')
                ->columnSpanFull()
                ->searchable()
                ->bulkToggleable()
                ->options([
                    'business-leader' => 'Business Leader',
                    'charisma' => 'Charismatic Speaker',
                    'first-time' => 'First Time Speaker',
                    'hometown-hero' => 'Hometown Hero',
                    'humanitarian' => 'Works in Humanitarian Field',
                    'laracasts-contributor' => 'Laracasts Contributor',
                    'twitter-influencer' => 'Large Twitter Following',
                    'youtube-influencer' => 'Large YouTube Following',
                    'open-source' => 'Open Source Creator / Maintainer',
                    'unique-perspective' => 'Unique Perspective',
                ])
                ->descriptions([
                    'business-leader' => 'Here is a nice long description',
                    'charisma' => 'This is even more information about why you should pick this one',
                ])
                ->columns(3),
        ];
    }
}
