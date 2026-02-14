<?php

namespace App\Livewire\Categories;

use App\Models\MainCategory;
use App\Models\SubCategory;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Categories')]
class CategoryIndex extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function createMainCategoryAction(): Action
    {
        return Action::make('createMainCategory')
            ->label('New Category')
            ->modalHeading('Create Main Category')
            ->form([
                TextInput::make('name')->required()->maxLength(255),
                Textarea::make('description')->rows(2),
                TextInput::make('icon')->placeholder('e.g. shopping-bag'),
                ColorPicker::make('color'),
                TextInput::make('sort_order')->numeric()->default(0),
            ])
            ->action(function (array $data): void {
                MainCategory::create($data);
                Notification::make()->title('Category created')->success()->send();
            });
    }

    public function editMainCategoryAction(): Action
    {
        return Action::make('editMainCategory')
            ->modalHeading('Edit Category')
            ->form([
                TextInput::make('name')->required()->maxLength(255),
                Textarea::make('description')->rows(2),
                TextInput::make('icon'),
                ColorPicker::make('color'),
                TextInput::make('sort_order')->numeric(),
            ])
            ->fillForm(function (array $arguments): array {
                $category = MainCategory::findOrFail($arguments['id']);
                return $category->toArray();
            })
            ->action(function (array $data, array $arguments): void {
                $category = MainCategory::findOrFail($arguments['id']);
                $category->update($data);
                Notification::make()->title('Category updated')->success()->send();
            });
    }

    public function deleteMainCategoryAction(): Action
    {
        return Action::make('deleteMainCategory')
            ->requiresConfirmation()
            ->modalHeading('Delete Category')
            ->modalDescription('This will delete the category and all its subcategories. Are you sure?')
            ->color('danger')
            ->action(function (array $arguments): void {
                $category = MainCategory::findOrFail($arguments['id']);
                $category->delete();
                Notification::make()->title('Category deleted')->success()->send();
            });
    }

    public function createSubCategoryAction(): Action
    {
        return Action::make('createSubCategory')
            ->modalHeading('Create Subcategory')
            ->form(function (array $arguments): array {
                $mainCategoryId = $arguments['main_category_id'];

                return [
                    TextInput::make('name')->required()->maxLength(255),
                    Textarea::make('description')->rows(2),
                    Select::make('parent_id')
                        ->label('Parent Subcategory')
                        ->options(function () use ($mainCategoryId) {
                            return SubCategory::where('main_category_id', $mainCategoryId)
                                ->get()
                                ->mapWithKeys(fn ($sub) => [$sub->id => $sub->breadcrumb]);
                        })
                        ->placeholder('None (root level)')
                        ->searchable(),
                    TextInput::make('sort_order')->numeric()->default(0),
                ];
            })
            ->action(function (array $data, array $arguments): void {
                SubCategory::create([
                    ...$data,
                    'main_category_id' => $arguments['main_category_id'],
                ]);
                Notification::make()->title('Subcategory created')->success()->send();
            });
    }

    public function editSubCategoryAction(): Action
    {
        return Action::make('editSubCategory')
            ->modalHeading('Edit Subcategory')
            ->form(function (array $arguments): array {
                $sub = SubCategory::findOrFail($arguments['id']);

                return [
                    TextInput::make('name')->required()->maxLength(255),
                    Textarea::make('description')->rows(2),
                    Select::make('parent_id')
                        ->label('Parent Subcategory')
                        ->options(function () use ($sub) {
                            return SubCategory::where('main_category_id', $sub->main_category_id)
                                ->where('id', '!=', $sub->id)
                                ->get()
                                ->mapWithKeys(fn ($s) => [$s->id => $s->breadcrumb]);
                        })
                        ->placeholder('None (root level)')
                        ->searchable(),
                    TextInput::make('sort_order')->numeric(),
                ];
            })
            ->fillForm(function (array $arguments): array {
                return SubCategory::findOrFail($arguments['id'])->toArray();
            })
            ->action(function (array $data, array $arguments): void {
                $sub = SubCategory::findOrFail($arguments['id']);
                $sub->update($data);
                Notification::make()->title('Subcategory updated')->success()->send();
            });
    }

    public function deleteSubCategoryAction(): Action
    {
        return Action::make('deleteSubCategory')
            ->requiresConfirmation()
            ->modalHeading('Delete Subcategory')
            ->modalDescription('This will delete this subcategory and all its children. Are you sure?')
            ->color('danger')
            ->action(function (array $arguments): void {
                $sub = SubCategory::findOrFail($arguments['id']);
                $sub->delete();
                Notification::make()->title('Subcategory deleted')->success()->send();
            });
    }

    public function render()
    {
        return view('livewire.categories.category-index', [
            'mainCategories' => MainCategory::with('rootSubCategoriesWithChildren')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}
