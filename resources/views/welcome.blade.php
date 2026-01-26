<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Smart Multi-Warehouse Storage</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/framer-motion/dist/framer-motion.umd.js"></script>
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="w-full flex justify-between items-center px-10 py-4 bg-white shadow-md fixed top-0 left-0 z-50">
        <h1 class="text-3xl font-extrabold text-blue-700 tracking-wide">Smart Storage</h1>
        <div class="space-x-4">
            <a href="/login" class="px-5 py-2 border border-blue-600 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition font-semibold">Login</a>
            <a href="/register" class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-semibold">Sign Up</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-10 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 fadeIn">
            <h2 class="text-5xl md:text-6xl font-extrabold text-gray-800 leading-tight mb-4">
                Secure, Smart & Modern<br>Warehouse Storage
            </h2>
            <p class="text-gray-600 text-lg leading-relaxed mb-6">
               Manage your storage with smart technology! Multi-location support, smart booking, secure units — all in one powerful platform.
            </p>
            <a href="/register" class="px-8 py-3 bg-blue-600 text-white text-lg rounded-xl shadow-lg hover:bg-blue-700 transition">Get Started</a>
        </div>

        <!-- Hero Image -->
        <div class="flex-1">
            <img 
  src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1200&q=80"
  class="rounded-3xl shadow-xl w-full"
/>

        </div>
    </section>

    <!-- Features Section -->
    <section class="px-10 py-20 bg-white">
        <h2 class="text-4xl font-bold text-center text-gray-800 mb-14">Why Choose Us?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div class="bg-gray-100 rounded-2xl p-8 shadow hover:shadow-xl transition">
                <h3 class="text-2xl font-semibold mb-3">🔐 24/7 Security</h3>
                <p class="text-gray-600">Advanced cameras & secured units ensure maximum protection.</p>
            </div>

            <div class="bg-gray-100 rounded-2xl p-8 shadow hover:shadow-xl transition">
                <h3 class="text-2xl font-semibold mb-3">📦 Multiple Warehouses</h3>
                <p class="text-gray-600">Choose from multiple locations with flexible space options.</p>
            </div>

            <div class="bg-gray-100 rounded-2xl p-8 shadow hover:shadow-xl transition">
                <h3 class="text-2xl font-semibold mb-3">⚡ Smart Online Booking</h3>
                <p class="text-gray-600">Real-time booking & digital access from anywhere.</p>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="px-10 py-20 bg-gray-100">
        <h2 class="text-4xl font-bold text-center mb-12">Warehouse Gallery</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <img src="https://images.unsplash.com/photo-1527430253228-e93688616381" class="rounded-2xl shadow-lg hover:scale-105 transition" />
            <img src="https://images.unsplash.com/photo-1593079831268-3381b0db4a77" class="rounded-2xl shadow-lg hover:scale-105 transition" />
            <img src="https://images.unsplash.com/photo-1593642634315-48f5414c3ad9?w=1200&q=80" 
         class="rounded-2xl shadow-lg hover:scale-105 transition" />
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="px-10 py-20 bg-white">
        <h2 class="text-4xl font-bold text-center mb-12">Affordable Pricing</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 text-center">
            <div class="p-8 bg-gray-100 rounded-2xl shadow hover:shadow-xl">
                <h3 class="text-2xl font-bold">Basic Unit</h3>
                <p class="text-gray-600 mt-2">Small storage</p>
                <p class="text-4xl font-bold mt-4">RS 2500/mo</p>
            </div>
            <div class="p-8 bg-blue-600 text-white rounded-2xl shadow-xl">
                <h3 class="text-2xl font-bold">Standard Unit</h3>
                <p class="mt-2">Medium storage</p>
                <p class="text-4xl font-bold mt-4">RS 4500/mo</p>
            </div>
            <div class="p-8 bg-gray-100 rounded-2xl shadow hover:shadow-xl">
                <h3 class="text-2xl font-bold">Premium Unit</h3>
                <p class="text-gray-600 mt-2">Large storage</p>
                <p class="text-4xl font-bold mt-4">RS 7500/mo</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="px-10 py-20 bg-gray-100">
        <h2 class="text-4xl font-bold text-center mb-10">What Our Clients Say</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <p class="text-gray-600 italic">“Best warehouse platform! Booking bohot easy hai aur units secure hain.”</p>
                <h4 class="mt-4 font-bold">— Ayesha K.</h4>
            </div>
            <div class="bg-white p-8 rounded-2xl shadow-lg">
                <p class="text-gray-600 italic">“Multiple locations se kaafi convenience mili. Highly recommended!”</p>
                <h4 class="mt-4 font-bold">— Ali R.</h4>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6 mt-10">
        <p>© 2025 Smart Storage Platform — All Rights Reserved.</p>
    </footer>

</body>
</html>
