<?php

namespace App\Http\Livewire\Companies\Admin\News;

use App\Models\Post;
use App\Traits\SupportUiNotification;
use Livewire\Component;

class CompanyAdminNewLoopComponent extends Component
{
    use SupportUiNotification;

    public $post;
    public $status;
    public $toggleStatusLabel;
    protected $listeners = [
        'removePost' => 'doRemove',
        'companyAdminNewLoopComponent' => '$refresh',
        'renderCompanyAdminNewLoopComponent' => 'render',
        'disablePost' => 'doDisable',
        'enablePost' => 'doEnable',
    ];

    public function render()
    {
        $this->setStatus();
        return view('livewire.companies.admin.news.company-admin-new-loop-component');
    }

    public function mount()
    {
        $this->setStatus();
    }

    public function setStatus()
    {
        $status = $this->post->status;
        $this->toggleStatusLabel = $status === 'publish' ? 'Disable' : 'Enable';
        $this->status = $status;
    }


    public function setPublish($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to enable this Article?',
            'confirmButtonText' => 'Enable',
            'method' => 'enablePost',
            'params' => $id
        ]);
    }

    public function doEnable($id)
    {
        $obj = Post::find($id);
        if ($obj) {
            $obj->update(['status' => 'publish']);
            $this->alert()->success(['title' => 'Article Published']);
        }
        $this->setStatus();

        $this->emit('updateDOM');
        $this->emit('companyAdminNewLoopComponent');
        $this->emit('renderCompanyAdminNewLoopComponent');
        $this->emit('companyAdminNewsDashboardComponent');
        $this->emit('renderCompanyAdminNewsDashboardComponent');
    }

    public function setDisable($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure you want to disable this Article?',
            'confirmButtonText' => 'Disable',
            'method' => 'disablePost',
            'params' => $id
        ]);
    }

    public function doDisable($id)
    {
        $obj = Post::find($id);
        if ($obj) {
            $obj->update(['status' => 'disabled']);
            $this->alert()->success(['title' => 'Article Disabled']);
        }
        $this->setStatus();

        $this->emit('updateDOM');
        $this->emit('companyAdminNewLoopComponent');
        $this->emit('renderCompanyAdminNewLoopComponent');
        $this->emit('companyAdminNewsDashboardComponent');
        $this->emit('renderCompanyAdminNewsDashboardComponent');
    }


    public function delete($id)
    {
        $this->alert()->confirm([
            'icon' => 'warning',
            'title' => 'Are you sure want to disabled this Post?',
            'confirmButtonText' => 'Disable',
            'method' => 'removePost',
            'params' => $id
        ]);
    }

    public function doRemove($id)
    {
        $obj = Post::find($id);
        if ($obj) {
            $obj->delete();
        }
        $this->alert()->success(['title' => 'News was deleted']);
        $this->emit('updateDOM');
        return redirect()->route('company.admin.news');
    }
}
