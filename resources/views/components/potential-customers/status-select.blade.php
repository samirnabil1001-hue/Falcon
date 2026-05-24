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
        
        @if ($currentStatusValue == \App\Enums\PotentialCustomerStatus::NEW->value)
            <option value="{{ \App\Enums\PotentialCustomerStatus::NEW->value }}" selected disabled class="text-gray-400 font-normal">
                {{ \App\Enums\PotentialCustomerStatus::NEW->label() }}
            </option>
            <option value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}">
                {{ \App\Enums\PotentialCustomerStatus::CONTACTED->label() }}
            </option>
        @elseif($currentStatusValue == \App\Enums\PotentialCustomerStatus::CONTACTED->value)
            <option value="{{ \App\Enums\PotentialCustomerStatus::CONTACTED->value }}" selected disabled class="text-gray-400 font-normal">
                {{ \App\Enums\PotentialCustomerStatus::CONTACTED->label() }}
            </option>
            <option value="{{ \App\Enums\PotentialCustomerStatus::CONFIRMED->value }}">
                {{ \App\Enums\PotentialCustomerStatus::CONFIRMED->label() }}
            </option>
            <option value="{{ \App\Enums\PotentialCustomerStatus::CANCELLED->value }}">
                {{ \App\Enums\PotentialCustomerStatus::CANCELLED->label() }}
            </option>
        @else
            <option value="{{ $currentStatusValue }}" selected disabled>
                {{ $statusEnum?->label() ?? $currentStatusValue }}
            </option>
        @endif
    </select>
</form>