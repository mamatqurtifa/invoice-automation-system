<?php

namespace App\Livewire\FormBuilder;

use App\Models\Form;
use App\Models\FormComponent;
use App\Models\Project;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class FormEditor extends Component
{
    use AuthorizesRequests, WithFileUploads;

    public $form;
    public $project;
    public $formName = '';
    public $formDescription = '';
    public $isActive = true;
    public $isTemplate = false;
    public $closingDate = null;
    public $closingTime = null;
    public $pages = [1];
    public $currentPage = 1;
    public $components = [];
    public $editingComponent = null;
    public $uploadedImage = null;
    public $availableProducts = [];
    
    public $componentTypes = [
        'text' => 'Text',
        'textarea' => 'Textarea',
        'number' => 'Number',
        'email' => 'Email',
        'phone' => 'Phone',
        'select' => 'Select',
        'radio' => 'Radio Buttons',
        'checkbox' => 'Checkbox',
        'date' => 'Date',
        'image' => 'Image',
        'heading' => 'Heading',
        'paragraph' => 'Paragraph',
        'product' => 'Product', // New component type
        'page_break' => 'Page Break', // New component type
    ];

    protected $rules = [
        'formName' => 'required|string|max:255',
        'formDescription' => 'nullable|string',
        'isActive' => 'boolean',
        'isTemplate' => 'boolean',
        'closingDate' => 'nullable|date',
        'closingTime' => 'nullable|string',
        'components' => 'array',
        'components.*.page' => 'required|integer|min:1',
        'components.*.type' => 'required|string',
        'components.*.label' => 'required|string',
        'components.*.required' => 'boolean',
        'components.*.properties' => 'nullable',
    ];

    public function mount($projectId = null, $formId = null, $templateId = null)
    {
        if ($formId) {
            $this->form = Form::findOrFail($formId);
            $this->authorize('update', $this->form);
            $this->formName = $this->form->name;
            $this->formDescription = $this->form->description;
            $this->isActive = $this->form->is_active;
            $this->isTemplate = $this->form->is_template;
            $this->project = $this->form->project;
            
            // Set closing date and time if exists
            if ($this->form->closing_at) {
                $this->closingDate = $this->form->closing_at->format('Y-m-d');
                $this->closingTime = $this->form->closing_at->format('H:i');
            }
            
            // Load existing components
            $existingComponents = $this->form->components->toArray();
            
            // Determine pages from components
            $pages = collect($existingComponents)->pluck('page')->unique()->sort()->values()->toArray();
            $this->pages = empty($pages) ? [1] : $pages;
            
            $this->components = $existingComponents;
        } elseif ($templateId) {
            // Create from template
            $template = Form::findOrFail($templateId);
            $this->authorize('view', $template);
            
            $this->formName = $template->name . ' (Copy)';
            $this->formDescription = $template->description;
            $this->isActive = true;
            $this->isTemplate = false;
            $this->project = Project::findOrFail($projectId);
            $this->authorize('update', $this->project);
            
            // Load template components, but clear IDs to create new ones
            $templateComponents = $template->components->toArray();
            foreach ($templateComponents as &$component) {
                unset($component['id']);
            }
            
            // Determine pages from components
            $pages = collect($templateComponents)->pluck('page')->unique()->sort()->values()->toArray();
            $this->pages = empty($pages) ? [1] : $pages;
            
            $this->components = $templateComponents;
        } else {
            $this->project = Project::findOrFail($projectId);
            $this->authorize('update', $this->project);
            $this->components = [];
        }
        
        // Load products for product component
        $this->availableProducts = $this->project->products()->with(['variants'])->get();
    }

    public function addComponent($type)
    {
        $newComponent = [
            'type' => $type,
            'label' => $this->componentTypes[$type],
            'required' => false,
            'properties' => $this->getDefaultPropertiesForType($type),
            'order' => count($this->components),
            'page' => $this->currentPage,
        ];

        $this->components[] = $newComponent;
        $this->editingComponent = count($this->components) - 1;
    }

    public function editComponent($index)
    {
        $this->editingComponent = $index;
    }

    public function updateComponent()
    {
        $this->editingComponent = null;
    }

    public function removeComponent($index)
    {
        array_splice($this->components, $index, 1);
        $this->editingComponent = null;
        
        // Update order for remaining components
        foreach ($this->components as $key => $component) {
            $this->components[$key]['order'] = $key;
        }
    }

    public function moveComponentUp($index)
    {
        if ($index > 0) {
            $temp = $this->components[$index];
            $this->components[$index] = $this->components[$index - 1];
            $this->components[$index - 1] = $temp;
            
            // Update order
            $this->components[$index]['order'] = $index;
            $this->components[$index - 1]['order'] = $index - 1;
        }
    }

    public function moveComponentDown($index)
    {
        if ($index < count($this->components) - 1) {
            $temp = $this->components[$index];
            $this->components[$index] = $this->components[$index + 1];
            $this->components[$index + 1] = $temp;
            
            // Update order
            $this->components[$index]['order'] = $index;
            $this->components[$index + 1]['order'] = $index + 1;
        }
    }
    
    public function addPage()
    {
        $nextPage = max($this->pages) + 1;
        $this->pages[] = $nextPage;
        $this->currentPage = $nextPage;
    }
    
    public function switchToPage($page)
    {
        $this->currentPage = $page;
    }
    
    public function removePage($page)
    {
        if (count($this->pages) <= 1) {
            session()->flash('error', 'Cannot remove the only page. Forms must have at least one page.');
            return;
        }
        
        // Remove the page
        $this->pages = array_values(array_filter($this->pages, function($p) use ($page) {
            return $p != $page;
        }));
        
        // Remove components on this page
        $this->components = array_values(array_filter($this->components, function($component) use ($page) {
            return $component['page'] != $page;
        }));
        
        // Renumber remaining pages and their components
        $newPages = [];
        $pageMap = [];
        
        foreach ($this->pages as $index => $oldPage) {
            $newPageNumber = $index + 1;
            $newPages[] = $newPageNumber;
            $pageMap[$oldPage] = $newPageNumber;
        }
        
        $this->pages = $newPages;
        
        // Update components to use new page numbers
        foreach ($this->components as $index => $component) {
            $this->components[$index]['page'] = $pageMap[$component['page']];
        }
        
        // Set current page
        $this->currentPage = min($this->currentPage, max($this->pages));
    }
    
    public function uploadImageForComponent($index)
    {
        $this->validate([
            'uploadedImage' => 'required|image|max:5120', // 5MB max
        ]);
        
        // Store the image
        $path = $this->uploadedImage->store('form-images', 'public');
        
        // Update component properties with the image path
        $this->components[$index]['properties']['url'] = Storage::url($path);
        
        // Clear the uploaded image
        $this->uploadedImage = null;
    }

    public function saveForm()
    {
        $this->validate();
        
        // Prepare closing_at datetime
        $closingAt = null;
        if ($this->closingDate) {
            if ($this->closingTime) {
                $closingAt = Carbon::parse($this->closingDate . ' ' . $this->closingTime);
            } else {
                $closingAt = Carbon::parse($this->closingDate)->endOfDay();
            }
        }
        
        if ($this->form) {
            // Update existing form
            $this->form->update([
                'name' => $this->formName,
                'description' => $this->formDescription,
                'is_active' => $this->isActive,
                'is_template' => $this->isTemplate,
                'closing_at' => $closingAt,
            ]);
            
            // Delete existing components
            $this->form->components()->delete();
        } else {
            // Create new form
            $this->form = Form::create([
                'project_id' => $this->project->id,
                'name' => $this->formName,
                'description' => $this->formDescription,
                'is_active' => $this->isActive,
                'is_template' => $this->isTemplate,
                'closing_at' => $closingAt,
            ]);
        }
        
        // Save components
        foreach ($this->components as $index => $component) {
            FormComponent::create([
                'form_id' => $this->form->id,
                'page' => $component['page'],
                'type' => $component['type'],
                'label' => $component['label'],
                'required' => $component['required'] ?? false,
                'properties' => $component['properties'] ?? [],
                'order' => $index,
            ]);
        }
        
        session()->flash('message', 'Form saved successfully!');
        return redirect()->route('projects.forms.index', $this->project);
    }

    private function getDefaultPropertiesForType($type)
    {
        switch ($type) {
            case 'select':
            case 'radio':
                return [
                    'options' => ['Option 1', 'Option 2', 'Option 3'],
                ];
            case 'checkbox':
                return [
                    'options' => ['Option 1'],
                ];
            case 'image':
                return [
                    'width' => '100%',
                    'height' => 'auto',
                    'url' => '',
                    'alignment' => 'center',
                ];
            case 'heading':
                return [
                    'level' => 'h2',
                ];
            case 'product':
                return [
                    'product_ids' => [],
                    'show_images' => true,
                    'show_variants' => true,
                    'show_prices' => true,
                    'allow_quantity' => true,
                ];
            case 'page_break':
                return [
                    'next_button_text' => 'Next',
                    'prev_button_text' => 'Previous',
                ];
            default:
                return [];
        }
    }
    
    public function getComponentsForCurrentPage()
    {
        return array_values(array_filter($this->components, function($component) {
            return $component['page'] == $this->currentPage;
        }));
    }

    public function render()
    {
        return view('livewire.form-builder.form-editor', [
            'componentsForCurrentPage' => $this->getComponentsForCurrentPage(),
        ]);
    }
}