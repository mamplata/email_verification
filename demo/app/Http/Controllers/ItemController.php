<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::paginate(10); // Adjust items per page as needed
        $currentPage = $items->currentPage();
        $lastPage = $items->lastPage();
        $range = 1;

        // Calculate the start and end of the range
        $start = max(1, $currentPage - 1);
        $end = min($lastPage, $start + $range - 1);

        // Adjust start if the range is incomplete at the end
        if ($end - $start < $range - 1) {
            $start = max(1, $end - $range + 1);
        }

        // Generate pagination links
        $links = [];
        for ($page = $start; $page <= $end; $page++) {
            $links[] = [
                'page' => $page,
                'label' => $page,
                'active' => $page == $currentPage,
                'url' => $items->url($page),
            ];
        }

        // Add Previous and Next buttons
        $prev = $currentPage > 1
            ? ['page' => $currentPage - 1, 'label' => '', 'url' => $items->previousPageUrl()]
            : null;

        $next = $currentPage < $lastPage
            ? ['page' => $currentPage + 1, 'label' => '', 'url' => $items->nextPageUrl()]
            : null;

        // Add Back to First and Last Page buttons
        $backToFirst = $currentPage > 1
            ? ['page' => 1, 'label' => 'Back to First', 'url' => $items->url(1)]
            : null;

        $lastPageButton = $currentPage < $lastPage
            ? ['page' => $lastPage, 'label' => 'Last Page', 'url' => $items->url($lastPage)]
            : null;

        return response()->json([
            'data' => $items->items(),
            'links' => $links,
            'prev' => $prev,
            'next' => $next,
            'backToFirst' => $backToFirst,
            'lastPage' => $lastPageButton,
            'meta' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ]
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('query', ''); // Get the search query from the request
        $items = Item::where('name', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->paginate(10); // Adjust pagination as needed

        $currentPage = $items->currentPage();
        $lastPage = $items->lastPage();

        $range = 3; // Max pages to show at a time
        $start = max(1, $currentPage - 1);
        $end = min($lastPage, $start + $range - 1);

        if ($end - $start < $range - 1) {
            $start = max(1, $end - $range + 1);
        }

        $links = [];
        for ($page = $start; $page <= $end; $page++) {
            $links[] = [
                'page' => $page,
                'label' => $page,
                'active' => $page == $currentPage,
                'url' => null, // No URL needed for search
            ];
        }

        $prev = $currentPage > 1
            ? ['page' => $currentPage - 1, 'label' => 'Previous', 'url' => null]
            : null;

        $next = $currentPage < $lastPage
            ? ['page' => $currentPage + 1, 'label' => 'Next', 'url' => null]
            : null;

        // Add Back to First and Last Page buttons
        $backToFirst = $currentPage > 1
            ? ['page' => 1, 'label' => 'Back to First', 'url' => $items->url(1)]
            : null;

        $lastPageButton = $currentPage < $lastPage
            ? ['page' => $lastPage, 'label' => 'Last Page', 'url' => $items->url($lastPage)]
            : null;

        return response()->json([
            'data' => $items->items(),
            'links' => $links,
            'prev' => $prev,
            'next' => $next,
            'backToFirst' => $backToFirst,
            'lastPage' => $lastPageButton,
            'meta' => [
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'per_page' => $items->perPage(),
                'total' => $items->total(),
            ]
        ]);
    }
}
