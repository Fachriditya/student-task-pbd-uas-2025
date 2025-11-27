<?php

namespace App\Http\Controllers\Transaksi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengadaanController extends Controller
{
    public function index()
    {
        $dataPengadaan = DB::select("SELECT * FROM view_pengadaan");
        
        $data = [
            'title'      => 'Data Pengadaan',
            'pengadaans' => $dataPengadaan
        ];
        
        return view('transaksi.pengadaan.index', $data);
    }

    public function show(string $id)
    {
        $pengadaan = DB::selectOne("SELECT * FROM view_pengadaan WHERE idpengadaan = ?", [$id]);

        $details = DB::select(
            "SELECT 
                dp.*, b.nama as nama_barang 
            FROM 
                detail_pengadaan dp
            JOIN 
                barang b ON dp.idbarang = b.idbarang
            WHERE 
                dp.idpengadaan = ?", 
            [$id]
        );
        
        if (!$pengadaan) {
            abort(404, 'Data Pengadaan tidak ditemukan');
        }

        $data = [
            'title'     => 'Detail Pengadaan #' . $pengadaan->idpengadaan,
            'pengadaan' => $pengadaan,
            'details'   => $details
        ];

        return view('transaksi.pengadaan.show', $data);
    }

    public function create()
    {
        $vendors = DB::select("SELECT idvendor, nama_vendor FROM vendor WHERE status = 'A'");
        $barangs = DB::select("SELECT idbarang, nama, harga FROM barang WHERE status = 1 ORDER BY nama");

        $data = [
            'title'   => 'Buat Pengadaan Baru',
            'vendors' => $vendors,
            'barangs' => $barangs
        ];
        
        return view('transaksi.pengadaan.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer|exists:vendor,idvendor',
            'items'     => 'required|array|min:1',
            'items.*.idbarang'     => 'required|integer|exists:barang,idbarang',
            'items.*.jumlah'       => 'required|integer|min:1',
            'items.*.harga_satuan' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();

            $id_user_login = Auth::id();
            $id_vendor = $request->input('vendor_id');
            $status = 'I';
            $tanggal = now();

            DB::insert(
                "INSERT INTO pengadaan (timestamp, user_iduser, status, vendor_idvendor, subtotal_nilai, ppn, total_nilai) 
                 VALUES (?, ?, ?, ?, 0, 0, 0)", 
                [$tanggal, $id_user_login, $status, $id_vendor]
            );

            $id_pengadaan_baru = DB::getPdo()->lastInsertId();
            $total_subtotal_pengadaan = 0;

            foreach ($request->input('items') as $item) {
                $id_barang = $item['idbarang'];
                $jumlah = $item['jumlah'];
                $harga_satuan = $item['harga_satuan'];

                $result = DB::selectOne(
                    "SELECT func_hitung_subtotal(?, ?) AS item_sub_total", 
                    [$harga_satuan, $jumlah]
                );
                $item_sub_total = $result->item_sub_total;

                DB::insert(
                    "INSERT INTO detail_pengadaan (harga_satuan, jumlah, sub_total, idbarang, idpengadaan) 
                     VALUES (?, ?, ?, ?, ?)",
                    [$harga_satuan, $jumlah, $item_sub_total, $id_barang, $id_pengadaan_baru]
                );

                $total_subtotal_pengadaan += $item_sub_total;
            }

            $result_ppn = DB::selectOne("SELECT func_hitung_ppn(?) AS ppn_value", [$total_subtotal_pengadaan]);
            $ppn_dihitung = $result_ppn->ppn_value;

            $result_total = DB::selectOne("SELECT func_hitung_total_dengan_ppn(?) AS total_value", [$total_subtotal_pengadaan]);
            $total_nilai_pengadaan = $result_total->total_value;

            DB::update(
                "UPDATE pengadaan 
                 SET subtotal_nilai = ?, ppn = ?, total_nilai = ?
                 WHERE idpengadaan = ?",
                [$total_subtotal_pengadaan, $ppn_dihitung, $total_nilai_pengadaan, $id_pengadaan_baru]
            );
            
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                             ->withInput();
        }

        return redirect()->route('transaksi.pengadaan.index')
                         ->with('success', 'Data pengadaan baru (ID: ' . $id_pengadaan_baru . ') berhasil dibuat.');
    }

    public function cancel(string $id)
    {
        try {
            $pengadaan = DB::selectOne("SELECT status FROM pengadaan WHERE idpengadaan = ?", [$id]);

            if (!$pengadaan) {
                return redirect()->back()->with('error', 'Data pengadaan tidak ditemukan.');
            }

            if ($pengadaan->status != 'I') {
                return redirect()->back()->with('error', 'Gagal membatalkan. Hanya pengadaan dengan status "In Process" yang bisa dibatalkan.');
            }

            DB::update("UPDATE pengadaan SET status = 'C' WHERE idpengadaan = ?", [$id]);

            return redirect()->route('transaksi.pengadaan.index')
                             ->with('success', 'Pengadaan (ID: ' . $id . ') berhasil dibatalkan (Cancelled).');

        } catch (\Exception $e) {
            return redirect()->back()
                             ->with('error', 'Terjadi kesalahan saat membatalkan data: ' . $e->getMessage());
        }
    }
}