<?php


namespace App\Services;


use App\Models\Company;

class EventService
{
    public function search(
        Company $company,
        $searchQuery = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'events'
    ) {
        $query = $company->user
            ->events()
            ->with(['media', 'user'])
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery) {
                    $query->where('name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('description', 'like', '%' . $searchQuery . '%');
                });
            });


        if ($disabled === 'disabled') {
            $query->where('status', 'pending');
        } else {
            if ($status === 'draft') {
                $query->where('status', 'draft');
            }else {
                $query->where('status', 'enabled');
            }
        }


        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }
        return $query->paginate($limit, ['*'], $pageName);
    }
}