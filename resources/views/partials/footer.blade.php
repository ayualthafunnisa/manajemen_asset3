<footer class="bg-white border-t border-neutral-200 mt-auto">
    <div class="px-4 md:px-6 py-4">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <!-- Copyright -->
            <div class="mb-4 md:mb-0">
                <p class="text-sm text-neutral-600">
                    © {{ date('Y') }} Jobie Dashboard. All rights reserved.
                </p>
                <p class="text-xs text-neutral-500 mt-1">
                    v{{ config('app.version', '1.0.0') }}
                </p>
            </div>
        </div>
    </div>
</footer>