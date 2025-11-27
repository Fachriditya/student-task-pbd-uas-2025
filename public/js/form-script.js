document.addEventListener('DOMContentLoaded', () => {

    const itemListContainer = document.getElementById('item-list-container');
    const addItemBtn = document.getElementById('add-item-btn');
    const itemTemplate = document.getElementById('item-row-template');

    if (!itemListContainer || !addItemBtn || !itemTemplate) {
        console.error('Elemen form penting tidak ditemukan!');
        return;
    }

    function formatCurrency(value) {
        if (isNaN(value)) value = 0;
        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
    }

    function parseCurrency(value) {
        return Number(String(value).replace(/[^0-9]/g, ''));
    }

    function updateRowSubtotal(row) {
        const qtyInput = row.querySelector('.item-jumlah');
        const priceInput = row.querySelector('.item-harga');
        const subtotalInput = row.querySelector('.item-subtotal');

        const qty = qtyInput.valueAsNumber || 0;
        const price = priceInput.valueAsNumber || 0;
        const subtotal = qty * price;

        subtotalInput.value = formatCurrency(subtotal);
        updateTotalSummary();
    }

    function updateTotalSummary() {
        const allItemRows = itemListContainer.querySelectorAll('.item-row');
        let totalSubtotal = 0;

        allItemRows.forEach(row => {
            const subtotalInput = row.querySelector('.item-subtotal');
            totalSubtotal += parseCurrency(subtotalInput.value);
        });

        const ppn = Math.ceil(totalSubtotal * 0.10);
        const totalNilai = totalSubtotal + ppn;

        document.getElementById('summary_subtotal_text').textContent = formatCurrency(totalSubtotal);
        document.getElementById('summary_ppn_text').textContent = formatCurrency(ppn);
        document.getElementById('summary_total_text').textContent = formatCurrency(totalNilai);

        document.getElementById('summary_subtotal').value = totalSubtotal;
        document.getElementById('summary_ppn').value = ppn;
        document.getElementById('summary_total').value = totalNilai;
    }

    function addItemRow() {
        const itemIndex = Date.now();
        const newRowTemplate = itemTemplate.content.cloneNode(true);
        const newRow = newRowTemplate.firstElementChild; 

        newRow.querySelector('.item-barang').name = `items[${itemIndex}][idbarang]`;
        newRow.querySelector('.item-jumlah').name = `items[${itemIndex}][jumlah]`;
        newRow.querySelector('.item-harga').name = `items[${itemIndex}][harga_satuan]`;
        newRow.querySelector('.item-subtotal').name = `items[${itemIndex}][sub_total]`;

        itemListContainer.appendChild(newRow);
        newRow.querySelector('.item-barang').focus();
    }

    addItemBtn.addEventListener('click', () => {
        addItemRow();
    });

    itemListContainer.addEventListener('input', (e) => {
        if (e.target.classList.contains('item-jumlah')) {
            const row = e.target.closest('.item-row');
            if (row) {
                updateRowSubtotal(row);
            }
        }
    });

    itemListContainer.addEventListener('change', (e) => {
        if (e.target.classList.contains('item-barang')) {
            const row = e.target.closest('.item-row');
            if (row) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const harga = selectedOption.dataset.harga || 0;
                
                row.querySelector('.item-harga').value = harga;
                updateRowSubtotal(row);
                row.querySelector('.item-jumlah').focus();
            }
        }
    });

    itemListContainer.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.item-remove-btn');
        if (removeBtn) {
            const row = removeBtn.closest('.item-row');
            row.remove();
            updateTotalSummary();
        }
    });

    const existingRows = itemListContainer.querySelectorAll('.item-row');
    existingRows.forEach(row => {
        updateRowSubtotal(row);
    });

    if (existingRows.length === 0) {
        addItemRow();
    } else {
        updateTotalSummary();
    }
});