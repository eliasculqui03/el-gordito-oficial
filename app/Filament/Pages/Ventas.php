<?php

namespace App\Filament\Pages;

use App\Models\ComprobantePago;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Filament\Actions\Action as PageAction;
use Illuminate\Contracts\View\View;

class Ventas extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.ventas';
}
