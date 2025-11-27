document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('item-list-container');
    const addBtn = document.getElementById('add-item-btn');
    const template = document.getElementById('item-row-template');

    function updateRow(row) {
        const select = row.querySelector('.item-barang');
        const jumlahInput = row.querySelector('.item-jumlah');
        const hargaDisplay = row.querySelector('.item-harga-display');
        const hargaInput = row.querySelector('.item-harga');
        const subtotalDisplay = row.querySelector('.item-subtotal');
        const stokInfo = row.querySelector('.stok-info');
        const stokVal = row.querySelector('.stok-val');

        const selectedOption = select.options[select.selectedIndex];
        const harga = parseFloat(selectedOption.dataset.harga) || 0;
        const stok = parseInt(selectedOption.dataset.stok) || 0;
        let jumlah = parseInt(jumlahInput.value) || 0;

        if (stok > 0) {
            jumlahInput.setAttribute('max', stok);
            
            stokInfo.style.display = 'block';
            stokVal.innerText = stok;

            if (jumlah > stok) {
                jumlah = stok;
                jumlahInput.value = stok;
                alert('Jumlah melebihi stok yang tersedia!');
            }
        } else {
            stokInfo.style.display = 'none';
        }

        hargaDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(harga);
        hargaInput.value = harga; 

        const subtotal = harga * jumlah;
        subtotalDisplay.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(subtotal);

        updateTotal();
    }

    function updateTotal() {
        let totalSub = 0;
        
        container.querySelectorAll('.item-row').forEach(row => {
            const select = row.querySelector('.item-barang');
            const jumlah = parseInt(row.querySelector('.item-jumlah').value) || 0;
            const harga = parseFloat(select.options[select.selectedIndex]?.dataset.harga) || 0;
            totalSub += (harga * jumlah);
        });

        const ppn = Math.ceil(totalSub * 0.10);
        const total = totalSub + ppn;

        document.getElementById('summary_subtotal_text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalSub);
        document.getElementById('summary_ppn_text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(ppn);
        document.getElementById('summary_total_text').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-barang')) {
            updateRow(e.target.closest('.item-row'));
        }
    });

    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-jumlah')) {
            updateRow(e.target.closest('.item-row'));
        }
    });

    container.addEventListener('click', function(e) {
        if (e.target.closest('.item-remove-btn')) {
            if (container.querySelectorAll('.item-row').length > 1) {
                e.target.closest('.item-row').remove();
                updateTotal();
            } else {
                alert("Minimal harus ada 1 barang.");
            }
        }
    });

    addBtn.addEventListener('click', function() {
        const index = new Date().getTime();
        const clone = template.content.cloneNode(true);
        const row = clone.querySelector('.item-row');
        
        row.querySelector('.item-barang').name = `items[${index}][idbarang]`;
        row.querySelector('.item-jumlah').name = `items[${index}][jumlah]`;
        
        container.appendChild(row);
    });
    
    container.querySelectorAll('.item-row').forEach(row => {
        updateRow(row);
    });
});