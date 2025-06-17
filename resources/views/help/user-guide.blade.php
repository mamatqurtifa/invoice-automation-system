<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Guide') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="prose max-w-none">
                        <h2>Getting Started with Invoice Automation System</h2>
                        
                        <p>This user guide will help you understand how to use the key features of the Invoice Automation System to efficiently manage your orders, products, and invoices.</p>
                        
                        <h3>1. Projects</h3>
                        
                        <p>Projects are the top-level organizational unit in the system. A project typically represents a sales campaign or event.</p>
                        
                        <h4>Creating a Project</h4>
                        <ol>
                            <li>From the dashboard, click "Create New Project"</li>
                            <li>Enter a name and description</li>
                            <li>Select the project type (preorder, ready stock, or other)</li>
                            <li>Set the start date and optional end date</li>
                            <li>Click "Create Project"</li>
                        </ol>
                        
                        <h3>2. Forms</h3>
                        
                        <p>Forms allow you to collect information from customers.</p>
                        
                        <h4>Building a Form</h4>
                        <ol>
                            <li>Go to your project and select "Forms"</li>
                            <li>Click "Create New Form"</li>
                            <li>Enter form details (name, description)</li>
                            <li>Use the form builder to add components:
                                <ul>
                                    <li>Text fields for names, addresses, etc.</li>
                                    <li>Select dropdowns for options</li>
                                    <li>Image fields for design uploads</li>
                                    <li>And many more component types</li>
                                </ul>
                            </li>
                            <li>Preview your form as you build it</li>
                            <li>Save when you're satisfied with the design</li>
                        </ol>
                        
                        <h4>Sharing a Form</h4>
                        <p>Once saved, your form will have a public URL that you can share with customers. Access this URL from the form details page.</p>
                        
                        <h3>3. Products</h3>
                        
                        <h4>Adding Simple Products</h4>
                        <ol>
                            <li>Go to your project and select "Products"</li>
                            <li>Click "Add New Product"</li>
                            <li>Fill in the basic information (name, description, price)</li>
                            <li>Optionally upload a product image</li>
                            <li>If you want to track inventory, check "Track inventory" and set the quantity</li>
                            <li>Click "Create Product"</li>
                        </ol>
                        
                        <h4>Creating Products with Variants</h4>
                        <ol>
                            <li>Follow steps 1-4 above</li>
                            <li>Check "This product has multiple variants"</li>
                            <li>Add attributes (like Size, Color) and their values</li>
                            <li>Click "Generate Variants" to create all possible combinations</li>
                            <li>For each variant, set price adjustments and stock levels</li>
                            <li>Click "Create Product"</li>
                        </ol>
                        
                        <h3>4. Orders</h3>
                        
                        <h4>Creating Orders from Form Responses</h4>
                        <ol>
                            <li>Go to a form's details page</li>
                            <li>Find the customer's form response</li>
                            <li>Click "Create Order" on that response</li>
                            <li>Add the products the customer ordered</li>
                            <li>Verify customer information and set quantities</li>
                            <li>Click "Create Order"</li>
                        </ol>
                        
                        <h4>Managing Orders</h4>
                        <ol>
                            <li>Go to your project and select "Orders"</li>
                            <li>Click on any order to see its details</li>
                            <li>Update order status as needed (pending, processing, completed)</li>
                            <li>Record payments received</li>
                            <li>Generate invoices</li>
                        </ol>
                        
                        <h3>5. Payments</h3>
                        
                        <h4>Setting Up Payment Methods</h4>
                        <ol>
                            <li>Go to your project and select "Payment Methods"</li>
                            <li>Click "Add Payment Method"</li>
                            <li>Enter the details (name, type, account info)</li>
                            <li>Click "Save Payment Method"</li>
                        </ol>
                        
                        <h4>Recording Payments</h4>
                        <ol>
                            <li>Navigate to the order details page</li>
                            <li>Scroll down to the "Record a Payment" section</li>
                            <li>Select the payment method</li>
                            <li>Enter the amount paid</li>
                            <li>Select the payment type (full payment, down payment, or installment)</li>
                            <li>Optionally upload proof of payment</li>
                            <li>Click "Record Payment"</li>
                        </ol>
                        
                        <h3>6. Invoices</h3>
                        
                        <h4>Generating Invoices</h4>
                        <ol>
                            <li>Go to an order's details page</li>
                            <li>Click "Generate Invoice"</li>
                            <li>Select invoice type (commercial, proforma, or receipt)</li>
                            <li>The system will create a PDF invoice automatically</li>
                        </ol>
                        
                        <h4>Sharing Invoices</h4>
                        <ol>
                            <li>From the invoice details page, click "Share via WhatsApp"</li>
                            <li>This will open WhatsApp with a pre-filled message</li>
                            <li>Alternatively, click "Download PDF" and share the document directly</li>
                        </ol>
                        
                        <h3>7. Reports</h3>
                        
                        <h4>Viewing Financial Reports</h4>
                        <ol>
                            <li>Go to your project and select "Financial Report"</li>
                            <li>Select the reporting period</li>
                            <li>View visualizations of your sales and payment data</li>
                            <li>Use the export buttons to download data as CSV files</li>
                        </ol>
                        
                        <h3>Need More Help?</h3>
                        <p>If you have additional questions or need help with specific features, please refer to the <a href="{{ route('faq') }}">FAQ</a> or contact support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>