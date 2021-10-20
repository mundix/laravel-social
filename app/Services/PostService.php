<?php


namespace App\Services;


use App\Models\Company;

class PostService
{

    /**
     * @param Company $company
     * @param null|string $searchQuery
     * @param null|integer $category
     * @param null|string $status
     * @param null|string $disabled
     * @param null|integer $limit
     * @param null|string $sortColumn
     * @param null|bool $sortOrder
     * @param string $pageName
     */
    public function search(
        Company $company,
        $searchQuery = null,
        $category = null,
        $status = null,
        $disabled = null,
        $limit = 5,
        $sortColumn = null,
        bool $sortOrder = true,
        $pageName = 'news'
    ) {
        $query = $company->posts()->select('posts.*')
            ->with(['media', 'user'])
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where(function ($query) use ($searchQuery){
                    $query->where('title', 'like', '%' . $searchQuery . '%')
                        ->orWhere('content', 'like', '%' . $searchQuery . '%');
                });

            })
            ->when((!empty($category) && $category !== 'all'), function ($query) use ($category) {
                $query->where('category_id', $category);
            })

            ->join('category_posts', 'category_posts.id', '=', 'posts.category_id');

        if($disabled === 'disabled') {
            $query->where('posts.status', 'disabled');
        }elseif($status === 'draft') {
            $query->where('posts.status', 'draft');
        }elseif($status === 'all'){
            $query->where(function ($query) use ($status) {
                $query->where('posts.status', 'publish');
                $query->orWhere('posts.status', 'draft');
            });
        }else{
            $query->where('posts.status', 'publish');
        }

        if ($sortColumn) {
            if($sortColumn === 'favorites') {
                $query->orderBy('favorites_count', $sortOrder ? 'asc' : 'desc');
            }else{
                $query->orderBy($sortColumn, $sortOrder ? 'asc' : 'desc');
            }
        }

        return $query->paginate($limit, ['*'], $pageName);
    }

    /**
     * Get an excerpt
     *
     * @param string $content The content to be transformed
     * @param int $length The number of words
     * @param string $more The text to be displayed at the end, if shortened
     * @return string
     */
    public static function get_excerpt($content, $length = 40, $more = '...')
    {
        $excerpt = strip_tags(trim($content));
        $words = str_word_count($excerpt, 2);
        if (count($words) > $length) {
            $words = array_slice($words, 0, $length, true);
            end($words);
            $position = key($words) + strlen(current($words));
            $excerpt = substr($excerpt, 0, $position) . $more;
        }
        return $excerpt;
    }
}