<?php

namespace App\Http\Controllers\Playground;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PlaygroundsTableController extends Controller
{
    /**
     * Mock data for the playground table demo.
     * This is a protected class property used by all methods.
     */
    protected array $data = [
        ['id' => 1, 'name' => 'Alice Johnson', 'email' => 'alice.johnson@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-01-01', 'revenue' => 12500.00],
        ['id' => 2, 'name' => 'Bob Smith', 'email' => 'bob.smith@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-01-05', 'revenue' => 8750.50],
        ['id' => 3, 'name' => 'Charlie Brown', 'email' => 'charlie.brown@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-01-10', 'revenue' => 3200.00],
        ['id' => 4, 'name' => 'Diana Prince', 'email' => 'diana.prince@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-01-15', 'revenue' => 15000.75],
        ['id' => 5, 'name' => 'Edward Norton', 'email' => 'edward.norton@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-01-20', 'revenue' => 5600.25],
        ['id' => 6, 'name' => 'Fiona Apple', 'email' => 'fiona.apple@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-01-25', 'revenue' => 18900.00],
        ['id' => 7, 'name' => 'George Lucas', 'email' => 'george.lucas@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-02-01', 'revenue' => 7200.00],
        ['id' => 8, 'name' => 'Hannah Montana', 'email' => 'hannah.montana@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-02-05', 'revenue' => 2100.50],
        ['id' => 9, 'name' => 'Ivan Petrov', 'email' => 'ivan.petrov@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-02-10', 'revenue' => 11300.00],
        ['id' => 10, 'name' => 'Julia Roberts', 'email' => 'julia.roberts@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-02-15', 'revenue' => 4800.75],
        ['id' => 11, 'name' => 'Kevin Hart', 'email' => 'kevin.hart@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-02-20', 'revenue' => 22000.00],
        ['id' => 12, 'name' => 'Laura Croft', 'email' => 'laura.croft@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-02-25', 'revenue' => 9500.25],
        ['id' => 13, 'name' => 'Mike Tyson', 'email' => 'mike.tyson@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-03-01', 'revenue' => 1800.00],
        ['id' => 14, 'name' => 'Nancy Drew', 'email' => 'nancy.drew@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-03-05', 'revenue' => 13400.50],
        ['id' => 15, 'name' => 'Oscar Wilde', 'email' => 'oscar.wilde@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-03-10', 'revenue' => 6200.00],
        ['id' => 16, 'name' => 'Patty Smith', 'email' => 'patty.smith@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-03-15', 'revenue' => 19800.75],
        ['id' => 17, 'name' => 'Quinn Fabray', 'email' => 'quinn.fabray@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-03-20', 'revenue' => 8100.00],
        ['id' => 18, 'name' => 'Rachel Green', 'email' => 'rachel.green@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-03-25', 'revenue' => 2900.25],
        ['id' => 19, 'name' => 'Steve Jobs', 'email' => 'steve.jobs@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-04-01', 'revenue' => 25000.00],
        ['id' => 20, 'name' => 'Tina Fey', 'email' => 'tina.fey@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-04-05', 'revenue' => 7500.50],
        ['id' => 21, 'name' => 'Uma Thurman', 'email' => 'uma.thurman@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-04-10', 'revenue' => 16700.00],
        ['id' => 22, 'name' => 'Victor Hugo', 'email' => 'victor.hugo@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-04-15', 'revenue' => 5400.75],
        ['id' => 23, 'name' => 'Wendy Darling', 'email' => 'wendy.darling@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-04-20', 'revenue' => 3100.00],
        ['id' => 24, 'name' => 'Xander Cage', 'email' => 'xander.cage@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-04-25', 'revenue' => 14200.25],
        ['id' => 25, 'name' => 'Yara Shahidi', 'email' => 'yara.shahidi@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-05-01', 'revenue' => 6800.00],
        ['id' => 26, 'name' => 'Zack Morris', 'email' => 'zack.morris@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-05-05', 'revenue' => 21500.50],
        ['id' => 27, 'name' => 'Amy Adams', 'email' => 'amy.adams@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-05-10', 'revenue' => 9200.00],
        ['id' => 28, 'name' => 'Bruce Wayne', 'email' => 'bruce.wayne@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-05-15', 'revenue' => 1500.75],
        ['id' => 29, 'name' => 'Clark Kent', 'email' => 'clark.kent@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-05-20', 'revenue' => 17800.00],
        ['id' => 30, 'name' => 'Diana Ross', 'email' => 'diana.ross@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-05-25', 'revenue' => 5900.25],
        ['id' => 31, 'name' => 'Eve Adams', 'email' => 'eve.adams@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-06-01', 'revenue' => 23000.00],
        ['id' => 32, 'name' => 'Frank Castle', 'email' => 'frank.castle@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-06-05', 'revenue' => 7800.50],
        ['id' => 33, 'name' => 'Gina Linetti', 'email' => 'gina.linetti@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-06-10', 'revenue' => 2400.00],
        ['id' => 34, 'name' => 'Harry Potter', 'email' => 'harry.potter@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-06-15', 'revenue' => 12900.75],
        ['id' => 35, 'name' => 'Iris West', 'email' => 'iris.west@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-06-20', 'revenue' => 4500.00],
        ['id' => 36, 'name' => 'Jack Sparrow', 'email' => 'jack.sparrow@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-06-25', 'revenue' => 19200.25],
        ['id' => 37, 'name' => 'Kate Bishop', 'email' => 'kate.bishop@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-07-01', 'revenue' => 8600.00],
        ['id' => 38, 'name' => 'Leo Messi', 'email' => 'leo.messi@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-07-05', 'revenue' => 3500.50],
        ['id' => 39, 'name' => 'Mona Lisa', 'email' => 'mona.lisa@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-07-10', 'revenue' => 15600.00],
        ['id' => 40, 'name' => 'Nick Fury', 'email' => 'nick.fury@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-07-15', 'revenue' => 6100.75],
        ['id' => 41, 'name' => 'Olivia Pope', 'email' => 'olivia.pope@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-07-20', 'revenue' => 20500.00],
        ['id' => 42, 'name' => 'Peter Pan', 'email' => 'peter.pan@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-07-25', 'revenue' => 7100.25],
        ['id' => 43, 'name' => 'Queen Latifah', 'email' => 'queen.latifah@example.com', 'role' => 'Viewer', 'status' => 'inactive', 'created_at' => '2024-08-01', 'revenue' => 2700.00],
        ['id' => 44, 'name' => 'Ron Swanson', 'email' => 'ron.swanson@example.com', 'role' => 'Manager', 'status' => 'active', 'created_at' => '2024-08-05', 'revenue' => 11800.50],
        ['id' => 45, 'name' => 'Sarah Connor', 'email' => 'sarah.connor@example.com', 'role' => 'Contributor', 'status' => 'pending', 'created_at' => '2024-08-10', 'revenue' => 5200.00],
        ['id' => 46, 'name' => 'Tom Hardy', 'email' => 'tom.hardy@example.com', 'role' => 'Admin', 'status' => 'active', 'created_at' => '2024-08-15', 'revenue' => 18300.75],
        ['id' => 47, 'name' => 'Uma Thurman', 'email' => 'uma.thurman2@example.com', 'role' => 'Editor', 'status' => 'active', 'created_at' => '2024-08-20', 'revenue' => 9700.00],
    ];

    /**
     * Display the playground table page.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $perPage = (int) $request->input('per_page', 10);
        $search = $request->input('search');
        $searchField = $request->input('search_field', '');
        $sortField = $request->input('sort_field', '');
        $sortDirection = $request->input('sort_direction', 'asc');

        // Filter
        $filtered = $this->filterData($this->data, $search, $searchField);

        // Sort
        $sorted = $this->sortData($filtered, $sortField, $sortDirection);

        // Paginate
        $total = count($sorted);
        $paginator = new LengthAwarePaginator(
            array_values(array_slice($sorted, ($page - 1) * $perPage, $perPage)),
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => $request->query()]
        );

        return inertia('Playgrounds/Tables', [
            'serverData' => $paginator->items(),
            'paginationMeta' => [
                'currentPage' => $paginator->currentPage(),
                'lastPage' => $paginator->lastPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
                'previousPageUrl' => $paginator->previousPageUrl(),
                'nextPageUrl' => $paginator->nextPageUrl(),
            ],
            'searchFieldOptions' => [
                ['value' => '', 'label' => 'All Fields'],
                ['value' => 'name', 'label' => 'Name'],
                ['value' => 'email', 'label' => 'Email'],
                ['value' => 'role', 'label' => 'Role'],
                ['value' => 'status', 'label' => 'Status'],
            ],
            'sortFieldOptions' => [
                ['value' => 'name', 'label' => 'Name'],
                ['value' => 'email', 'label' => 'Email'],
                ['value' => 'role', 'label' => 'Role'],
                ['value' => 'status', 'label' => 'Status'],
                ['value' => 'created_at', 'label' => 'Created'],
                ['value' => 'revenue', 'label' => 'Revenue'],
            ],
            'queryParams' => [
                'search' => $search,
                'search_field' => $searchField,
                'sort_field' => $sortField,
                'sort_direction' => $sortDirection,
            ],
        ]);
    }

    /**
     * Filter data by search query and optional field.
     */
    protected function filterData(array $data, ?string $search, string $searchField): array
    {
        if (!$search) {
            return $data;
        }

        $searchableFields = $searchField ? [$searchField] : ['name', 'email', 'role', 'status'];

        return array_values(array_filter($data, function ($row) use ($search, $searchableFields) {
            foreach ($searchableFields as $field) {
                if (isset($row[$field]) && stripos($row[$field], $search) !== false) {
                    return true;
                }
            }
            return false;
        }));
    }

    /**
     * Sort data by field and direction.
     */
    protected function sortData(array $data, string $sortField, string $sortDirection): array
    {
        if (!$sortField) {
            return $data;
        }

        usort($data, function ($a, $b) use ($sortField, $sortDirection) {
            $aVal = $a[$sortField] ?? '';
            $bVal = $b[$sortField] ?? '';

            // Handle numeric comparison
            if (is_numeric($aVal) && is_numeric($bVal)) {
                $cmp = (float) $aVal - (float) $bVal;
            } else {
                $cmp = strcmp((string) $aVal, (string) $bVal);
            }

            return $sortDirection === 'desc' ? -$cmp : $cmp;
        });

        return $data;
    }
}
