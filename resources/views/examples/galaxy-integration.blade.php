<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galaxy Integration Example</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('css/galaxy-design-system.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="text-white">
    <div class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold mb-8 text-center">Galaxy Integration Example</h1>
        
        <!-- User Profile Card -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">User Profile Card</h2>
            <div class="max-w-md">
                <x-galaxy-card title="John Doe" subtitle="Software Developer">
                    <div class="space-y-4">
                        <p class="text-sm text-gray-300">Experienced software developer with expertise in modern web technologies.</p>
                        <div class="flex gap-2">
                            <span class="galaxy-badge galaxy-badge-success">Active</span>
                            <span class="galaxy-badge galaxy-badge-info">Premium</span>
                        </div>
                        <div class="flex gap-2">
                            <x-galaxy-button variant="primary" size="sm">Message</x-galaxy-button>
                            <x-galaxy-button variant="secondary" size="sm">Settings</x-galaxy-button>
                        </div>
                    </div>
                </x-galaxy-card>
            </div>
        </section>

        <!-- Contact Form -->
        <section class="mb-12">
            <h2 class="text-2xl font-semibold mb-6">Contact Form</h2>
            <div class="max-w-lg">
                <x-galaxy-card title="Get in Touch" subtitle="Send us a message">
                    <form class="space-y-6">
                        <x-galaxy-input label="Name" placeholder="Enter your name" required />
                        <x-galaxy-input type="email" label="Email" placeholder="Enter your email" required />
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Message</label>
                            <textarea class="galaxy-textarea" placeholder="Enter your message" rows="4"></textarea>
                        </div>
                        <div class="flex gap-3">
                            <x-galaxy-button variant="primary" type="submit">Send Message</x-galaxy-button>
                            <x-galaxy-button variant="ghost" type="button">Cancel</x-galaxy-button>
                        </div>
                    </form>
                </x-galaxy-card>
            </div>
        </section>
    </div>
</body>
</html>
