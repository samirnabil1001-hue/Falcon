@php
    use App\Enums\PotentialCustomerStatus;
    $currentStatusValue = $customer->status instanceof \BackedEnum ? $customer->status->value : $customer->status;
    $isLocked = in_array($currentStatusValue, [PotentialCustomerStatus::CONFIRMED->value, PotentialCustomerStatus::CANCELLED->value]);
@endphp

<form id="status-form-{{ $customer->id }}" action="{{ $route }}" method="POST" class="m-0">
    @csrf
    @method('PATCH')
    
    <select name="status" 
        x-on:focus="showCurrentLabel($event)"
        x-on:blur="hideCurrentLabel($event)"
        x-on:change="checkStatus($event)"
        data-original-value="{{ $currentStatusValue }}" 
        dir="rtl"
        {{ $isLocked ? 'disabled' : '' }}
        class="w-full text-xs border border-gray-300 dark:border-slate-600 rounded-lg pl-8 pr-2 py-1 bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 text-right focus:ring-2 focus:ring-indigo-500 appearance-none bg-no-repeat bg-[left_0.5rem_center] disabled:opacity-60 disabled:cursor-not-allowed transition-all"
        style="background-size: 0.65em auto; height: 30px;">
        
        {{-- عرض الحالة الحالية --}}
        <option value="{{ $currentStatusValue }}" selected disabled>
            {{ $customer->status instanceof PotentialCustomerStatus ? $customer->status->label() : $currentStatusValue }}
        </option>

        {{-- المنطق الديناميكي لعرض الخيارات --}}
        @if ($currentStatusValue == PotentialCustomerStatus::NEW->value)
            <option value="{{ PotentialCustomerStatus::CONTACTED->value }}">{{ PotentialCustomerStatus::CONTACTED->label() }}</option>
            <option value="{{ PotentialCustomerStatus::CONFIRMED->value }}">{{ PotentialCustomerStatus::CONFIRMED->label() }}</option>
            <option value="{{ PotentialCustomerStatus::CANCELLED->value }}">{{ PotentialCustomerStatus::CANCELLED->label() }}</option>
            
        @elseif ($currentStatusValue == PotentialCustomerStatus::CONTACTED->value)
            <option value="{{ PotentialCustomerStatus::CONFIRMED->value }}">{{ PotentialCustomerStatus::CONFIRMED->label() }}</option>
            <option value="{{ PotentialCustomerStatus::CANCELLED->value }}">{{ PotentialCustomerStatus::CANCELLED->label() }}</option>
        @endif
        
    </select>
</form>