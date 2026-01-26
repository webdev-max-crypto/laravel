<x-app-layout>
    <h2 class="text-2xl font-bold">Welcome {{ ucfirst(Auth::user()->role) }}!</h2>
</x-app-layout>
<x-app-layout>
    <div class="p-6 text-3xl font-bold">

        @if(Auth::user()->role === 'admin')
            Welcome Admin!
        @elseif(Auth::user()->role === 'owner')
            Welcome Owner!
        @else
            Welcome Customer!
        @endif

    </div>
</x-app-layout>
