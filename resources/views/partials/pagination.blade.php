@if($kosList->hasPages())
    <div class="flex justify-center mt-6">
        {{ $kosList->links() }}
    </div>
@endif

