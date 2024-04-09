@if (session()->has('success'))
    <div> <p class="text-white"> {{ session('success') }} </p></div>
@endif
@livewireStyles
<script src="https://cdn.tailwindcss.com"></script>
<div class="w-full sm:px-6">
    <div class="px-4 py-4 md:px-10 md:py-7">
        <div class="flex items-center justify-between">
            <p tabindex="0" class="text-base font-bold leading-normal text-gray-800 focus:outline-none sm:text-lg md:text-xl lg:text-2xl">{{$merchant->name}} </p>
            <div class="flex items-center text-sm font-medium leading-none text-gray-600 bg-gray-200 rounded cursor-pointer">
                @livewire('Loader')
            </div>
        </div>
    </div>

    {{-- <div class="px-4 py-4 md:px-10 md:py-7">
    </div> --}}
    <div class="px-4 py-4 bg-white md:py-7 md:px-8 xl:px-10">
        <div class="items-center justify-between sm:flex">
            <div class="flex items-center">
                <a href="{{route('store', ['id'=>1,'sortBy' => 'positive'])}}" class="rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800" href=" javascript:void(0)">
                    <div class="px-8 py-2 text-indigo-700 bg-indigo-100 rounded-full">
                        <p>All</p>
                    </div>
                </a>
                <a href="{{route('store', ['id'=>1,'sortBy' => 'positive'])}}" class="ml-4 rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 sm:ml-8">
                    <div class="px-8 py-2 text-gray-600 rounded-full hover:text-indigo-700 hover:bg-indigo-100 ">
                        <p>Positive</p>
                    </div>
                </a>
                <a href="{{route('store', ['id'=>1,'sortBy' => 'negative'])}}" class="ml-4 rounded-full focus:outline-none focus:ring-2 focus:bg-indigo-50 focus:ring-indigo-800 sm:ml-8">
                    <div class="px-8 py-2 text-gray-600 rounded-full hover:text-indigo-700 hover:bg-indigo-100 ">
                        <p>Negative</p>
                    </div>
                </a>
            </div>
            <button class="inline-flex items-start justify-start px-6 py-3 mt-4 bg-indigo-700 rounded pointer-events-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 sm:mt-0 hover:bg-indigo-600 focus:outline-none">
                <p class="text-sm font-medium leading-none text-white">Total products: {{$merchant->total_rows}}</p>
            </button>
        </div>
        <div class="overflow-x-auto mt-7">
            <table class="w-full whitespace-nowrap">
                <tbody>
                    @foreach ($products as $item)
                        @if ($item->default_merchant_uuid == session()->get('merchant_id'))
                            @php $color = 'border-gray-100'; $bg_color = 'bg-slate-50'; @endphp
                        @else
                            @php $color = 'border-red-300'; $bg_color = 'bg-red-50'; @endphp
                        @endif
                        <tr tabindex="0" class="h-16 border {{ $bg_color }} focus:outline-none" >
                        <td>
                            <div class="ml-3">
                                <p class="text-gray-500">{{ $loop->iteration }} : {{ $item->positive }}</p>
                            </div>
                        </td>
                        <td>
                            <div class="ml-5">
                                <div class="relative flex items-center justify-center flex-shrink-0 w-5 h-5 bg-gray-200 rounded-sm">
                                    <input placeholder="checkbox" type="checkbox" class="absolute w-full h-full opacity-0 cursor-pointer focus:opacity-100 checkbox" />
                                    <div class="hidden text-white bg-indigo-700 rounded-sm check-icon">
                                        <svg class="icon icon-tabler icon-tabler-check" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z"></path>
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="">
                            <div class="flex items-center pl-5">
                                <img src="{{$item->img_url_thumbnail}}" width="50" alt="good name">
                                <p class="ml-1 text-sm leading-none text-gray-700">{{$item->name}}</p>
                            </div>
                        </td>
                        <td class="pl-24">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                    <path d="M9.16667 2.5L16.6667 10C17.0911 10.4745 17.0911 11.1922 16.6667 11.6667L11.6667 16.6667C11.1922 17.0911 10.4745 17.0911 10 16.6667L2.5 9.16667V5.83333C2.5 3.99238 3.99238 2.5 5.83333 2.5H9.16667" stroke="#52525B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <circle cx="7.50004" cy="7.49967" r="1.66667" stroke="#52525B" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></circle>
                                </svg>
                                <p class="ml-2 text-sm leading-none text-gray-600">{{ ($item->retail_price) ? $item->retail_price : $item->old_price }} ₼</p>
                                <div class=" flex-column">
                                    @foreach ($offers as $offer)
                                        @if ($item->id == $offer->store_id)
                                            <div class="relative rounded-full column ">
                                                <p class="block ml-2 text-xs leading-none text-gray-400">
                                                    {{ ($offer->retail_price) ? $offer->retail_price : $offer->old_price }} ₼
                                                </p>
                                                <span class="absolute bottom-0 px-1 py-0.5 text-xs text-white transition-opacity duration-300 transform-translate-x-1/2 bg-black opacity-0 pointer-events-none left-1/2 hover:opacity-100">
                                                    {{$offer->name}}
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                            </div>
                        </td>


                        <td class="pl-5">
                            {{-- <button class="px-3 py-3 text-sm leading-none text-red-700 bg-red-100 rounded focus:outline-none">Due today at 18:00</button> --}}
                            <div class="flex" >
                                @foreach ($offers as $offer)
                                    @if ($item->id == $offer->store_id)
                                        <div class="relative inline-block rounded-full">
                                            <img class="w-10 h-10 ml-1 border-red-950" src="{{$offer->logo}}" alt="{{$offer->name}}">
                                            <span class="absolute bottom-0 px-2 py-1 text-xs text-white transition-opacity duration-300 transform -translate-x-1/2 bg-black opacity-0 pointer-events-none left-1/2 hover:opacity-100">
                                                {{$offer->name}}
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        {{-- <td class="pl-4">
                            <button class="px-5 py-3 text-sm leading-none text-gray-600 bg-gray-100 rounded focus:ring-2 focus:ring-offset-2 focus:ring-red-300 hover:bg-gray-200 focus:outline-none">View</button>
                        </td> --}}
                        <td>
                            <div class="relative px-5 pt-2">
                                <button class="rounded-md focus:ring-2 focus:outline-none" onclick="dropdownFunction(this)" role="button" aria-label="option">
                                    <svg class="dropbtn" onclick="dropdownFunction(this)" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M4.16667 10.8332C4.62691 10.8332 5 10.4601 5 9.99984C5 9.5396 4.62691 9.1665 4.16667 9.1665C3.70643 9.1665 3.33334 9.5396 3.33334 9.99984C3.33334 10.4601 3.70643 10.8332 4.16667 10.8332Z" stroke="#9CA3AF" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10 10.8332C10.4602 10.8332 10.8333 10.4601 10.8333 9.99984C10.8333 9.5396 10.4602 9.1665 10 9.1665C9.53976 9.1665 9.16666 9.5396 9.16666 9.99984C9.16666 10.4601 9.53976 10.8332 10 10.8332Z" stroke="#9CA3AF" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M15.8333 10.8332C16.2936 10.8332 16.6667 10.4601 16.6667 9.99984C16.6667 9.5396 16.2936 9.1665 15.8333 9.1665C15.3731 9.1665 15 9.5396 15 9.99984C15 10.4601 15.3731 10.8332 15.8333 10.8332Z" stroke="#9CA3AF" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                                <div class="absolute right-0 z-30 hidden w-24 mr-6 bg-white shadow dropdown-content">
                                    <div tabindex="0" class="w-full px-4 py-4 text-xs cursor-pointer focus:outline-none focus:text-indigo-600 hover:bg-indigo-700 hover:text-white">
                                        <p>Edit</p>
                                    </div>
                                    <div tabindex="0" class="w-full px-4 py-4 text-xs cursor-pointer focus:outline-none focus:text-indigo-600 hover:bg-indigo-700 hover:text-white">
                                        <p>Delete</p>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="h-3"></tr>
                    @endforeach
                </tbody>
            </table>
            <div>
                {{ $products->appends(['sortBy' => $sort])->links() }}
            </div>
        </div>
    </div>
</div>
@livewireScripts
        <style>.checkbox:checked + .check-icon {
  display: flex;
}
.relative:hover .absolute {
    opacity: 1;
    z-index: 9999;
}
</style>
<script>
    function dropdownFunction(element) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            var i;
            let list = element.parentElement.parentElement.getElementsByClassName("dropdown-content")[0];
            list.classList.add("target");
            for (i = 0; i < dropdowns.length; i++) {
                if (!dropdowns[i].classList.contains("target")) {
                    dropdowns[i].classList.add("hidden");
                }
            }
            list.classList.toggle("hidden");
        }
    </script>
