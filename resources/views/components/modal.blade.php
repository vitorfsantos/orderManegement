@props(['id', 'title', 'size' => 'lg'])

@php
$sizeClasses = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg',
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    '2xl' => 'max-w-6xl',
];
@endphp

<!-- Modal Backdrop -->
<div id="{{ $id }}-backdrop" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" onclick="closeModal('{{ $id }}')">
    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full {{ $sizeClasses[$size] }} max-h-[90vh] overflow-hidden" onclick="event.stopPropagation()">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">{{ $title }}</h3>
                <button type="button" onclick="closeModal('{{ $id }}')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6 overflow-y-auto max-h-[calc(90vh-140px)]">
                {{ $slot }}
            </div>
            
            <!-- Modal Footer (if provided) -->
            @if(isset($footer))
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200 bg-gray-50">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    const modal = document.getElementById(modalId + '-backdrop');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId + '-backdrop');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const openModal = document.querySelector('.fixed.inset-0.bg-black.bg-opacity-50:not(.hidden)');
        if (openModal) {
            const modalId = openModal.id.replace('-backdrop', '');
            closeModal(modalId);
        }
    }
});
</script>
