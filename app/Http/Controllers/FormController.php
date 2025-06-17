<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FormController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the forms.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $forms = $project->forms;
        return view('forms.index', compact('project', 'forms'));
    }

    /**
     * Show the form for creating a new form.
     */
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        return view('forms.create', compact('project'));
    }

    /**
     * Store a newly created form in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        // Form creation is handled by Livewire component
        // This method is included for completeness but not used
        return redirect()->route('projects.forms.index', $project);
    }

    /**
     * Display the specified form.
     */
    public function show(Project $project, Form $form)
    {
        $this->authorize('view', $project);
        
        return view('forms.show', compact('project', 'form'));
    }

    /**
     * Show the form for editing the specified form.
     */
    public function edit(Project $project, Form $form)
    {
        $this->authorize('update', $project);
        
        return view('forms.edit', compact('project', 'form'));
    }

    /**
     * Update the specified form in storage.
     */
    public function update(Request $request, Project $project, Form $form)
    {
        $this->authorize('update', $project);
        
        // Form update is handled by Livewire component
        // This method is included for completeness but not used
        return redirect()->route('projects.forms.index', $project);
    }

    /**
     * Remove the specified form from storage.
     */
    public function destroy(Project $project, Form $form)
    {
        $this->authorize('update', $project);
        
        // Delete the form and its components
        $form->delete();
        
        return redirect()->route('projects.forms.index', $project)
            ->with('success', 'Form deleted successfully');
    }
    
    /**
     * Show public form for guests
     */
    public function publicForm($formId)
    {
        $form = Form::findOrFail($formId);
        
        // Check if form is active
        if (!$form->is_active) {
            abort(404);
        }
        
        return view('forms.public', compact('form'));
    }
    
    /**
     * Store form response from public
     */
    public function storeResponse(Request $request, $formId)
    {
        $form = Form::with('components')->findOrFail($formId);
        
        // Check if form is active
        if (!$form->is_active) {
            abort(404);
        }
        
        // Validate required components
        $validationRules = [];
        $components = $form->components;
        
        foreach ($components as $component) {
            if ($component->required && in_array($component->type, ['text', 'email', 'number', 'textarea', 'select', 'radio', 'date', 'phone'])) {
                $validationRules["responses.{$component->id}"] = 'required';
                
                if ($component->type === 'email') {
                    $validationRules["responses.{$component->id}"] .= '|email';
                } elseif ($component->type === 'number') {
                    $validationRules["responses.{$component->id}"] .= '|numeric';
                }
            }
        }
        
        // Add guest info validation
        $validationRules['guest_name'] = 'required|string|max:255';
        $validationRules['guest_email'] = 'nullable|email|max:255';
        $validationRules['guest_phone'] = 'nullable|string|max:20';
        
        $validated = $request->validate($validationRules);
        
        // Create form response
        $formResponse = $form->responses()->create([
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'] ?? null,
            'guest_phone' => $validated['guest_phone'] ?? null,
            'responses' => $request->input('responses', []),
        ]);
        
        return redirect()->route('forms.thankyou', $form->id)
            ->with('success', 'Your response has been submitted successfully.');
    }
    
    /**
     * Thank you page after form submission
     */
    public function thankYou($formId)
    {
        $form = Form::findOrFail($formId);
        return view('forms.thankyou', compact('form'));
    }
}