<?php

namespace App\Livewire\FormBuilder;

use App\Models\Form;
use App\Models\FormComponent;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FormEditor extends Component
{
    use AuthorizesRequests;

    public $form;
    public $project;
    public $formName = '';
    public $formDescription = '';
    public $isTemplate = false;
    public $components = [];
    public $editingComponent = null;
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
        'paragraph' => 'Paragraph'
    ];

    protected $rules = [
        'formName' => 'required|string|max:255',
        'formDescription' => 'nullable|string',
        'isTemplate' => 'boolean',
        'components' => 'array',
        'components.*.type' => 'required|string',
        'components.*.label' => 'required|string',
        'components.*.required' => 'boolean',
        'components.*.properties' => 'nullable',
    ];

    public function mount($projectId = null, $formId = null)
    {
        if ($formId) {
            $this->form = Form::findOrFail($formId);
            $this->authorize('update', $this->form);
            $this->formName = $this->form->name;
            $this->formDescription = $this->form->description;
            $this->isTemplate = $this->form->is_template;
            $this->project = $this->form->project;
            
            // Load existing components
            $this->components = $this->form->components->map(function($component) {
                return [
                    'id' => $component->id,
                    'type' => $component->type,
                    'label' => $component->label,
                    'required' => $component->required,
                    'properties' => $component->properties,
                    'order' => $component->order,
                ];
            })->toArray();
        } else {
            $this->project = Project::findOrFail($projectId);
            $this->authorize('update', $this->project);
            $this->components = [];
        }
    }

    public function addComponent($type)
    {
        $newComponent = [
            'type' => $type,
            'label' => $this->componentTypes[$type],
            'required' => false,
            'properties' => $this->getDefaultPropertiesForType($type),
            'order' => count($this->components),
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

    public function saveForm()
    {
        $this->validate();
        
        if ($this->form) {
            // Update existing form
            $this->form->update([
                'name' => $this->formName,
                'description' => $this->formDescription,
                'is_template' => $this->isTemplate,
            ]);
            
            // Delete existing components
            $this->form->components()->delete();
        } else {
            // Create new form
            $this->form = Form::create([
                'project_id' => $this->project->id,
                'name' => $this->formName,
                'description' => $this->formDescription,
                'is_template' => $this->isTemplate,
            ]);
        }
        
        // Save components
        foreach ($this->components as $index => $component) {
            FormComponent::create([
                'form_id' => $this->form->id,
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
                ];
            case 'heading':
                return [
                    'level' => 'h2',
                ];
            default:
                return [];
        }
    }

    public function render()
    {
        return view('livewire.form-builder.form-editor');
    }
}