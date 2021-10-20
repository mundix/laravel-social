<?php

namespace App\Http\Livewire\Companies\Profile\Edit\Posts;

use App\Models\Post;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyProfileEditPostLoopComponent extends Component
{
    use SupportUiNotification;
    public $post;

    protected $listeners = ['removePost' => 'doRemove'];

    public function render()
    {
        return view('livewire.companies.profile.edit.posts.company-profile-edit-post-loop-component');
    }

    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Do you want to remove  this Post?',
            'confirmButtonText' => 'Yes',
            'method' => 'removePost',
            'params' => $id
        ]);
    }

    public function doRemove($id)
    {
        Post::find($id)->delete();
        $this->alert()->success(['title' => 'News was deleted']);
        $this->emit('updateDOM');
        return redirect()->route('company.admin.index');
    }
}
