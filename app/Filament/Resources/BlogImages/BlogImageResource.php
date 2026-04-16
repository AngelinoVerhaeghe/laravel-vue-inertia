<?php

namespace App\Filament\Resources\BlogImages;

use App\Filament\Resources\BlogImages\Pages\CreateBlogImage;
use App\Filament\Resources\BlogImages\Pages\EditBlogImage;
use App\Filament\Resources\BlogImages\Pages\ListBlogImages;
use App\Filament\Resources\BlogImages\Schemas\BlogImageForm;
use App\Filament\Resources\BlogImages\Tables\BlogImagesTable;
use App\Models\BlogImage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlogImageResource extends Resource
{
    protected static ?string $model = BlogImage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string|\UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return BlogImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlogImagesTable::configure($table);
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
            'index' => ListBlogImages::route('/'),
            'create' => CreateBlogImage::route('/create'),
            'edit' => EditBlogImage::route('/{record}/edit'),
        ];
    }
}
