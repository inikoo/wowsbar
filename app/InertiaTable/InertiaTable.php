<?php

namespace App\InertiaTable;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Inertia\Response;

class InertiaTable
{
    private string $name          = 'default';
    private string $pageName      = 'page';
    private array $perPageOptions = [10, 50, 100, 500, 1000];
    private Request $request;
    private Collection $columns;
    private Collection $searchInputs;
    private Collection $elementGroups;
    private Collection $filters;
    private string $defaultSort = '';

    private array $title = [];

    private Collection $emptyState;
    private Collection $modelOperations;

    private static bool|string $defaultGlobalSearch = false;
    private static array $defaultQueryBuilderConfig = [];

    public function __construct(Request $request)
    {
        $this->request         = $request;
        $this->columns         = new Collection();
        $this->searchInputs    = new Collection();
        $this->elementGroups   = new Collection();
        $this->filters         = new Collection();
        $this->modelOperations = new Collection();
        $this->emptyState      = new Collection();

        if (static::$defaultGlobalSearch !== false) {
            $this->withGlobalSearch(static::$defaultGlobalSearch);
        }
    }

    public static function defaultGlobalSearch(bool|string $label = 'Search...'): void
    {
        static::$defaultGlobalSearch = $label !== false ? __($label) : false;
    }


    private function query(string $key, mixed $default = null): string|array|null
    {
        return $this->request->query(
            $this->name === 'default' ? $key : "{$this->name}_$key",
            $default
        );
    }


    public static function updateQueryBuilderParameters(string $name): void
    {
        if (empty(static::$defaultQueryBuilderConfig)) {
            static::$defaultQueryBuilderConfig = config('query-builder.parameters');
        }

        $newConfig = collect(static::$defaultQueryBuilderConfig)->map(function ($value) use ($name) {
            return "{$name}_$value";
        })->all();

        config(['query-builder.parameters' => $newConfig]);
    }


    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }


    public function pageName(string $pageName): self
    {
        $this->pageName = $pageName;

        return $this;
    }


    public function perPageOptions(array $perPageOptions): self
    {
        $this->perPageOptions = $perPageOptions;

        return $this;
    }


    public function defaultSort(string $defaultSort): self
    {
        $this->defaultSort = $defaultSort;

        return $this;
    }


    protected function getQueryBuilderProps(): array
    {

        return [
            'defaultVisibleToggleableColumns' => $this->columns->reject->hidden->map->key->sort()->values(),
            'columns'                         => $this->transformColumns(),
            'hasHiddenColumns'                => $this->columns->filter->hidden->isNotEmpty(),
            'hasToggleableColumns'            => $this->columns->filter->canBeHidden->isNotEmpty(),
            'filters'                         => $this->transformFilters(),
            'hasFilters'                      => $this->filters->isNotEmpty(),
            'hasEnabledFilters'               => $this->filters->filter->value->isNotEmpty(),
            'searchInputs'                    => $searchInputs              = $this->transformSearchInputs(),
            'searchInputsWithoutGlobal'       => $searchInputsWithoutGlobal = $searchInputs->where('key', '!=', 'global'),
            'hasSearchInputs'                 => $searchInputsWithoutGlobal->isNotEmpty(),
            'hasSearchInputsWithValue'        => $searchInputsWithoutGlobal->whereNotNull('value')->isNotEmpty(),
            'hasSearchInputsWithoutValue'     => $searchInputsWithoutGlobal->whereNull('value')->isNotEmpty(),
            'globalSearch'                    => $this->searchInputs->firstWhere('key', 'global'),
            'cursor'                          => $this->query('cursor'),
            'sort'                            => $this->query('sort', $this->defaultSort) ?: null,
            'defaultSort'                     => $this->defaultSort,
            'page'                            => Paginator::resolveCurrentPage($this->pageName),
            'pageName'                        => $this->pageName,
            'perPageOptions'                  => $this->perPageOptions,
            'elementGroups'                   => $this->transformElementGroups(),
            'modelOperations'                 => $this->modelOperations,
            'emptyState'                      => $this->emptyState,
            'title'                           => $this->title
        ];
    }


    protected function transformColumns(): Collection
    {
        $columns = $this->query('columns', []);

        $sort = $this->query('sort', $this->defaultSort);

        return $this->columns->map(function (Column $column) use ($columns, $sort) {
            $key = $column->key;

            if (!empty($columns)) {
                $column->hidden = !in_array($key, $columns);
            }

            if ($sort === $key) {
                $column->sorted = 'asc';
            } elseif ($sort === "-$key") {
                $column->sorted = 'desc';
            }

            return $column;
        });
    }


    protected function transformFilters(): Collection
    {
        $filters = $this->filters;

        $queryFilters = $this->query('filter', []);

        if (empty($queryFilters)) {
            return $filters;
        }

        return $filters->map(function (Filter $filter) use ($queryFilters) {
            if (array_key_exists($filter->key, $queryFilters)) {
                $filter->value = $queryFilters[$filter->key];
            }

            return $filter;
        });
    }

    protected function transformElementGroups(): Collection
    {
        $elementGroups = $this->elementGroups;
        $queryElements = $this->query('elements', []);

        if (empty($queryElements)) {
            return $elementGroups;
        }

        return $elementGroups->map(function (ElementGroup $elementGroup) use ($queryElements) {
            if (array_key_exists($elementGroup->key, $queryElements)) {
                $elementGroup->values = explode(',', $queryElements[$elementGroup->key]);
            }

            return $elementGroup;
        });
    }


    protected function transformSearchInputs(): Collection
    {
        $filters = $this->query('filter', []);

        if (empty($filters)) {
            return $this->searchInputs;
        }

        return $this->searchInputs->map(function (SearchInput $searchInput) use ($filters) {
            if (array_key_exists($searchInput->key, $filters)) {
                $searchInput->value = $filters[$searchInput->key];
            }

            return $searchInput;
        });
    }


    public function elementGroup(string $key, array|string $label, array $elements): self
    {
        if (is_string($label)) {
            $label = $label ?: Str::headline($key);
            $key   = $key ?: Str::kebab($label);
        } else {
            $key = $key ?: Str::kebab($label['tooltip']);
        }


        $this->elementGroups = $this->elementGroups->reject(function (ElementGroup $column) use ($key) {
            return $column->key === $key;
        })->push(
            new ElementGroup(
                key: $key,
                label: $label,
                elements: $elements
            )
        )->values();


        return $this;
    }


    public function column(string $key = null, array|string $label = null, bool $canBeHidden = true, bool $hidden = false, bool $sortable = false, bool $searchable = false): self
    {
        if (is_string($label)) {
            $label = $label ?: Str::headline($key);
            $key   = $key ?: Str::kebab($label);
        } else {
            $key = $key ?: Str::kebab($label['tooltip']);
        }


        $this->columns = $this->columns->reject(function (Column $column) use ($key) {
            return $column->key === $key;
        })->push(
            $column = new Column(
                key: $key,
                label: $label,
                canBeHidden: $canBeHidden,
                hidden: $hidden,
                sortable: $sortable,
                sorted: false
            )
        )->values();

        if ($searchable) {
            $this->searchInput($column->key, $column->label);
        }

        return $this;
    }


    public function withGlobalSearch(string $label = null): self
    {
        return $this->searchInput('global', $label ?: __('Search...'));
    }

    public function withModelOperations(array $modelOperations = null): self
    {

        $this->modelOperations = collect($modelOperations);

        return $this;
    }

    public function withEmptyState(array $emptyState = null): self
    {
        $this->emptyState = collect($emptyState);

        return $this;
    }

    public function withTitle(string $title, array $leftIcon = null): self
    {
        $this->title = [
            'title'    => $title,
            'leftIcon' => $leftIcon
        ];

        return $this;
    }


    public function searchInput(string $key, string $label = null, string $defaultValue = null): self
    {
        $this->searchInputs = $this->searchInputs->reject(function (SearchInput $searchInput) use ($key) {
            return $searchInput->key === $key;
        })->push(
            new SearchInput(
                key: $key,
                label: $label ?: Str::headline($key),
                value: $defaultValue
            )
        )->values();

        return $this;
    }


    public function selectFilter(string $key, array $options, string $label = null, string $defaultValue = null, bool $noFilterOption = true, string $noFilterOptionLabel = null): self
    {
        $this->filters = $this->filters->reject(function (Filter $filter) use ($key) {
            return $filter->key === $key;
        })->push(
            new Filter(
                key: $key,
                label: $label ?: Str::headline($key),
                options: $options,
                value: $defaultValue,
                noFilterOption: $noFilterOption,
                noFilterOptionLabel: $noFilterOptionLabel ?: '-',
                type: 'select'
            )
        )->values();

        return $this;
    }


    public function applyTo(Response $response): Response
    {
        $props = array_merge($response->getQueryBuilderProps(), [
            $this->name => $this->getQueryBuilderProps(),
        ]);
        return $response->with('queryBuilderProps', $props);
    }
}
