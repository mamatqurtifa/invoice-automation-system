<?php

namespace App\Livewire\Orders;

use App\Models\FormResponse;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Project;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateFromResponse extends Component
{
    use AuthorizesRequests;

    public $project;
    public $formResponse;
    public $guestName;
    public $guestEmail;
    public $guestPhone;
    public $notes;
    
    public $availableProducts = [];
    public $selectedProducts = [];
    public $totalAmount = 0;
    
    protected $rules = [
        'guestName' => 'required|string|max:255',
        'guestEmail' => 'nullable|email|max:255',
        'guestPhone' => 'nullable|string|max:20',
        'notes' => 'nullable|string',
        'selectedProducts' => 'required|array|min:1',
        'selectedProducts.*.product_id' => 'required|exists:products,id',
        'selectedProducts.*.variant_id' => 'nullable|exists:product_variants,id',
        'selectedProducts.*.quantity' => 'required|integer|min:1',
    ];
    
    public function mount(Project $project, FormResponse $formResponse)
    {
        $this->authorize('view', $project);
        
        $this->project = $project;
        $this->formResponse = $formResponse;
        
        // Check if form response belongs to this project
        if ($formResponse->form->project_id !== $project->id) {
            abort(404);
        }
        
        // Pre-fill guest information
        $this->guestName = $formResponse->guest_name;
        $this->guestEmail = $formResponse->guest_email;
        $this->guestPhone = $formResponse->guest_phone;
        
        // Load available products for this project
        $this->availableProducts = $project->products()->with(['attributes', 'variants'])->get();
        
        // Initialize selected products array
        $this->selectedProducts = [
            [
                'product_id' => '',
                'variant_id' => '',
                'quantity' => 1,
                'price' => 0,
                'subtotal' => 0,
            ]
        ];
    }
    
    public function addProductRow()
    {
        $this->selectedProducts[] = [
            'product_id' => '',
            'variant_id' => '',
            'quantity' => 1,
            'price' => 0,
            'subtotal' => 0,
        ];
    }
    
    public function removeProductRow($index)
    {
        if (count($this->selectedProducts) > 1) {
            unset($this->selectedProducts[$index]);
            $this->selectedProducts = array_values($this->selectedProducts);
            $this->calculateTotal();
        }
    }
    
    public function updatedSelectedProducts($value, $key)
    {
        // Parse the key to extract index and property
        $parts = explode('.', $key);
        if (count($parts) === 3 && ($parts[2] === 'product_id' || $parts[2] === 'variant_id' || $parts[2] === 'quantity')) {
            $index = $parts[1];
            
            // Reset variant if product changes
            if ($parts[2] === 'product_id') {
                $this->selectedProducts[$index]['variant_id'] = '';
            }
            
            // Update price and subtotal
            $this->updatePriceAndSubtotal($index);
        }
    }
    
    protected function updatePriceAndSubtotal($index)
    {
        $productId = $this->selectedProducts[$index]['product_id'];
        $variantId = $this->selectedProducts[$index]['variant_id'];
        $quantity = max(1, intval($this->selectedProducts[$index]['quantity']));
        
        if (empty($productId)) {
            $this->selectedProducts[$index]['price'] = 0;
            $this->selectedProducts[$index]['subtotal'] = 0;
            $this->calculateTotal();
            return;
        }
        
        // Find product and price
        $product = collect($this->availableProducts)->firstWhere('id', $productId);
        if (!$product) {
            $this->selectedProducts[$index]['price'] = 0;
            $this->selectedProducts[$index]['subtotal'] = 0;
            $this->calculateTotal();
            return;
        }
        
        // Determine price (product or variant)
        if ($product->has_variants && !empty($variantId)) {
            $variant = $product->variants->firstWhere('id', $variantId);
            if ($variant) {
                $price = $variant->getPrice();
            } else {
                $price = $product->base_price;
            }
        } else {
            $price = $product->base_price;
        }
        
        $this->selectedProducts[$index]['price'] = $price;
        $this->selectedProducts[$index]['subtotal'] = $price * $quantity;
        
        $this->calculateTotal();
    }
    
    protected function calculateTotal()
    {
        $this->totalAmount = collect($this->selectedProducts)->sum('subtotal');
    }
    
    public function createOrder()
    {
        $this->validate();
        
        // Create order
        $order = Order::create([
            'project_id' => $this->project->id,
            'form_response_id' => $this->formResponse->id,
            'order_number' => Order::generateOrderNumber(),
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone' => $this->guestPhone,
            'total_amount' => $this->totalAmount,
            'amount_paid' => 0,
            'status' => 'pending',
            'notes' => $this->notes,
        ]);
        
        // Create order items
        foreach ($this->selectedProducts as $item) {
            $product = Product::findOrFail($item['product_id']);
            $variantId = !empty($item['variant_id']) ? $item['variant_id'] : null;
            $variantDetails = null;
            
            if ($variantId) {
                $variant = ProductVariant::findOrFail($variantId);
                $variantDetails = $variant->attribute_values;
            }
            
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_variant_id' => $variantId,
                'product_name' => $product->name,
                'variant_details' => $variantDetails,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $item['subtotal'],
            ]);
        }
        
        session()->flash('success', 'Order created successfully!');
        
        // Redirect to the order page
        return redirect()->route('projects.orders.show', [$this->project->id, $order->id]);
    }
    
    public function render()
    {
        return view('livewire.orders.create-from-response');
    }
}