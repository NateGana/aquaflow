<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

abstract class CrudController extends Controller
{
    abstract protected function model(): string;

    abstract protected function route(): string;

    abstract protected function singular(): string;

    abstract protected function plural(): string;

    abstract protected function fields(): array;

    abstract protected function columns(): array;

    protected function searchColumn(): string
    {
        return 'title';
    }

    protected function with(): array
    {
        return [];
    }

    protected function counts(): array
    {
        return [];
    }

    protected function extra(?Model $item): ?string
    {
        return null;
    }

    protected function extraData(?Model $item): array
    {
        return [];
    }

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $items = $this->model()::query()
            ->with($this->with())
            ->withCount($this->counts())
            ->when($q !== '', fn ($query) => $query->where($this->searchColumn(), 'like', "%{$q}%"))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.crud.index', [
            'title' => $this->plural(),
            'singular' => $this->singular(),
            'route' => $this->route(),
            'columns' => $this->columns(),
            'items' => $items,
            'q' => $q,
        ]);
    }

    public function create()
    {
        return $this->form(null);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->model()::create($this->payload($request));

        return redirect()->route($this->route().'.index')->with('success', $this->singular().' created.');
    }

    public function edit(string $id)
    {
        return $this->form($this->find($id));
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $item = $this->find($id);
        $item->update($this->payload($request, $item));

        return redirect()->route($this->route().'.index')->with('success', $this->singular().' updated.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $item = $this->find($id);

        foreach ($this->fields() as $field) {
            if (($field['type'] ?? 'text') === 'file' && $item->{$field['name']}) {
                Storage::disk('public')->delete($item->{$field['name']});
            }
        }

        $item->delete();

        return redirect()->route($this->route().'.index')->with('success', $this->singular().' deleted.');
    }

    protected function find(string $id): Model
    {
        $class = $this->model();
        $key = (new $class)->getRouteKeyName();

        return $class::where($key, $id)->firstOrFail();
    }

    protected function form(?Model $item)
    {
        return view('admin.crud.form', [
            'title' => ($item ? 'Edit ' : 'New ').strtolower($this->singular()),
            'route' => $this->route(),
            'fields' => $this->fields(),
            'item' => $item,
            'extra' => $this->extra($item),
            'extraData' => $this->extraData($item),
        ]);
    }

    protected function payload(Request $request, ?Model $item = null): array
    {
        $rules = [];

        foreach ($this->fields() as $field) {
            $rules[$field['name']] = $field['rules'] ?? 'nullable';
        }

        $valid = $request->validate($rules);
        $data = [];

        foreach ($this->fields() as $field) {
            $name = $field['name'];

            switch ($field['type'] ?? 'text') {
                case 'file':
                    if ($request->hasFile($name)) {
                        if ($item && $item->{$name}) {
                            Storage::disk('public')->delete($item->{$name});
                        }
                        $data[$name] = $request->file($name)->store('uploads', 'public');
                    }
                    break;
                case 'lines':
                    $lines = preg_split('/\R/', (string) ($valid[$name] ?? ''));
                    $data[$name] = array_values(array_filter(array_map('trim', $lines), fn ($line) => $line !== ''));
                    break;
                case 'checkbox':
                    $data[$name] = $request->boolean($name);
                    break;
                default:
                    $data[$name] = $valid[$name] ?? ($field['default'] ?? null);
            }
        }

        return $data;
    }
}
