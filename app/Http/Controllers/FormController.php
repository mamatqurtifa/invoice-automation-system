<?php

namespace App\Http\Controllers;

use App\Models\Form;
use App\Models\FormComponent;
use App\Models\FormResponse;
use App\Models\Project;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class FormController extends Controller
{
    use authorizesRequests;
    /**
     * Display a listing of the forms for a project.
     */
    public function index(Project $project)
    {
        $this->authorize('view', $project);
        
        $forms = $project->forms()
            ->where('is_template', false)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $templates = $project->forms()
            ->where('is_template', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('forms.index', compact('project', 'forms', 'templates'));
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
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
            'closing_date' => 'nullable|date',
            'closing_time' => 'nullable|string',
        ]);
        
        // Prepare closing_at datetime
        $closingAt = null;
        if (!empty($validated['closing_date'])) {
            if (!empty($validated['closing_time'])) {
                $closingAt = Carbon::parse($validated['closing_date'] . ' ' . $validated['closing_time']);
            } else {
                $closingAt = Carbon::parse($validated['closing_date'])->endOfDay();
            }
        }
        
        $form = Form::create([
            'project_id' => $project->id,
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
            'is_template' => $validated['is_template'] ?? false,
            'closing_at' => $closingAt,
        ]);
        
        return redirect()->route('projects.forms.edit', [$project, $form])
            ->with('success', 'Form created successfully. Add components to your form.');
    }

    /**
     * Show the form for editing the specified form.
     */
    public function edit(Project $project, Form $form = null, $templateId = null)
    {
        if ($form) {
            $this->authorize('update', $form);
        } else {
            $this->authorize('update', $project);
        }
        
        return view('forms.edit', compact('project', 'form', 'templateId'));
    }

    /**
     * Update the specified form in storage.
     */
    public function update(Request $request, Project $project, Form $form)
    {
        $this->authorize('update', $form);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_template' => 'boolean',
            'closing_date' => 'nullable|date',
            'closing_time' => 'nullable|string',
        ]);
        
        // Prepare closing_at datetime
        $closingAt = null;
        if (!empty($validated['closing_date'])) {
            if (!empty($validated['closing_time'])) {
                $closingAt = Carbon::parse($validated['closing_date'] . ' ' . $validated['closing_time']);
            } else {
                $closingAt = Carbon::parse($validated['closing_date'])->endOfDay();
            }
        }
        
        $form->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_active' => $validated['is_active'] ?? false,
            'is_template' => $validated['is_template'] ?? false,
            'closing_at' => $closingAt,
        ]);
        
        return redirect()->route('projects.forms.show', [$project, $form])
            ->with('success', 'Form updated successfully');
    }

    /**
     * Display the specified form.
     */
    public function show(Project $project, Form $form)
    {
        $this->authorize('view', $project);
        
        $responses = $form->responses()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Get components and organize by page
        $components = $form->components()->orderBy('order')->get();
        $pageComponents = [];
        
        foreach ($components as $component) {
            $pageComponents[$component->page][] = $component;
        }
        
        // Get total pages
        $totalPages = !empty($pageComponents) ? max(array_keys($pageComponents)) : 1;
        
        return view('forms.show', compact('project', 'form', 'responses', 'pageComponents', 'totalPages'));
    }

    /**
     * Remove the specified form from storage.
     */
    public function destroy(Project $project, Form $form)
    {
        $this->authorize('delete', $form);
        
        // Check if form has responses
        if ($form->responses()->count() > 0 && !$form->is_template) {
            return redirect()->route('projects.forms.show', [$project, $form])
                ->with('error', 'Cannot delete form that has responses. You may deactivate it instead.');
        }
        
        // Delete form components first
        $form->components()->delete();
        
        // Delete the form
        $form->delete();
        
        return redirect()->route('projects.forms.index', $project)
            ->with('success', 'Form deleted successfully');
    }

    /**
     * Toggle form active status.
     */
    public function toggleActive(Project $project, Form $form)
    {
        $this->authorize('update', $form);
        
        $form->update(['is_active' => !$form->is_active]);
        
        return redirect()->route('projects.forms.show', [$project, $form])
            ->with('success', 'Form ' . ($form->is_active ? 'activated' : 'deactivated') . ' successfully');
    }

    /**
     * Show the public form.
     */
    public function showPublic(Form $form)
    {
        // Check if form exists and is active
        if (!$form->exists() || !$form->is_active) {
            abort(404, 'Form not found or inactive');
        }
        
        // Check if form is closed
        if ($form->isClosed()) {
            return view('forms.closed', compact('form'));
        }
        
        // Get components and organize by page
        $components = $form->components()->orderBy('order')->get();
        $pageComponents = [];
        
        foreach ($components as $component) {
            $pageComponents[$component->page][] = $component;
        }
        
        // Get total pages
        $totalPages = !empty($pageComponents) ? max(array_keys($pageComponents)) : 1;
        
        // Get products for product components
        $productIds = [];
        foreach ($components as $component) {
            if ($component->type === 'product' && isset($component->properties['product_ids'])) {
                $productIds = array_merge($productIds, $component->properties['product_ids']);
            }
        }
        
        $products = Product::with(['variants'])->whereIn('id', array_unique($productIds))->get();
        
        return view('forms.public', compact('form', 'pageComponents', 'totalPages', 'products'));
    }

    /**
     * Submit the public form.
     */
    public function submitPublic(Request $request, Form $form)
    {
        // Check if form exists and is active
        if (!$form->exists() || !$form->is_active) {
            abort(404, 'Form not found or inactive');
        }
        
        // Check if form is closed
        if ($form->isClosed()) {
            return redirect()->back()->with('error', 'This form is no longer accepting submissions as it has closed.');
        }
        
        // Get required fields for validation
        $rules = [];
        
        foreach ($form->components as $component) {
            // Skip non-input components
            if (in_array($component->type, ['heading', 'paragraph', 'image', 'page_break'])) {
                continue;
            }
            
            // Generate field name based on component id
            $fieldName = 'field_' . $component->id;
            
            // Set validation rules
            if ($component->required) {
                $rules[$fieldName] = 'required';
            }
            
            // Add type-specific validation
            switch ($component->type) {
                case 'email':
                    $rules[$fieldName] = ($component->required ? 'required|' : '') . 'email';
                    break;
                case 'number':
                    $rules[$fieldName] = ($component->required ? 'required|' : '') . 'numeric';
                    break;
                case 'date':
                    $rules[$fieldName] = ($component->required ? 'required|' : '') . 'date';
                    break;
                case 'product':
                    if ($component->required) {
                        // For product components, validate that at least one product is selected
                        $rules[$fieldName . '.*'] = 'required';
                    }
                    break;
            }
        }
        
        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        // Process the form submission
        $name = '';
        $email = '';
        $phone = '';
        $responses = [];
        $orderItems = [];
        
        foreach ($form->components as $component) {
            // Skip non-input components
            if (in_array($component->type, ['heading', 'paragraph', 'image', 'page_break'])) {
                continue;
            }
            
            // Generate field name based on component id
            $fieldName = 'field_' . $component->id;
            
            // Save the response
            $response = $request->input($fieldName);
            
            // Extract name, email and phone if available
            if ($component->type == 'text' && stripos($component->label, 'name') !== false) {
                $name = $response;
            } else if ($component->type == 'email') {
                $email = $response;
            } else if ($component->type == 'phone') {
                $phone = $response;
            } else if ($component->type == 'product' && !empty($response)) {
                foreach ($response as $productData) {
                    if (empty($productData['product_id'])) {
                        continue;
                    }
                    
                    // Format: product_id:variant_id:quantity
                    $parts = explode(':', $productData['selection']);
                    $productId = $parts[0] ?? null;
                    $variantId = !empty($parts[1]) ? (int)$parts[1] : null;
                    $quantity = $parts[2] ?? 1;
                    
                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $price = $product->base_price;
                            $variantDetails = null;
                            
                            if ($variantId) {
                                $variant = ProductVariant::find($variantId);
                                if ($variant) {
                                    $price = $variant->getPrice();
                                    $variantDetails = $variant->attribute_values;
                                }
                            }
                            
                            $orderItems[] = [
                                'product_id' => $productId,
                                'product_variant_id' => $variantId,
                                'product_name' => $product->name,
                                'variant_details' => $variantDetails,
                                'price' => $price,
                                'quantity' => $quantity,
                                'subtotal' => $price * $quantity
                            ];
                        }
                    }
                }
                
                // Save a summary in the responses
                $responses[$component->id] = 'Product order: ' . count($orderItems) . ' items';
            } else {
                $responses[$component->id] = $response;
            }
        }
        
        // Create the form response
        $formResponse = FormResponse::create([
            'form_id' => $form->id,
            'guest_name' => $name,
            'guest_email' => $email,
            'guest_phone' => $phone,
            'responses' => $responses,
        ]);
        
        // If we have order items, create an order
        if (!empty($orderItems)) {
            $totalAmount = array_sum(array_column($orderItems, 'subtotal'));
            
            $order = Order::create([
                'project_id' => $form->project_id,
                'form_response_id' => $formResponse->id,
                'order_number' => Order::generateOrderNumber(),
                'guest_name' => $name,
                'guest_email' => $email,
                'guest_phone' => $phone,
                'total_amount' => $totalAmount,
                'amount_paid' => 0,
                'status' => 'pending',
            ]);
            
            // Create order items
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['product_variant_id'],
                    'product_name' => $item['product_name'],
                    'variant_details' => $item['variant_details'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);
            }
            
            return redirect()->route('orders.thank-you', $order)
                ->with('success', 'Your order has been submitted successfully!');
        }
        
        return redirect()->route('forms.thank-you', $formResponse)
            ->with('success', 'Form submitted successfully!');
    }

    /**
     * Show thank you page after form submission.
     */
    public function thankYou(FormResponse $formResponse)
    {
        $form = $formResponse->form;
        return view('forms.thank-you', compact('form', 'formResponse'));
    }

    /**
     * Show order thank you page.
     */
    public function orderThankYou(Order $order)
    {
        return view('orders.thank-you', compact('order'));
    }

    /**
     * View response details.
     */
    public function viewResponse(Project $project, Form $form, FormResponse $response)
    {
        $this->authorize('view', $project);
        
        // Group form components by ID for easier access
        $componentsById = [];
        foreach ($form->components as $component) {
            $componentsById[$component->id] = $component;
        }
        
        return view('forms.response', compact('project', 'form', 'response', 'componentsById'));
    }

    /**
     * Delete response.
     */
    public function deleteResponse(Project $project, Form $form, FormResponse $response)
    {
        $this->authorize('update', $project);
        
        // Check if response has an associated order
        if ($response->order) {
            return redirect()->back()
                ->with('error', 'Cannot delete response that has an associated order.');
        }
        
        $response->delete();
        
        return redirect()->route('projects.forms.show', [$project, $form])
            ->with('success', 'Response deleted successfully');
    }

    /**
     * Clone an existing form.
     */
    public function clone(Project $project, Form $form)
    {
        $this->authorize('update', $project);
        
        // Clone the form
        $newForm = $form->replicate();
        $newForm->name = $form->name . ' (Copy)';
        $newForm->is_active = false; // Set as inactive by default
        $newForm->save();
        
        // Clone form components
        foreach ($form->components as $component) {
            $newComponent = $component->replicate();
            $newComponent->form_id = $newForm->id;
            $newComponent->save();
        }
        
        return redirect()->route('projects.forms.edit', [$project, $newForm])
            ->with('success', 'Form cloned successfully. You are now editing the copy.');
    }

    /**
     * Display all responses for a form with detailed data.
     */
    public function responses(Project $project, Form $form)
    {
        $this->authorize('view', $project);
        
        $responses = $form->responses()
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        // Get components for the form (excluding layout components)
        $components = $form->components()
            ->whereNotIn('type', ['heading', 'paragraph', 'image', 'page_break'])
            ->orderBy('page')
            ->orderBy('order')
            ->get();
            
        return view('forms.responses', compact('project', 'form', 'responses', 'components'));
    }

    /**
     * Export responses as CSV.
     */
    public function exportResponsesCSV(Project $project, Form $form)
    {
        $this->authorize('view', $project);
        
        // Get all responses for the form
        $responses = $form->responses()->orderBy('created_at', 'desc')->get();
        
        // Get components for the form (excluding layout components)
        $components = $form->components()
            ->whereNotIn('type', ['heading', 'paragraph', 'image', 'page_break'])
            ->orderBy('page')
            ->orderBy('order')
            ->get();
        
        // Prepare CSV headers
        $headers = [
            'Response ID',
            'Guest Name',
            'Guest Email',
            'Guest Phone',
            'Submission Date',
        ];
        
        // Add component labels to headers
        foreach ($components as $component) {
            $headers[] = $component->label;
        }
        
        // Prepare CSV data
        $data = [];
        foreach ($responses as $response) {
            $row = [
                $response->id,
                $response->guest_name,
                $response->guest_email,
                $response->guest_phone,
                $response->created_at->format('Y-m-d H:i:s'),
            ];
            
            // Add response data for each component
            foreach ($components as $component) {
                $responseData = $response->responses[$component->id] ?? '';
                if (is_array($responseData)) {
                    $row[] = implode(', ', $responseData);
                } else {
                    $row[] = $responseData;
                }
            }
            
            $data[] = $row;
        }
        
        // Generate CSV
        $filename = $this->slugify($form->name) . '_responses_' . date('Ymd_His') . '.csv';
        
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, $headers);
        
        foreach ($data as $row) {
            fputcsv($handle, $row);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
    
    /**
     * Export responses as PDF.
     */
    public function exportResponsesPDF(Project $project, Form $form)
    {
        $this->authorize('view', $project);
        
        // Get all responses for the form
        $responses = $form->responses()->orderBy('created_at', 'desc')->get();
        
        // Get components for the form (excluding layout components)
        $components = $form->components()
            ->whereNotIn('type', ['heading', 'paragraph', 'image', 'page_break'])
            ->orderBy('page')
            ->orderBy('order')
            ->get();
        
        // Prepare data for PDF
        $data = [
            'form' => $form,
            'components' => $components,
            'responses' => $responses,
            'project' => $project,
            'generated_date' => Carbon::now()->format('Y-m-d H:i:s')
        ];
        
        $pdf = PDF::loadView('forms.exports.pdf-responses', $data);
        
        // Set PDF options if needed
        $pdf->setPaper('a4', 'landscape');
        
        // Generate PDF filename
        $filename = $this->slugify($form->name) . '_responses_' . date('Ymd_His') . '.pdf';
        
        // Return PDF download
        return $pdf->download($filename);
    }
    
    /**
     * Create a form from a template.
     */
    public function createFromTemplate(Project $project, Form $template)
    {
        $this->authorize('update', $project);
        
        return view('forms.create-from-template', [
            'project' => $project,
            'template' => $template,
        ]);
    }
    
    /**
     * Show form templates.
     */
    public function templates(Project $project)
    {
        $this->authorize('view', $project);
        
        $templates = Form::where('project_id', $project->id)
            ->where('is_template', true)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $publicTemplates = Form::where('is_template', true)
            ->where('is_active', true)
            ->where('project_id', '!=', $project->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('forms.templates', compact('project', 'templates', 'publicTemplates'));
    }
    
    /**
     * Update form closing date.
     */
    public function updateClosingDate(Request $request, Project $project, Form $form)
    {
        $this->authorize('update', $form);
        
        $validated = $request->validate([
            'closing_date' => 'nullable|date',
            'closing_time' => 'nullable|string',
        ]);
        
        $closingAt = null;
        if (!empty($validated['closing_date'])) {
            if (!empty($validated['closing_time'])) {
                $closingAt = $validated['closing_date'] . ' ' . $validated['closing_time'];
            } else {
                $closingAt = $validated['closing_date'] . ' 23:59:59';
            }
        }
        
        $form->update([
            'closing_at' => $closingAt,
        ]);
        
        return redirect()->route('projects.forms.show', [$project, $form])
            ->with('success', 'Form closing date updated successfully');
    }
    
    /**
     * Remove form closing date.
     */
    public function removeClosingDate(Project $project, Form $form)
    {
        $this->authorize('update', $form);
        
        $form->update([
            'closing_at' => null,
        ]);
        
        return redirect()->route('projects.forms.show', [$project, $form])
            ->with('success', 'Form closing date removed successfully');
    }
    
    /**
     * Save form as template
     */
    public function saveAsTemplate(Request $request, Project $project, Form $form)
    {
        $this->authorize('update', $form);
        
        $validated = $request->validate([
            'template_name' => 'required|string|max:255',
            'make_public' => 'nullable|boolean',
        ]);
        
        // Create a new form as template
        $template = $form->replicate();
        $template->name = $validated['template_name'];
        $template->is_template = true;
        $template->is_active = $request->has('make_public');  // Make it active if it's public
        $template->save();
        
        // Clone form components
        foreach ($form->components as $component) {
            $newComponent = $component->replicate();
            $newComponent->form_id = $template->id;
            $newComponent->save();
        }
        
        return redirect()->route('projects.forms.show', [$project, $template])
            ->with('success', 'Form saved as template successfully');
    }
    
    /**
     * Convert form response to order.
     */
    public function createOrderFromResponse(Project $project, Form $form, FormResponse $response)
    {
        $this->authorize('update', $project);
        
        // Check if an order already exists for this response
        if ($response->order) {
            return redirect()->route('projects.orders.show', [$project, $response->order])
                ->with('info', 'An order already exists for this response.');
        }
        
        return view('orders.create-from-response', compact('project', 'form', 'response'));
    }
    
    /**
     * Store an order created from a form response.
     */
    public function storeOrderFromResponse(Request $request, Project $project, Form $form, FormResponse $response)
    {
        $this->authorize('update', $project);
        
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.variant_id' => 'nullable|exists:product_variants,id',
            'products.*.quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);
        
        // Calculate order total
        $totalAmount = 0;
        $orderItems = [];
        
        foreach ($validated['products'] as $item) {
            $product = Product::find($item['product_id']);
            $price = $product->base_price;
            $variantDetails = null;
            
            if (!empty($item['variant_id'])) {
                $variant = ProductVariant::find($item['variant_id']);
                if ($variant) {
                    $price = $variant->getPrice();
                    $variantDetails = $variant->attribute_values;
                }
            }
            
            $subtotal = $price * $item['quantity'];
            $totalAmount += $subtotal;
            
            $orderItems[] = [
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['variant_id'] ?? null,
                'product_name' => $product->name,
                'variant_details' => $variantDetails,
                'price' => $price,
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
            ];
        }
        
        // Create the order
        $order = Order::create([
            'project_id' => $project->id,
            'form_response_id' => $response->id,
            'order_number' => Order::generateOrderNumber(),
            'guest_name' => $response->guest_name,
            'guest_email' => $response->guest_email,
            'guest_phone' => $response->guest_phone,
            'total_amount' => $totalAmount,
            'amount_paid' => 0,
            'status' => 'pending',
            'notes' => $validated['note'],
        ]);
        
        // Create order items
        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_variant_id' => $item['product_variant_id'],
                'product_name' => $item['product_name'],
                'variant_details' => $item['variant_details'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }
        
        return redirect()->route('projects.orders.show', [$project, $order])
            ->with('success', 'Order created successfully from form response.');
    }
    
    /**
     * Helper function to create a URL-friendly string
     */
    private function slugify($text) 
    {
        // Replace non letter or digit with -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // Trim
        $text = trim($text, '-');
        // Remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // Lowercase
        $text = strtolower($text);
        
        return $text ?: 'form';
    }
}