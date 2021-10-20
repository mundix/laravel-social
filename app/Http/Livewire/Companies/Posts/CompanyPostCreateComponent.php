<?php

namespace App\Http\Livewire\Companies\Posts;

use App\Models\CategoryPost;
use App\Models\Post;
use App\Traits\SupportUiNotification;
use App\Traits\ValidatorErrorManagementTrait;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompanyPostCreateComponent extends Component
{

    use WithFileUploads;
    use SupportUiNotification;
    use ValidatorErrorManagementTrait;

    protected $listeners = ['CompanyPostCreateComponent' => '$refresh'];

    public $company;
    public $isNew = true;
    public $isUpdated = false;
    public $post;
    public $postID = null;
    public $picture;
    public $currentPicture;
    public $content;
    public $summary;
    public $category;
    public $title;
    public $author;
    public $status = 'pending';

    public $rules = [
        'title' => 'required',
        'summary' => 'required',
        'category_id' => 'required',
        'author' => 'required',
        'content' => 'required',
    ];

    public function render()
    {
        return view('livewire.companies.posts.company-post-create-component', [
            'categories' => CategoryPost::all()
        ]);
    }

    public function mount($user = null, $post = null, $company = null, $new = true)
    {
        if (is_null($post)) {
            $this->post = new Post();
            $this->title = '';
            $this->status = 'pending';
            $this->author = auth()->user()->company->name ?? '';

            $this->post->fill([
                'title' => 'New Post',
                'status' => $this->status,
                'author' => auth()->user()->company->name ?? '',
            ]);
        } else {
            $this->post = $post;
            $this->company = $company;
            $this->summary = $post->summary;
            $this->title = $post->title;
            $this->status = $post->status;
            $this->category = $post->category_id;
            $this->author = $post->author;
            $this->content = $post->content;
            $this->currentPicture = $this->post->picture->url ?? null;
        }
    }

    public function draft()
    {
        $data = [
            'title' => $this->title,
            'summary' => $this->summary,
            'category_id' => $this->category,
            'content' => $this->content,
            'author' => $this->author,
            'user_id' => auth()->user()->id,
        ];

        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $this->post->fill($data);
        $this->post->status = 'draft';
        $data['status'] = 'draft';
        $this->status = 'draft';
        $this->post->save($data);

        $this->post->refresh();

        $this->postID = $this->post->id;

        $this->alert()->success(['title' => 'Save Post as Draft']);

        return redirect()->route('company.preview.news', $this->postID);
    }

    public function save()
    {
        $data = [
            'title' => $this->title,
            'summary' => $this->summary,
            'category_id' => $this->category,
            'content' => $this->content,
            'author' => $this->author,
            'user_id' => auth()->user()->id,
        ];

        $validator = \Validator::make($data, $this->rules);

        if ($validator->fails()) {
            $message = $this->getErrorFromValidator($validator);

            $this->alert()->error(['title' => $message]);

            $validator->validate();
        }

        $data['status'] = 'publish';

        $this->status = 'publish';

        $this->post->fill($data);

        $this->post->save();

        $this->post->refresh();

        $this->postID = $this->post->id;

        if ($this->picture) {
            $file_name = 'picture_' . $this->post->id . '.' . $this->picture->getClientOriginalExtension();

            $this->post->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');

            $this->post = $this->post->refresh();

            $this->currentPicture = $this->post->picture->url;

            $this->alert()->success(['title' => 'Post Picture was updated']);

            $this->post = $this->post->refresh();
        }

        $this->alert()->success(['title' => 'Save Post as Publish']);

        return redirect()->route('company.preview.news', $this->postID);
    }

    /**
     * Livewire Hooks, when a property updated by any interaction, in this case when choose an image.
     */
    public function updatedPicture()
    {
        if ($this->postID) {
            $validator = \Validator::make(['picture' => $this->picture], ['picture' => 'image|max:102400',]);

            if ($validator->fails()) {
                $message = $this->getErrorFromValidator($validator);

                $this->alert()->error(['title' => $message]);

                $validator->validate();
            }

            $file_name = 'post_' . $this->post->id . '.' . $this->picture->getClientOriginalExtension();

            $this->post->addMedia($this->picture->getRealPath())->usingName($file_name)->toMediaCollection('picture');

            $this->post = $this->post->refresh();

            $this->currentPicture = $this->post->picture->url;

            $this->alert()->success(['title' => 'Post Picture was updated']);

            $this->emit('CompanyPostCreateComponent');

            $this->emit('updateDOM');
        } else {
            $this->currentPicture = $this->picture->temporaryUrl();
        }
    }

    public function hydrate()
    {
        $this->isUpdated = true;
    }

}
