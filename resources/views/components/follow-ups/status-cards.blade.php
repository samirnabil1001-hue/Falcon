<div class="flex gap-2 mb-2 w-full" dir="rtl">

    {{-- إجمالي الإجراءات --}}
    <div class="flex-1">
        <div class="bg-blue-50 border-r-4 border-blue-500  shadow-sm">

            <div class="py-2 px-3">

                <div class="font-bold text-[12px] text-blue-600 mb-1">
                    إجمالي الإجرائات
                </div>

                <div class="font-bold text-[20px] leading-none text-gray-800">
                    {{ number_format(collect($statusCounts)->where('status', '!==', 'new')->sum('count')) }}
                </div>

            </div>
        </div>
    </div>

    {{-- الحالات --}}
    @foreach ($statusCounts as $item)
        @if ($item['status'] !== 'new')

            <div class="flex-1">

                <div class="
                     shadow-sm border-r-4

                    @if ($item['status'] === 'contacted')
                        bg-orange-50 border-orange-500
                    @elseif($item['status'] === 'cancelled')
                        bg-red-50 border-red-500
                    @elseif($item['status'] === 'confirmed')
                        bg-green-50 border-green-500
                    @else
                        bg-gray-50 border-gray-500
                    @endif
                ">

                    <div class="py-2 px-3">

                        <div class="
                            font-bold text-[12px] mb-1

                            @if ($item['status'] === 'contacted')
                                text-orange-600
                            @elseif($item['status'] === 'cancelled')
                                text-red-600
                            @elseif($item['status'] === 'confirmed')
                                text-green-600
                            @else
                                text-gray-600
                            @endif
                        ">
                            {{ $item['label'] }}
                        </div>

                        <div class="font-bold text-[20px] leading-none text-gray-800">
                            {{ number_format($item['count']) }}
                        </div>

                    </div>

                </div>

            </div>

        @endif
    @endforeach

</div>