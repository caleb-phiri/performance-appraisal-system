<!-- resources/views/components/signature-pad.blade.php -->
<div class="signature-container">
    <canvas id="signature-pad-{{ $id }}" class="signature-pad" 
            style="border: 1px solid #ccc; width: 100%; height: 200px;"></canvas>
    <div class="mt-2">
        <button type="button" class="btn btn-secondary btn-sm" onclick="clearSignature('{{ $id }}')">
            Clear
        </button>
        <input type="hidden" name="{{ $name }}" id="signature-data-{{ $id }}">
    </div>
</div>

@push('scripts')
<script>
function initSignaturePad(id) {
    const canvas = document.getElementById('signature-pad-' + id);
    const signaturePad = new SignaturePad(canvas);
    
    window['signaturePad_' + id] = signaturePad;
    
    // Update hidden input on end of drawing
    signaturePad.addEventListener('endStroke', () => {
        document.getElementById('signature-data-' + id).value = 
            signaturePad.toDataURL();
    });
}

function clearSignature(id) {
    if (window['signaturePad_' + id]) {
        window['signaturePad_' + id].clear();
        document.getElementById('signature-data-' + id).value = '';
    }
}
</script>
@endpush