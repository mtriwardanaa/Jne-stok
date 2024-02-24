<?php

namespace Modules\Report\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\Divisi;
use App\AgenKategori;
use App\BarangKeluar;
use App\Barang;
use App\BarangHarga;
use App\User;

use Excel;
use Auth;
use PDF;

use App\Exports\ReportExport;
use App\Lib\MyHelper;

class ReportController extends Controller
{
    public function index()
    {
        $divisi = Divisi::get()->toArray();
        if (empty($divisi)) {
            return redirect('divisi')->withErrors(['Divisi masih kosong, silahkan input divisi terlebih dahulu']);
        }

        $barang = Barang::get()->toArray();
        if (empty($barang)) {
            return redirect('barang')->withErrors(['Barang masih kosong, silahkan input barang terlebih dahulu']);
        }

        $kategori = AgenKategori::get()->toArray();
        if (empty($kategori)) {
            return redirect('kategori')->withErrors(['Agen Kategori masih kosong, silahkan input kategori terlebih dahulu']);
        }

        $user = User::where('id_divisi', 13)->get()->toArray();

        $bulan = date('m');
        $tahun = date('Y');

        return view('report::index', ['divisi' => $divisi, 'user' => $user, 'bulan' => $bulan, 'tahun' => $tahun, 'kategori' => $kategori, 'barang' => $barang]);
    }

    public function print(Request $request)
    {
        $post = $request->except('_token');
        // return $post;

        $title = 'SEMUA DIVISI';
        $mode = 'VIEW';

        if (isset($post['view'])) {
            $mode = $post['view'];
        }

        $tanggal_mulai = date('Y-m-d 00:00:00', strtotime($post['tanggal_mulai']));
        $tanggal_selesai = date('Y-m-d 23:59:59', strtotime($post['tanggal_selesai']));

        if ($post['id_divisi'] != 'all') {
            $check_divisi = Divisi::where('id', $post['id_divisi'])->first();
            if (empty($check_divisi)) {
                return back()->withErrors(['Divisi tidak ditemukan']);
            }

            $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_divisi->nama);

            if (isset($post['id_kategori'])) {
                $check_kategori = AgenKategori::where('id', $post['id_kategori'])->first();
                if (empty($check_kategori)) {
                    return back()->withErrors(['Sub Agen tidak ditemukan']);
                }

                $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_divisi->nama . ' (' . $check_kategori->nama . ')');
            }

            if (isset($post['id_agen'])) {
                if ($post['id_agen'] != 'pontianak' && $post['id_agen'] != 'non-pontianak') {
                    $check_agen = User::where('id', $post['id_agen'])->first();
                    if (empty($check_agen)) {
                        return back()->withErrors(['Sub Agen tidak ditemukan']);
                    }

                    $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_agen->nama);
                } else {
                    $title = 'SEMUA AGEN ' . strtoupper(str_replace('-', ' ', $post['id_agen']));
                }
            }

            $barang_keluar = BarangKeluar::with('detailStok.stokBarang.stokBarangSatuan', 'user', 'order')->where('id_divisi', $post['id_divisi'])->where('tanggal', '>=', $tanggal_mulai)->where('tanggal', '<=', $tanggal_selesai);
        } else {
            $barang_keluar = BarangKeluar::with('detailStok.stokBarang.stokBarangSatuan', 'user', 'order')->where('tanggal', '>=', $tanggal_mulai)->where('tanggal', '<=', $tanggal_selesai);
        }

        if (isset($post['id_kategori'])) {
            $barang_keluar = $barang_keluar->where('id_kategori', $post['id_kategori']);
        }

        if (isset($post['id_agen'])) {
            $all_id_agen = [];

            if ($post['id_agen'] != 'pontianak' && $post['id_agen'] != 'non-pontianak') {
                $get_all_agen = User::where('nama', $check_agen->nama)->get()->toArray();
            } else {
                if ($post['id_agen'] == 'pontianak') {
                    $get_all_agen = User::where('id_divisi', 13)->where(function ($query) {
                        $query->whereNull('id_agen_kategori')->orWhere('id_agen_kategori', 17);
                    })->get()->toArray();
                } else {
                    $get_all_agen = User::where('id_divisi', 13)->whereNotNull('id_agen_kategori')->where('id_agen_kategori', '!=', 17)->get()->toArray();
                }
            }

            if (!empty($get_all_agen)) {
                $all_id_agen = array_column($get_all_agen, 'id');
            }

            $barang_keluar = $barang_keluar->whereIn('id_agen', $all_id_agen);
        }

        if ($post['id_barang'] != 'all') {
            $barang_keluar = $barang_keluar->whereHas('detailStok', function ($query) use ($post) {
                return $query->where('id_barang', '=', $post['id_barang']);
            });
        }

        $barang_keluar = $barang_keluar->get()->toArray();
        // return $barang_keluar;
        $data_print = [];
        $harga_total = 0;
        $total_pcs = 0;
        $total_barang = 0;
        $total_stok = 0;
        $data_barang = [];

        $title_barang = 'SEMUA BARANG';
        $pcs_item = 'ITEM';

        if ($post['id_barang'] != 'all') {
            $barang = Barang::with('stokBarangSatuan')->where('id', $post['id_barang'])->first();
            if (empty($barang)) {
                return back()->withErrors(['Barang tidak ditemukan']);
            }

            $pcs_item = $barang->stokBarangSatuan->nama_satuan;
            $title_barang = strtoupper(str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $barang->nama_barang));
        }

        $data_report = array_map(function ($arr) use (&$data_print, &$harga_total, &$total_pcs, &$total_barang, &$total_stok, &$data_barang, $title, $post) {
            if ($title == 'SEMUA DIVISI' || $title == 'SEMUA AGEN PONTIANAK' || $title == 'SEMUA AGEN NON PONTIANAK') {
                $check_divisi = Divisi::where('id', $arr['id_divisi'])->first();
                if (empty($check_divisi)) {
                    return back()->withErrors(['Divisi tidak ditemukan']);
                }

                $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_divisi->nama);

                if (isset($arr['id_kategori'])) {
                    $check_kategori = AgenKategori::where('id', $arr['id_kategori'])->first();
                    if (empty($check_kategori)) {
                        return back()->withErrors(['Sub Agen tidak ditemukan']);
                    }

                    $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_divisi->nama . ' (' . $check_kategori->nama . ')');
                }

                if (isset($arr['id_agen'])) {
                    $check_agen = User::where('id', $arr['id_agen'])->first();
                    if (empty($check_agen)) {
                        return back()->withErrors(['Sub Agen tidak ditemukan']);
                    }

                    $title = str_replace(array('\'', '"', ',', ';', '<', '>', '/'), ' ', $check_agen->nama);
                }
            }

            $tanggal_order = '-';
            $tanggal_diterima = '-';

            if (isset($arr['order']['tanggal_approve'])) {
                $tanggal_diterima = date('d-m-Y H:i', strtotime($arr['order']['tanggal_approve']));
            }

            if (isset($arr['order']['tanggal'])) {
                $tanggal_order = date('d-m-Y H:i', strtotime($arr['order']['tanggal']));
            }

            $stok_data_print = [
                'order_tanggal' => $tanggal_order,
                'tanggal'       => date('d-m-Y H:i', strtotime($arr['tanggal'])),
                'no_transaksi'  => $arr['no_barang_keluar'],
                'request_by'    => strtoupper($arr['nama_user_request']),
                'divisi'        => $title,
                'proses_by'     => strtoupper($arr['user']['nama']),
                'kode_barang'   => $arr['detail_stok'][0]['stok_barang']['kode_barang'],
                'nama_barang'   => $arr['detail_stok'][0]['stok_barang']['nama_barang'],
                'jumlah_barang' => number_format($arr['detail_stok'][0]['qty_barang']) . " " . $arr['detail_stok'][0]['stok_barang']['stok_barang_satuan']['nama_satuan'],
                'harga_satuan'  => $arr['detail_stok'][0]['harga_barang'],
                'harga_total'   => ($arr['detail_stok'][0]['qty_barang'] * $arr['detail_stok'][0]['harga_barang']),
            ];

            // return $stok_data_print;

            $stok_data_print['success'] = 'YA';
            $stok_data_print['alasan'] = '-';

            if (isset($arr['order']['tanggal_approve'])) {
                if (date('Y-m-d H:i:s', strtotime($arr['order']['tanggal_approve'])) > date('Y-m-d H:i:s', strtotime("+2 days", strtotime($arr['order']['tanggal'])))) {
                    $stok_data_print['success'] = 'TIDAK';
                    if (isset($arr['order']['alasan'])) {
                        $stok_data_print['alasan'] = $arr['order']['alasan'];
                    }
                }
            }

            $data_first = false;
            $set_data_first = [];

            if ($post['id_barang'] != 'all') {
                if ($post['id_barang'] == $arr['detail_stok'][0]['id_barang']) {
                    $data_print[] = $stok_data_print;

                    $total_pcs = $total_pcs + ($arr['detail_stok'][0]['qty_barang']);
                    $harga_total = $harga_total + ($arr['detail_stok'][0]['qty_barang'] * $arr['detail_stok'][0]['harga_barang']);
                    $total_stok = $total_stok + $arr['detail_stok'][0]['qty_barang'];

                    if (!in_array($arr['detail_stok'][0]['id_barang'], $data_barang)) {
                        $total_barang++;
                        $data_barang[] = $arr['detail_stok'][0]['id_barang'];
                    }

                    $set_data_first = [
                        'order_tanggal' => $tanggal_order,
                        'tanggal'       => date('d-m-Y H:i', strtotime($arr['tanggal'])),
                        'no_transaksi'  => $arr['no_barang_keluar'],
                        'request_by'    => strtoupper($arr['nama_user_request']),
                        'divisi'        => $title,
                        'proses_by'     => strtoupper($arr['user']['nama']),
                    ];

                } else {
                    $data_first = true;
                    $set_data_first = [
                        'order_tanggal' => $tanggal_order,
                        'tanggal'       => date('d-m-Y H:i', strtotime($arr['tanggal'])),
                        'no_transaksi'  => $arr['no_barang_keluar'],
                        'request_by'    => strtoupper($arr['nama_user_request']),
                        'divisi'        => $title,
                        'proses_by'     => strtoupper($arr['user']['nama']),
                    ];
                }
            } else {
                $data_print[] = $stok_data_print;

                $total_pcs = $total_pcs + ($arr['detail_stok'][0]['qty_barang']);
                $harga_total = $harga_total + ($arr['detail_stok'][0]['qty_barang'] * $arr['detail_stok'][0]['harga_barang']);
                $total_stok = $total_stok + $arr['detail_stok'][0]['qty_barang'];

                if (!in_array($arr['detail_stok'][0]['id_barang'], $data_barang)) {
                    $total_barang++;
                    $data_barang[] = $arr['detail_stok'][0]['id_barang'];
                }
            }

            if (isset($arr['detail_stok'])) {
                if (count($arr['detail_stok']) > 1) {
                    foreach ($arr['detail_stok'] as $key => $value) {
                        if ($key > 0) {
                            if ($data_first) {
                                $data_print_set = [
                                    'order_tanggal' => $set_data_first['order_tanggal'],
                                    'tanggal'       => $set_data_first['tanggal'],
                                    'no_transaksi'  => $set_data_first['no_transaksi'],
                                    'request_by'    => $set_data_first['request_by'],
                                    'divisi'        => $set_data_first['divisi'],
                                    'proses_by'     => $set_data_first['proses_by'],
                                    'kode_barang'   => $value['stok_barang']['kode_barang'],
                                    'nama_barang'   => $value['stok_barang']['nama_barang'],
                                    'jumlah_barang' => number_format($value['qty_barang']) . " " . $value['stok_barang']['stok_barang_satuan']['nama_satuan'],
                                    'harga_satuan'  => $value['harga_barang'],
                                    'harga_total'   => ($value['qty_barang'] * $value['harga_barang']),
                                    'success'       => $stok_data_print['success'],
                                    'alasan'        => $stok_data_print['alasan'],
                                ];
                            } else {
                                if ($post['id_barang'] != 'all') {
                                    $data_print_set = [
                                        'order_tanggal' => $set_data_first['order_tanggal'],
                                        'tanggal'       => $set_data_first['tanggal'],
                                        'no_transaksi'  => $set_data_first['no_transaksi'],
                                        'request_by'    => $set_data_first['request_by'],
                                        'divisi'        => $set_data_first['divisi'],
                                        'proses_by'     => $set_data_first['proses_by'],
                                        'kode_barang'   => $value['stok_barang']['kode_barang'],
                                        'nama_barang'   => $value['stok_barang']['nama_barang'],
                                        'jumlah_barang' => number_format($value['qty_barang']) . " " . $value['stok_barang']['stok_barang_satuan']['nama_satuan'],
                                        'harga_satuan'  => $value['harga_barang'],
                                        'harga_total'   => ($value['qty_barang'] * $value['harga_barang']),
                                        'success'       => $stok_data_print['success'],
                                        'alasan'        => $stok_data_print['alasan'],
                                    ];
                                } else {
                                    $data_print_set = [
                                        'order_tanggal' => $stok_data_print['order_tanggal'],
                                        'tanggal'       => $stok_data_print['tanggal'],
                                        'no_transaksi'  => $stok_data_print['no_transaksi'],
                                        'request_by'    => $stok_data_print['request_by'],
                                        'divisi'        => $stok_data_print['divisi'],
                                        'proses_by'     => $stok_data_print['proses_by'],
                                        'kode_barang'   => $value['stok_barang']['kode_barang'],
                                        'nama_barang'   => $value['stok_barang']['nama_barang'],
                                        'jumlah_barang' => number_format($value['qty_barang']) . " " . $value['stok_barang']['stok_barang_satuan']['nama_satuan'],
                                        'harga_satuan'  => $value['harga_barang'],
                                        'harga_total'   => ($value['qty_barang'] * $value['harga_barang']),
                                        'success'       => $stok_data_print['success'],
                                        'alasan'        => $stok_data_print['alasan'],
                                    ];
                                }
                            }

                            if ($post['id_barang'] != 'all') {
                                if ($post['id_barang'] == $value['id_barang']) {
                                    $data_print[] = $data_print_set;
                                    $total_pcs = $total_pcs + ($value['qty_barang']);
                                    $harga_total = $harga_total + ($value['qty_barang'] * $value['harga_barang']);
                                    $total_stok = $total_stok + $value['qty_barang'];

                                    if (!in_array($value['id_barang'], $data_barang)) {
                                        $total_barang++;
                                        $data_barang[] = $value['id_barang'];
                                    }
                                }
                            } else {
                                $data_print[] = $data_print_set;
                                $total_pcs = $total_pcs + ($value['qty_barang']);
                                $harga_total = $harga_total + ($value['qty_barang'] * $value['harga_barang']);
                                $total_stok = $total_stok + $value['qty_barang'];

                                if (!in_array($value['id_barang'], $data_barang)) {
                                    $total_barang++;
                                    $data_barang[] = $value['id_barang'];
                                }
                            }
                        }
                    }
                }
            }

        }, $barang_keluar);

        $data_print[] = [
            'order_tanggal' => '',
            'tanggal'       => '',
            'no_transaksi'  => '',
            'request_by'    => '',
            'divisi'        => '',
            'proses_by'     => '',
            'kode_barang'   => '',
            'nama_barang'   => 'Total Barang',
            'jumlah_barang' => number_format($total_pcs) . ' ' . $pcs_item,
            'harga_satuan'  => 'Total Harga',
            'harga_total'   => $harga_total,
            'success'       => '',
            'alasan'        => '',
        ];

        $nama = 'REPORT-' . strtoupper($title) . '-' . $title_barang . '-' . date('d-m-Y', strtotime($tanggal_mulai)) . '-' . date('d-m-Y', strtotime($tanggal_selesai));
        $data = json_decode(json_encode($data_print, JSON_NUMERIC_CHECK), true);
        // return $data;

        $post['periode_mulai'] = date('d-m-Y', strtotime($post['tanggal_mulai']));
        $post['periode_selesai'] = date('d-m-Y', strtotime($post['tanggal_selesai']));
        $post['pcs_item'] = $pcs_item;
        $post['title'] = $title;
        $post['title_barang'] = $title_barang;
        $post['total'] = count($data);
        // return $post;

        if ($mode == 'VIEW') {
            return view('report::view', ['data' => $data, 'post' => $post]);
        }

        $heading = $this->heading();
        return Excel::download(new ReportExport($data, $heading, $title, 0), $nama . '.xlsx');
    }

    public function printAll(Request $request)
    {
        $post = $request->except('_token');
        return $post;
    }

    public function heading()
    {
        return [
            'TANGGAL ORDER',
            'TANGGAL KELUAR',
            'NO TRX',
            'REQUEST BY',
            'DIVISI',
            'PROSES BY',
            'KODE BARANG',
            'NAMA BARANG',
            'JUMLAH BARANG',
            'HARGA SATUAN',
            'HARGA TOTAL',
            'SUKSES',
            'ALASAN',
        ];
    }

    public function stok(Request $request)
    {
        $post = $request->except('_token');

        $stock_no = $post['bulan'] . '/SO/V/' . $post['tahun'];
        $group = 'ALL';
        $periode_mulai = date($post['tahun'] . '-' . $post['bulan'] . '-01');
        $periode_mulai = date('Y-m-d', strtotime($periode_mulai));
        $periode_akhir = date('Y-m-t', strtotime($periode_mulai));
        $opname_no = 'F/PMD/' . date('m-d', strtotime($periode_mulai)) . '/00';

        $text_periode_awal = MyHelper::indonesian_date($periode_mulai, 'd F Y');
        $text_periode_akhir = MyHelper::indonesian_date($periode_akhir, 'd F Y');

        $report = [];
        $barang = Barang::get()->toArray();
        if (!empty($barang)) {
            $sort = array_column($barang, 'nama_barang');

            array_multisort($sort, SORT_ASC, $barang);

            $report = array_map(function ($arr) use ($periode_mulai, $periode_akhir) {
                $data = [
                    'kode_barang' => $arr['kode_barang'],
                    'nama_barang' => $arr['nama_barang'],
                ];

                //jumlah sebelumnya
                $total_masuk_awal = 0;
                $total_keluar_awal = 0;

                $masuk_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_mulai)))->whereNotNull('id_barang_masuk')->get()->toArray();
                if (!empty($masuk_awal)) {
                    $column = array_column($masuk_awal, 'qty_barang');
                    $total_masuk_awal = array_sum($column);
                }

                $keluar_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<', date('Y-m-d 00:00:00', strtotime($periode_mulai)))->whereNotNull('id_barang_keluar')->get()->toArray();
                if (!empty($keluar_awal)) {
                    $column = array_column($keluar_awal, 'qty_barang');
                    $total_keluar_awal = array_sum($column);
                }

                $data['stok'] = $total_masuk_awal - $total_keluar_awal;

                //jumlah sekarang
                $total_masuk_akhir = 0;
                $total_keluar_akhir = 0;

                $masuk_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<=', date('Y-m-d 23:59:59', strtotime($periode_akhir)))->whereNotNull('id_barang_masuk')->get()->toArray();
                if (!empty($masuk_awal)) {
                    $column = array_column($masuk_awal, 'qty_barang');
                    $total_masuk_akhir = array_sum($column);
                }

                $keluar_awal = BarangHarga::where('id_barang', $arr['id'])->where('tanggal_barang', '<=', date('Y-m-d 23:59:59', strtotime($periode_akhir)))->whereNotNull('id_barang_keluar')->get()->toArray();
                if (!empty($keluar_awal)) {
                    $column = array_column($keluar_awal, 'qty_barang');
                    $total_keluar_akhir = array_sum($column);
                }

                $data['opname'] = $total_masuk_akhir - $total_keluar_akhir;
                return $data;
            }, $barang);
        }

        // return $report;

        $pelaksana = ucwords(strtolower(Auth::user()->nama));
        $koordinator = ucwords(strtolower($post['koordinator']));
        $audit = ucwords(strtolower($post['audit']));

        $data = [
            'stock_no'           => $stock_no,
            'group'              => $group,
            'periode_mulai'      => $periode_mulai,
            'periode_akhir'      => $periode_akhir,
            'text_periode_awal'  => $text_periode_awal,
            'text_periode_akhir' => $text_periode_akhir,
            'opname_no'          => $opname_no,
            'pelaksana'          => $pelaksana,
            'koordinator'        => $koordinator,
            'audit'              => $audit,
            'report'             => $report,
        ];

        return view('report::opname', $data);
    }

    public function stokCetak()
    {
        $pdf = PDF::loadview('report::testing');
        return $pdf->stream();

        $pdf = PDF::loadview('report::opname');
        return $pdf->download('laporan-pdf.pdf');
    }

}
