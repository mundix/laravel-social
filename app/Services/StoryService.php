<?php


namespace App\Services;


use App\Models\Company;
use App\Models\Story;

class StoryService
{
    /**
     * @param Company $company
     * @param int $limit
     * @param null $status
     * @param null $queryString
     * @param string $orderBy
     * @param string $sortBy
     * @param string $pageName
     */
    public function search(
        Company $company = null,
        $limit = 5,
        $status = null,
        $queryString = null,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'storiesPage'
    ) {
        $query = $company->stories()
            ->with(['media', 'submitted'])
            ->when($queryString && !empty(trim($queryString)), function ($query) use ($queryString) {
                $query->where(function($query) use ($queryString) {
                    $query->where('title', 'like', '%' . $queryString . '%');
                    $query->orWhere('content', 'like', '%' . $queryString . '%');
                });
            });

        if($status === 'pending') {
            $query->where('status', 'pending');
        } elseif($status === 'draft') {
            $query->where('status', 'draft');
        }else {
            $query->where('status', 'publish');
        }

        if ($sortColumn) {
            $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
        }

        return $query->paginate($limit, ['*'], $pageName);
    }
}