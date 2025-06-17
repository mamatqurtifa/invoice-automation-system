<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Frequently Asked Questions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">What is Invoice Automation System?</h3>
                            <p class="mt-2 text-gray-600">
                                Invoice Automation System is a comprehensive platform designed to help businesses automate their order, inventory, and invoicing processes. It enables creating dynamic forms for order collection, managing product variants, processing payments, and generating professional invoices automatically.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How do I create a form for customers to order?</h3>
                            <p class="mt-2 text-gray-600">
                                To create a form, go to a project and click on Forms. From there, select "Create New Form". Use the form builder to add various components like text fields, dropdown selections, and image uploads. You can preview the form as you build it, and once you're done, save it. The form will have a public URL that you can share with your customers.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How do I add products with different variants?</h3>
                            <p class="mt-2 text-gray-600">
                                To add products, go to a project and click on Products. Click "Add New Product" and fill in the basic product information. If your product has variants (like different sizes or colors), check "This product has multiple variants" and add the attributes (like Size or Color) with their options. The system will generate all possible combinations of variants, where you can set specific prices and stock levels for each variant.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How do I handle down payments?</h3>
                            <p class="mt-2 text-gray-600">
                                When recording a payment for an order, you can select the payment type as "Down Payment". The system will track how much has been paid and how much is still due. You can later record additional payments as "Installment" payments until the order is fully paid.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Can I send invoices via WhatsApp?</h3>
                            <p class="mt-2 text-gray-600">
                                Yes, when viewing an invoice, you'll see a "Share via WhatsApp" button if the customer has provided a phone number. Clicking this button will generate a pre-filled WhatsApp message with the invoice details and a link to view the invoice online.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How do I generate reports?</h3>
                            <p class="mt-2 text-gray-600">
                                To generate financial reports, go to a project and click on the "Financial Report" option. You can select different time periods or set a custom date range. The report will show you key metrics like total sales, received revenue, and payment trends over time. You can also export your data as CSV files for further analysis.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Can I customize the invoice template?</h3>
                            <p class="mt-2 text-gray-600">
                                Currently, the system uses a standard invoice template that includes your business information, customer details, and order information. For customized templates, please contact the system administrator who can help modify the template to meet your specific needs.
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">How do I track inventory?</h3>
                            <p class="mt-2 text-gray-600">
                                When creating or editing a product, check the "Track inventory for this product" option. For simple products, you can enter the stock quantity directly. For products with variants, you can specify stock levels for each variant combination. The system will show inventory status on product listings and can warn you when stock is low.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>