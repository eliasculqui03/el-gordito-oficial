<div class="p-4 bg-white rounded-lg">
    <div class="overflow-hidden border rounded-lg">
        <iframe id="pdfPreview" src="data:application/pdf;base64,{{ $base64Pdf }}"
            style="width: 100%; height: 70vh; border: none;" frameborder="0"></iframe>
    </div>
</div>

<script>
    function printPdf() {
        const iframe = document.getElementById('pdfPreview');
        iframe.contentWindow.print();
    }

    function downloadPdf() {
        // Dispara el action de descarga en el footer del modal
        document.querySelector('button[form="preview_pdf"]').click();
    }
</script>
