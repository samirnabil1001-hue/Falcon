<!-- Google Angular Style Notification with Progress Bar -->
<div x-data="{
        show: false,
        message: '',
        title: '',
        type: 'error',
        progress: 100,
        timer: null,
        startTimer() {
            this.progress = 100;
            const duration = 5000; // 5 ثواني
            const interval = 50; // تحديث كل 50 ملي ثانية
            const step = (interval / duration) * 100;
    
            if (this.timer) clearInterval(this.timer);
    
            this.timer = setInterval(() => {
                this.progress -= step;
                if (this.progress <= 0) {
                    this.show = false;
                    clearInterval(this.timer);
                }
            }, interval);
        }
    }" 
    x-init="
        @if(session()->has('error'))
            title = 'Error';
            message = '{{ session('error') }}';
            type = 'error';
            show = true;
            startTimer();
        @endif
        @if(session()->has('success'))
            title = 'Success';
            message = '{{ session('success') }}';
            type = 'success';
            show = true;
            startTimer();
        @endif
        @if(session()->has('warning'))
            title = 'Warning';
            message = '{{ session('warning') }}';
            type = 'warning';
            show = true;
            startTimer();
        @endif
    " 
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-10"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-10"
    class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] w-full max-w-[380px] px-4" 
    style="display: none;">

    <div class="relative bg-white border border-gray-100 shadow-2xl rounded-lg overflow-hidden flex items-start gap-4 p-4 shadow-[0_10px_30px_rgba(0,0,0,0.08)]"
        :class="{
            'border-s-[6px] border-s-red-600': type === 'error',
            'border-s-[6px] border-s-emerald-600': type === 'success',
            'border-s-[6px] border-s-orange-500': type === 'warning'
        }">

        <!-- Icon -->
        <div class="flex-shrink-0">
            <template x-if="type === 'error'">
                <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </template>
            <template x-if="type === 'success'">
                <svg class="w-6 h-6 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </template>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <h3 class="text-[14px] font-bold uppercase tracking-wide mb-1"
                :class="{
                    'text-red-600': type === 'error',
                    'text-emerald-600': type === 'success',
                    'text-orange-500': type === 'warning'
                }"
                x-text="title"></h3>
            <p class="text-[12px] text-gray-500 font-medium leading-tight" x-text="message"></p>
        </div>

        <!-- Close -->
        <button @click="show = false" class="text-gray-300 hover:text-gray-500 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 h-[3px] transition-all duration-75 ease-linear"
            :class="{
                'bg-red-600/30': type === 'error',
                'bg-emerald-600/30': type === 'success',
                'bg-orange-500/30': type === 'warning'
            }"
            :style="`width: ${progress}%; text-align: left;`">
        </div>
    </div>
</div>