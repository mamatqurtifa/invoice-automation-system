<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PaymentMethodController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the payment methods.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $paymentMethods = $project->paymentMethods;
        return view('payment-methods.index', compact('project', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new payment method.
     */
    public function create(Project $project)
    {
        $this->authorize('update', $project);
        
        return view('payment-methods.create', compact('project'));
    }

    /**
     * Store a newly created payment method in storage.
     */
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,e_wallet,cash,other',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $paymentMethod = $project->paymentMethods()->create($validated);
        
        return redirect()->route('projects.payment-methods.index', $project)
            ->with('success', 'Payment method added successfully.');
    }

    /**
     * Show the form for editing the specified payment method.
     */
    public function edit(Project $project, PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $project);
        
        if ($paymentMethod->project_id !== $project->id) {
            abort(404);
        }
        
        return view('payment-methods.edit', compact('project', 'paymentMethod'));
    }

    /**
     * Update the specified payment method in storage.
     */
    public function update(Request $request, Project $project, PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $project);
        
        if ($paymentMethod->project_id !== $project->id) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bank_transfer,e_wallet,cash,other',
            'account_number' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        $paymentMethod->update($validated);
        
        return redirect()->route('projects.payment-methods.index', $project)
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified payment method from storage.
     */
    public function destroy(Project $project, PaymentMethod $paymentMethod)
    {
        $this->authorize('update', $project);
        
        if ($paymentMethod->project_id !== $project->id) {
            abort(404);
        }
        
        // Check if the payment method is used in any payments
        $paymentsCount = $paymentMethod->payments()->count();
        if ($paymentsCount > 0) {
            return redirect()->route('projects.payment-methods.index', $project)
                ->with('error', 'Cannot delete payment method that has been used in payments.');
        }
        
        $paymentMethod->delete();
        
        return redirect()->route('projects.payment-methods.index', $project)
            ->with('success', 'Payment method deleted successfully.');
    }
}