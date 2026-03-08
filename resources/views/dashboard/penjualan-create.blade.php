@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto py-6 md:py-10">
    
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight">Kasir Penjualan</h1>
            <p class="text-slate-500 mt-1 text-sm">Catat transaksi keluar. Stok akan otomatis terpotong dari gudang utama.</p>
        </div>
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest bg-white/50 px-3 py-1.5 rounded-full border border-slate-100">
            Admin Mode
        </span>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-100 text-green-600 px-6 py-4 rounded-2xl flex items-center shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold text-sm">{{ session('success') }}</span>
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-100 text-red-600 px-6 py-4 rounded-2xl shadow-sm">
        <ul class="list-disc list-inside text-sm font-bold">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('penjualan.store') }}" method="POST" id="form-penjualan">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-8">
                    <h3 class="font-bold text-slate-800 mb-6 flex items-center uppercase tracking-widest text-xs">
                        <span class="h-2 w-2 bg-blue-600 rounded-full mr-3"></span> Info Nota
                    </h3>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Tanggal Transaksi</label>
                            <input type="date" name="tanggal_transaksi" value="{{ date('Y-m-d') }}" required
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Nama Pembeli (Opsional)</label>
                            <input type="text" name="nama_pembeli" placeholder="Contoh: Pak Budi"
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase">Catatan</label>
                            <textarea name="keterangan" rows="2" placeholder="Catatan tambahan..."
                                class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none font-medium text-slate-700"></textarea>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900 rounded-[2rem] shadow-xl shadow-slate-900/30 border border-slate-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.97-1.31-2.94-3.04-3.32V6h-1.3v1.39c-1.57.32-2.81 1.4-2.81 2.97 0 1.9 1.48 2.62 3.51 3.1 1.95.46 2.45 1.15 2.45 1.94 0 .9-.88 1.57-2.27 1.57-1.51 0-2.31-.79-2.39-1.89h-1.74c.09 2 1.44 3.06 3.48 3.42V20h1.3v-1.41c1.84-.33 3-1.48 3-3.14 0-2.2-1.71-2.85-3.6-3.31z"/></svg>
                    </div>
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-1">Total Tagihan</p>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tighter" id="grand-total-display">Rp 0</h2>
                    
                    <button type="submit" class="w-full mt-8 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-xl transition-all flex items-center justify-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <span>Simpan Transaksi</span>
                    </button>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-200/20 border border-white p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold text-slate-800 flex items-center uppercase tracking-widest text-xs">
                            <span class="h-2 w-2 bg-green-500 rounded-full mr-3"></span> Rincian Barang
                        </h3>
                        <button type="button" id="btn-add-item" class="text-xs font-bold bg-blue-50 text-blue-600 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Tambah Barang
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <div class="item-row bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-end relative group">
                            
                            <button type="button" class="btn-remove-item absolute -top-2 -right-2 bg-red-100 text-red-600 hover:bg-red-500 hover:text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-all shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>

                            <div class="w-full md:w-2/5">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Produk</label>
                                <select name="items[0][barang_id]" required class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-blue-500">
                                    <option value="" disabled selected>Pilih Barang...</option>
                                    @foreach($barangs as $item)
                                        <option value="{{ $item->id }}">[{{ $item->sku }}] {{ $item->nama_barang }} (Sisa: {{ $item->stok_ekor > 0 ? $item->stok_ekor.' ekor' : $item->stok_berat.' kg' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-full md:w-1/6">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Qty (Ekor)</label>
                                <input type="number" name="items[0][jumlah_unit]" step="0.01" placeholder="0" class="input-qty w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-center">
                            </div>

                            <div class="w-full md:w-1/6">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Qty (Kg)</label>
                                <input type="number" name="items[0][jumlah_berat]" step="0.01" placeholder="0" class="input-qty w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-center text-blue-600">
                            </div>

                            <div class="w-full md:w-1/5">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Harga/Satuan</label>
                                <input type="number" name="items[0][harga_satuan]" required placeholder="Rp" class="input-harga w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-right">
                            </div>

                            <div class="w-full md:w-1/5">
                                <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider text-right">Subtotal</label>
                                <input type="text" readonly class="input-subtotal w-full px-3 py-2.5 bg-transparent border-none text-sm font-extrabold text-slate-800 text-right" value="Rp 0">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = 1; // Mulai dari 1 karena baris pertama index 0
        const container = document.getElementById('items-container');
        const btnAdd = document.getElementById('btn-add-item');
        const grandTotalDisplay = document.getElementById('grand-total-display');

        // Opsi Produk (diambil dari backend untuk di-copy ke baris baru)
        const productOptions = `
            <option value="" disabled selected>Pilih Barang...</option>
            @foreach($barangs as $item)
                <option value="{{ $item->id }}">[{{ $item->sku }}] {{ $item->nama_barang }} (Sisa: {{ $item->stok_ekor > 0 ? $item->stok_ekor.' ekor' : $item->stok_berat.' kg' }})</option>
            @endforeach
        `;

        // Format Rupiah
        const formatRupiah = (angka) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
        };

        // Fungsi Hitung Total
        const calculateTotal = () => {
            let grandTotal = 0;
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const qtyUnit = parseFloat(row.querySelector('input[name*="[jumlah_unit]"]').value) || 0;
                const qtyBerat = parseFloat(row.querySelector('input[name*="[jumlah_berat]"]').value) || 0;
                const harga = parseFloat(row.querySelector('input[name*="[harga_satuan]"]').value) || 0;
                
                // Prioritaskan hitungan berdasar berat(kg), jika kosong baru pakai unit(ekor)
                const activeQty = qtyBerat > 0 ? qtyBerat : qtyUnit;
                const subtotal = activeQty * harga;
                
                // Update Subtotal di baris tersebut
                row.querySelector('.input-subtotal').value = formatRupiah(subtotal);
                grandTotal += subtotal;
            });

            // Update Grand Total di layar hitam
            grandTotalDisplay.innerText = formatRupiah(grandTotal);
        };

        // Trigger hitung total saat ngetik (Event Delegation)
        container.addEventListener('input', function(e) {
            if(e.target.classList.contains('input-qty') || e.target.classList.contains('input-harga')) {
                calculateTotal();
            }
        });

        // Hapus Baris
        container.addEventListener('click', function(e) {
            if(e.target.closest('.btn-remove-item')) {
                const rows = document.querySelectorAll('.item-row');
                if(rows.length > 1) { // Jangan hapus kalau sisa 1 baris
                    e.target.closest('.item-row').remove();
                    calculateTotal();
                } else {
                    alert('Minimal harus ada 1 barang dalam transaksi!');
                }
            }
        });

        // Tambah Baris Baru
        btnAdd.addEventListener('click', function() {
            const newRow = document.createElement('div');
            newRow.className = 'item-row bg-slate-50 border border-slate-100 rounded-2xl p-4 flex flex-col md:flex-row gap-4 items-end relative group mt-4 hover:border-blue-200 transition-colors';
            
            newRow.innerHTML = `
                <button type="button" class="btn-remove-item absolute -top-2 -right-2 bg-red-100 text-red-600 hover:bg-red-500 hover:text-white rounded-full p-1.5 opacity-0 group-hover:opacity-100 transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <div class="w-full md:w-2/5">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Produk</label>
                    <select name="items[${itemIndex}][barang_id]" required class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold outline-none focus:border-blue-500">
                        ${productOptions}
                    </select>
                </div>
                <div class="w-full md:w-1/6">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Qty (Ekor)</label>
                    <input type="number" name="items[${itemIndex}][jumlah_unit]" step="0.01" placeholder="0" class="input-qty w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-center">
                </div>
                <div class="w-full md:w-1/6">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Qty (Kg)</label>
                    <input type="number" name="items[${itemIndex}][jumlah_berat]" step="0.01" placeholder="0" class="input-qty w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-center text-blue-600">
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider">Harga/Satuan</label>
                    <input type="number" name="items[${itemIndex}][harga_satuan]" required placeholder="Rp" class="input-harga w-full px-3 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold outline-none focus:border-blue-500 text-right">
                </div>
                <div class="w-full md:w-1/5">
                    <label class="block text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-wider text-right">Subtotal</label>
                    <input type="text" readonly class="input-subtotal w-full px-3 py-2.5 bg-transparent border-none text-sm font-extrabold text-slate-800 text-right" value="Rp 0">
                </div>
            `;
            
            container.appendChild(newRow);
            itemIndex++;
        });
    });
</script>
@endsection