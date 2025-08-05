<!-- Modal Detail Pembelian -->
<div class="modal fade" id="modalDetailPembelian" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="modalDetailLabel">Detail Pembelian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Kode Obat</th>
                        <td id="detail-kode-obat"></td>
                    </tr>
                    <tr>
                        <th>Nama Obat</th>
                        <td id="detail-nama-obat"></td>
                    </tr>
                    <tr>
                        <th>Jumlah Pembelian</th>
                        <td id="detail-jumlah"></td>
                    </tr>
                    <tr>
                        <th>Bulan Peramalan</th>
                        <td id="detail-bulan"></td>
                    </tr>
                    <tr>
                        <th>Hasil Peramalan</th>
                        <td id="detail-hasil"></td>
                    </tr>
                    <tr>
                        <th>Error MAD</th>
                        <td id="detail-mad"></td>
                    </tr>
                    <tr>
                        <th>Error MAPE</th>
                        <td id="detail-mape"></td>
                    </tr>
                    <tr>
                        <th>Kategori MAPE</th>
                        <td id="detail-mape-kategori"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalDetailPembelian');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            const kodeObat = button.getAttribute('data-kode-obat');
            const namaObat = button.getAttribute('data-nama-obat');
            const jumlah = button.getAttribute('data-jumlah');
            const bulan = button.getAttribute('data-bulan');
            const hasil = button.getAttribute('data-hasil');
            const mad = button.getAttribute('data-mad');
            const mape = button.getAttribute('data-mape');

            document.getElementById('detail-kode-obat').textContent = kodeObat;
            document.getElementById('detail-nama-obat').textContent = namaObat;
            document.getElementById('detail-jumlah').textContent = jumlah;
            document.getElementById('detail-bulan').textContent = bulan;
            document.getElementById('detail-hasil').textContent = hasil;
            document.getElementById('detail-mad').textContent = mad;
            document.getElementById('detail-mape').textContent = mape;

            let kategori = '';
            const mapeFloat = parseFloat(mape);
            if (isNaN(mapeFloat)) {
                kategori = 'Tidak Valid';
            } else if (mapeFloat < 10) {
                kategori = 'Sangat Baik';
            } else if (mapeFloat < 20) {
                kategori = 'Baik';
            } else if (mapeFloat < 50) {
                kategori = 'Layak';
            } else {
                kategori = 'Buruk';
            }

            document.getElementById('detail-mape-kategori').textContent = kategori;

        });
    });
</script>