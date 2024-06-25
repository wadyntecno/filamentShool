<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\EventResource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use App\Models\Event;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;
use Saade\FilamentFullCalendar\Actions\CreateAction;
use Saade\FilamentFullCalendar\Actions\DeleteAction;
use Saade\FilamentFullCalendar\Actions\EditAction;
use Saade\FilamentFullCalendar\Actions\ViewAction;
use Saade\FilamentFullCalendar\Data\EventData;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model | string | null $model = Event::class;

    public function getFormSchema(): array
    {
        return [
            Hidden::make('user_id')
                ->default(auth()->user()->id),
            TextInput::make('title')->autofocus(),
            ColorPicker::make('color'),

            Grid::make()
                ->schema([
                    DateTimePicker::make('starts_at'),

                    DateTimePicker::make('ends_at'),
                ]),
        ];
    }

    public function fetchEvents(array $fetchInfo): array
    {
        return Event::query()
            ->where('starts_at', '>=', $fetchInfo['start'])
            ->where('ends_at', '<=', $fetchInfo['end'])
            ->get()
            // ->map(
            //     fn (Event $event) => 
            //     [
            //         'id' => $event->id,
            //         'title' => $event->title,
            //         'color' => $event->color,
            //         'start' => $event->starts_at,
            //         'end' => $event->ends_at,
            //         'url' => EventResource::getUrl(name: 'edit', parameters: ['record' => $event]),
            //         // 'shouldOpenUrlInNewTab' => false
            //     ]
            // )
            // ->all();
            ->map(
                fn (Event $event) => EventData::make()
                    ->id($event->id)
                    ->title($event->title)
                    ->start($event->starts_at)
                    ->end($event->ends_at)
                    ->url(
                        url: EventResource::getUrl(name: 'edit', parameters: ['record' => $event]),
                        shouldOpenUrlInNewTab: false
                    )
            )
            ->toArray();

    }

    protected function headerActions(): array
    {
        return [
            CreateAction::make()->label('Add Event'),
        ];
    }

    protected function modalActions(): array
    {
        return [
            CreateAction::make()
                ->mountUsing(
                    function (Form $form, array $arguments) {
                        $form->fill([
                            'user_id' => auth()->user()->id,
                            'starts_at' => $arguments['start'] ?? null,
                            'ends_at' => $arguments['end'] ?? null
                        ]);
                    }
                ),
            EditAction::make()
                ->mountUsing(
                    function (Event $record, Form $form, array $arguments) {
                        $form->fill([
                            'title' => $record->title,
                            'color' => $record->color,
                            'user_id' => $record->user_id,
                            'starts_at' => $arguments['event']['start'] ?? $record->starts_at,
                            'ends_at' => $arguments['event']['end'] ?? $record->ends_at
                        ]);
                    }
                ),
            DeleteAction::make(),
        ];
    }



    public function eventDidMount(): string
    {
        return <<<JS
        function({ event, timeText, isStart, isEnd, isMirror, isPast, isFuture, isToday, el, view }){
            el.setAttribute("x-tooltip", "tooltip");
            el.setAttribute("x-data", "{ tooltip: '"+event.title+"' }");
        }
    JS;
    }

    protected function viewAction(): Action
    {
        return ViewAction::make()
            ->modalFooterActions(fn(ViewAction $action) =>[
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
