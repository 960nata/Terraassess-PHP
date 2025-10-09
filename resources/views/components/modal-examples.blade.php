{{-- Modal Examples --}}

{{-- Default Size Modal (512px) --}}
<x-modal id="defaultModal" title="Default Modal" size="default">
    <p>This is a default size modal with max-width of 512px.</p>
    <p>It's perfect for simple confirmations, alerts, or small forms.</p>
    
    <div class="mt-4">
        <h4 class="font-semibold text-gray-900 mb-2">Features:</h4>
        <ul class="list-disc list-inside text-sm text-gray-600 space-y-1">
            <li>Responsive design</li>
            <li>Smooth animations</li>
            <li>Backdrop blur effect</li>
            <li>Keyboard navigation (ESC to close)</li>
        </ul>
    </div>
    
    <x-slot name="footer">
        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('defaultModal')">
            Cancel
        </button>
        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Confirm
        </button>
    </x-slot>
</x-modal>

{{-- Large Size Modal (768px) --}}
<x-modal id="largeModal" title="Large Modal" size="large">
    <div class="space-y-4">
        <p>This is a large size modal with max-width of 768px.</p>
        <p>Perfect for forms, detailed content, or data tables.</p>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-2">Form Example:</h4>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter your message"></textarea>
                </div>
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('largeModal')">
            Cancel
        </button>
        <button type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
            Save Changes
        </button>
    </x-slot>
</x-modal>

{{-- XL Size Modal (1024px) --}}
<x-modal id="xlModal" title="Extra Large Modal" size="xl">
    <div class="space-y-6">
        <p>This is an extra large modal with max-width of 1024px.</p>
        <p>Ideal for complex dashboards, data visualization, or comprehensive forms.</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-semibold text-blue-900 mb-2">Statistics</h4>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Total Users</span>
                        <span class="font-semibold text-blue-900">1,234</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Active Sessions</span>
                        <span class="font-semibold text-blue-900">456</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Revenue</span>
                        <span class="font-semibold text-blue-900">$12,345</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-semibold text-green-900 mb-2">Recent Activity</h4>
                <div class="space-y-2">
                    <div class="text-sm text-green-700">Sistem aktif</div>
                    <div class="text-sm text-green-700">Data real dari database</div>
                    <div class="text-sm text-green-700">Sistem berjalan normal</div>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-50 p-4 rounded-lg">
            <h4 class="font-semibold text-gray-900 mb-3">Data Table Example</h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">1</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Data Real</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">Active</span></td>
                            <td class="px-4 py-2"><button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button></td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 text-sm text-gray-900">2</td>
                            <td class="px-4 py-2 text-sm text-gray-900">Jane Smith</td>
                            <td class="px-4 py-2"><span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded-full">Pending</span></td>
                            <td class="px-4 py-2"><button class="text-blue-600 hover:text-blue-800 text-sm">Edit</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <x-slot name="footer">
        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('xlModal')">
            Close
        </button>
        <button type="button" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
            Export Data
        </button>
    </x-slot>
</x-modal>

{{-- Modal without header --}}
<x-modal id="noHeaderModal" :showCloseButton="false" :closeOnBackdrop="true">
    <div class="text-center py-8">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="ph-check text-2xl text-green-600"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Success!</h3>
        <p class="text-gray-600 mb-6">Your action has been completed successfully.</p>
        <button type="button" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors" onclick="closeModal('noHeaderModal')">
            Continue
        </button>
    </div>
</x-modal>

{{-- Modal with custom content --}}
<x-modal id="customModal" title="Custom Content Modal" size="large">
    <div class="prose max-w-none">
        <h2>Custom Content Example</h2>
        <p>This modal demonstrates how to use custom content with the modal component.</p>
        
        <h3>Features:</h3>
        <ul>
            <li><strong>Flexible sizing:</strong> Choose from default, large, or xl</li>
            <li><strong>Customizable header:</strong> Optional title and close button</li>
            <li><strong>Footer support:</strong> Add custom footer content</li>
            <li><strong>Accessibility:</strong> Proper focus management and keyboard navigation</li>
            <li><strong>Responsive:</strong> Works on all screen sizes</li>
        </ul>
        
        <h3>Usage:</h3>
        <pre class="bg-gray-100 p-4 rounded-lg text-sm overflow-x-auto"><code>&lt;x-modal id="myModal" title="My Modal" size="large"&gt;
    &lt;p&gt;Your content here&lt;/p&gt;
    
    &lt;x-slot name="footer"&gt;
        &lt;button onclick="closeModal('myModal')"&gt;Close&lt;/button&gt;
    &lt;/x-slot&gt;
&lt;/x-modal&gt;</code></pre>
    </div>
    
    <x-slot name="footer">
        <button type="button" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors" onclick="closeModal('customModal')">
            Close
        </button>
    </x-slot>
</x-modal>
