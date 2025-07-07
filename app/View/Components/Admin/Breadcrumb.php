<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    protected string $title;
    /** @var array<string, mixed> */
    protected array $breadcrumbs;
    protected string $buttonText;
    protected string $buttonId;
    protected string $modalId;
    protected string $permissionKey;
    protected string $permissionModule;

    /**
     * @param array<string, mixed> $breadcrumbs
     */
    public function __construct(
        string $title,
        array $breadcrumbs = [],
        string $buttonText = '',
        string $buttonId = '',
        string $modalId = '',
        string $permissionKey = 'create',
        string $permissionModule = '',
    ) {
        $this->title = $title;
        $this->breadcrumbs = $breadcrumbs;
        $this->buttonText = $buttonText;
        $this->buttonId = $buttonId;
        $this->modalId = $modalId;
        $this->permissionKey = $permissionKey;
        $this->permissionModule = $permissionModule;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'components.admin.breadcrumb',
            [
                'title' => $this->title,
                'breadcrumbs' => $this->breadcrumbs,
                'buttonText' => $this->buttonText,
                'buttonId' => $this->buttonId,
                'modalId' => $this->modalId,
                'permissionKey' => $this->permissionKey,
                'permissionModule' => $this->permissionModule,
            ]
        );
    }
}
