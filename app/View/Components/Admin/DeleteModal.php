<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DeleteModal extends Component
{
    /**
     * Create a new component instance.
     */
    protected string $className;
    protected string $id;
    protected string $formId;
    protected string $action;
    protected string $method;
    protected string $formClass;
    /** @var array <string, mixed> */
    protected array $hiddenInputs;
    protected string $title;
    protected string $description;
    protected string $deleteBtnType;
    protected string $deleteBtnId;
    protected string $deleteBtnText;
    protected string $modalIconClass;

    /**
     * @param array<string, mixed> $hiddenInputs
     */
    public function __construct(
        string $className = '',
        string $id = '',
        string $formId = '',
        string $action = '',
        string $method = '',
        string $formClass = '',
        array $hiddenInputs = [],
        string $title = '',
        string $description = '',
        string $deleteBtnType = 'submit',
        string $deleteBtnId = '',
        ?string $deleteBtnText = null,
        string $modalIconClass = 'ti ti-trash-x fs-26',
    ) {
        $this->className = $className;
        $this->id = $id;
        $this->formId = $formId;
        $this->action = $action;
        $this->method = $method;
        $this->formClass = $formClass;
        $this->hiddenInputs = $hiddenInputs;
        $this->title = $title;
        $this->description = $description;
        $this->deleteBtnType = $deleteBtnType;
        $this->deleteBtnId = $deleteBtnId;
        $this->deleteBtnText = $deleteBtnText ?? __('admin.common.yes_delete');
        $this->modalIconClass = $modalIconClass;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view(
            'components.admin.delete-modal',
            [
                'className' => $this->className,
                'id' => $this->id,
                'formId' => $this->formId,
                'action' => $this->action,
                'method' => $this->method,
                'formClass' => $this->formClass,
                'hiddenInputs' => $this->hiddenInputs,
                'title' => $this->title,
                'description' => $this->description,
                'deleteBtnType' => $this->deleteBtnType,
                'deleteBtnId' => $this->deleteBtnId,
                'deleteBtnText' => $this->deleteBtnText,
                'modalIconClass' => $this->modalIconClass,
            ]
        );
    }
}
