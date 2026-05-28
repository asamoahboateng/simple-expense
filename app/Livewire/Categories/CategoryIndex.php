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
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Categories')]
class CategoryIndex extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithPagination;

    #[Url]
    public string $search = '';

    public string $filter = 'all';

    public int $perPage = 10;

    public ?int $editingMainId = null;

    public ?int $editingSubId = null;

    public ?int $creatingSubForMainId = null;

    public ?int $deletingMainId = null;

    public ?int $deletingSubId = null;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedFilter(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

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

    // Called from blade via wire:click
    public function startEditMainCategory(int $id): void
    {
        $this->editingMainId = $id;
        $this->mountAction('editMainCategory');
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
            ->fillForm(function (): array {
                if (! $this->editingMainId) return [];

                return MainCategory::findOrFail($this->editingMainId)->toArray();
            })
            ->action(function (array $data): void {
                if (! $this->editingMainId) return;

                MainCategory::findOrFail($this->editingMainId)->update($data);
                $this->editingMainId = null;
                Notification::make()->title('Category updated')->success()->send();
            });
    }

    public function startDeleteMainCategory(int $id): void
    {
        $this->deletingMainId = $id;
        $this->mountAction('deleteMainCategory');
    }

    public function deleteMainCategoryAction(): Action
    {
        return Action::make('deleteMainCategory')
            ->requiresConfirmation()
            ->modalHeading('Delete Category')
            ->modalDescription('This will delete the category and all its subcategories. Are you sure?')
            ->color('danger')
            ->action(function (): void {
                if (! $this->deletingMainId) return;

                $category = MainCategory::findOrFail($this->deletingMainId);
                $category->delete();
                $this->deletingMainId = null;
                Notification::make()->title('Category deleted')->success()->send();
            });
    }

    public function startCreateSubCategory(int $mainCategoryId): void
    {
        $this->creatingSubForMainId = $mainCategoryId;
        $this->mountAction('createSubCategory');
    }

    public function createSubCategoryAction(): Action
    {
        return Action::make('createSubCategory')
            ->modalHeading('Create Subcategory')
            ->form(function (): array {
                $mainCategoryId = $this->creatingSubForMainId;

                return [
                    TextInput::make('name')->required()->maxLength(255),
                    Textarea::make('description')->rows(2),
                    Select::make('parent_id')
                        ->label('Parent Subcategory')
                        ->options(function () use ($mainCategoryId) {
                            if (! $mainCategoryId) return [];

                            return SubCategory::where('main_category_id', $mainCategoryId)
                                ->get()
                                ->mapWithKeys(fn ($sub) => [$sub->id => $sub->breadcrumb]);
                        })
                        ->placeholder('None (root level)')
                        ->searchable(),
                    TextInput::make('sort_order')->numeric()->default(0),
                ];
            })
            ->action(function (array $data): void {
                if (! $this->creatingSubForMainId) return;

                SubCategory::create([
                    ...$data,
                    'main_category_id' => $this->creatingSubForMainId,
                ]);
                $this->creatingSubForMainId = null;
                Notification::make()->title('Subcategory created')->success()->send();
            });
    }

    // Called from blade via wire:click
    public function startEditSubCategory(int $id): void
    {
        $this->editingSubId = $id;
        $this->mountAction('editSubCategory');
    }

    public function editSubCategoryAction(): Action
    {
        return Action::make('editSubCategory')
            ->modalHeading('Edit Subcategory')
            ->form(function (): array {
                $sub = $this->editingSubId ? SubCategory::find($this->editingSubId) : null;

                return [
                    TextInput::make('name')->required()->maxLength(255),
                    Textarea::make('description')->rows(2),
                    Select::make('parent_id')
                        ->label('Parent Subcategory')
                        ->options(function () use ($sub) {
                            if (! $sub) return [];

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
            ->fillForm(function (): array {
                if (! $this->editingSubId) return [];

                return SubCategory::findOrFail($this->editingSubId)->toArray();
            })
            ->action(function (array $data): void {
                if (! $this->editingSubId) return;

                SubCategory::findOrFail($this->editingSubId)->update($data);
                $this->editingSubId = null;
                Notification::make()->title('Subcategory updated')->success()->send();
            });
    }

    public function startDeleteSubCategory(int $id): void
    {
        $this->deletingSubId = $id;
        $this->mountAction('deleteSubCategory');
    }

    public function deleteSubCategoryAction(): Action
    {
        return Action::make('deleteSubCategory')
            ->requiresConfirmation()
            ->modalHeading('Delete Subcategory')
            ->modalDescription('This will delete this subcategory and all its children. Are you sure?')
            ->color('danger')
            ->action(function (): void {
                if (! $this->deletingSubId) return;

                $sub = SubCategory::findOrFail($this->deletingSubId);
                $this->deleteSubCategoryRecursively($sub);
                $this->deletingSubId = null;
                Notification::make()->title('Subcategory deleted')->success()->send();
            });
    }

    private function deleteSubCategoryRecursively(SubCategory $sub): void
    {
        foreach ($sub->children as $child) {
            $this->deleteSubCategoryRecursively($child);
        }
        $sub->delete();
    }

    public function render()
    {
        $query = MainCategory::with(['rootSubCategoriesWithChildren', 'subCategories'])
            ->orderBy('sort_order')
            ->orderBy('name');

        if ($this->search !== '') {
            $searchTerm = $this->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereHas('subCategories', function ($sq) use ($searchTerm) {
                        $sq->where('name', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($this->filter === 'with-subs') {
            $query->has('subCategories');
        } elseif ($this->filter === 'empty') {
            $query->doesntHave('subCategories');
        }

        return view('livewire.categories.category-index', [
            'mainCategories' => $query->paginate($this->perPage),
        ]);
    }
}
