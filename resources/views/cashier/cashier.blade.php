    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FreshFood POS</title>
        @vite('resources/css/app.css')
        @vite('resources/js/app.js')
    </head>

    <body class="bg-gray-100">
        <header class="bg-white shadow-sm p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">FreshFood</h1>
        </header>

        <main class="grid grid-cols-3 gap-6 p-6">

            <div class="col-span-2 flex flex-col gap-6">
                <div class="bg-white p-6 rounded-lg shadow">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                        <input
                            type="text"
                            placeholder="Search or scan a product..."
                            class="w-full text-2xl font-bold text-gray-800 border-0 border-b-2 border-gray-200 focus:ring-0 focus:border-blue-500 pl-14 py-2"
                        >
                    </div>
                </div>
                {{-- <x-cart-list /> --}}
                <div class="bg-white rounded-lg shadow flex-grow">
                    <div class="grid grid-cols-5 gap-4 p-4 border-b bg-blue-900 font-bold text-white uppercase text-sm">
                        <div class="col-span-2">In Cart</div>
                        <div class="text-right ">Price</div>
                        <div class="text-right ">Quantity</div>
                        <div class="text-right pr-4">Total</div>
                    </div>
                    <div class="overflow-y-auto max-h-[40vh]">
                        <p class="p-4">Cart items will be listed here...</p>
                    </div>
                </div>

                {{-- <x-order-summary /> --}}
                <div class="bg-blue-900 text-white p-6 rounded-lg shadow">
                    <p>Order Summary UI...</p>
                </div>
            </div>

            {{-- <x-actions-panel /> --}}
            <div class="col-span-1 bg-white p-6 rounded-lg shadow flex flex-col justify-between">
                <div>
                    <div class="mb-6">
                        <img src="https://placehold.co/300x200/e0e7ff/6366f1?text=Scanner" alt="Scanner"
                            class="mx-auto rounded-lg">
                    </div>
                    <div class="space-y-4">
                        <button
                            class="w-full text-left bg-gray-100 p-4 rounded-lg font-semibold flex items-center text-gray-700 hover:bg-gray-200">Search
                            in catalog</button>
                        <button
                            class="w-full text-left bg-gray-100 p-4 rounded-lg font-semibold flex items-center text-gray-700 hover:bg-gray-200">Barcode
                            search</button>
                        <button
                            class="w-full text-left bg-gray-100 p-4 rounded-lg font-semibold flex items-center text-gray-700 hover:bg-gray-200">Add
                            Packages</button>
                        <button class="w-full bg-blue-600 text-white text-2xl font-bold py-6 rounded-lg hover:bg-blue-700">
                            Pay </button>
                    </div>
                </div>
            </div>

        </main>
    </body>

    </html>
