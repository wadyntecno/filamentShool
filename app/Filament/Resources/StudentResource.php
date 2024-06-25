<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


                

class StudentResource extends Resource
{
    
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required()
                ->label('Nome')
                ->minLength(5)
                ->maxLength(255),
                Forms\Components\TextInput::make('student_id')->required()
                ->label('Nª')
                ->maxLength(10),
                Forms\Components\TextInput::make('adress_1')->required()
                ->label('Estado')
                ->maxLength(255),
                Forms\Components\TextInput::make('adress_2')->required()
                ->label('Endereço')
                ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                ->searchable()->searchable()
                ->label('Nome'),

                Tables\Columns\TextColumn::make('student_id')
                ->label('Nª'),
                
                Tables\Columns\TextColumn::make('adress_1')
                ->searchable()
                ->label('Estado'),
                Tables\Columns\TextColumn::make('adress_2')
                ->label('Endereço'),

            ])
            ->filters([
                
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            // 'create' => Pages\CreateStudent::route('/create'),
            // 'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
