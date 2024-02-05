<html>

<head>
    <style>
        body {
            font-size: 15px;
            font-family: 'Times New Roman', Times, serif;
        }

        .margin-body {
            margin: 43px 80px 40px 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .text-italic {
            font-style: italic;
        }

        .text-underline {
            text-decoration: underline;
        }

        /* font like h1 */
        .font-h1 {
            font-size: 1.6em;
        }

        .header-top {
            margin-top: -10px;
        }

        .table-full {
            width: 100%;
        }

        /* table array 0 and 2 */
        .main,
        .table-0,
        .table-2 {
            margin: 10px 0px 15px 20px;
            width: 100%;
        }

        .main-not,
        .table-0-not,
        .table-2-not {
            margin: 5px 0px 5px 20px;
            width: 100%;
        }

        main,
        .table-1 {
            margin: 10px 0px 12px 20px;
            width: 100%;
        }

        .table-2 td {
            padding-right: -20px;
        }

        .table-2-not td {
            padding-right: -20px;
        }

        .td-alamat {
            padding-top: 10px;
        }

        main {
            margin-top: 25px;
        }

        /* Container for the table with text-align: right */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        footer table {
            width: 100%;
        }

        .footer-text {
            font-size: 11px;
        }

        small {
            font-size: 10px;
        }

        hr {
            /* border double */
            border-top: 0.5px #000;
            margin-right: -40px;
        }

        hr.first {
            /* border double */
            margin-bottom: -3px;
        }

        img {
            margin-top: -10px;
            margin-bottom: -30px;
        }

        .img-bg-header {
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/assets/image/certificate_header.png'))) }}");
            background-repeat: no-repeat;
            margin-right: -60px;
        }

        .font-italic-table {
            font-size: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        // check object is array
        if (!is_array($object)) {
            $object = [$object];
        }
        // if not array change to array
        $object = (array) $object;
        $object = json_decode(json_encode($object), true);
    @endphp
    @foreach ($object as $key => $data)
        {{-- create page break when last data not page break --}}
        {{-- @if (count($object) > 1 && $key == count($object) - 1)
            <div class="page-break"></div>
        @endif --}}
        @php
            // create function date format indonesia
            if (!function_exists('tgl_indo')) {
                function tgl_indo($tanggal)
                {
                    $bulan = [
                        1 => 'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember',
                    ];
                    $pecahkan = explode('-', $tanggal);

                    // variabel pecahkan 0 = tanggal
                    // variabel pecahkan 1 = bulan
                    // variabel pecahkan 2 = tahun

                    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
                }
            }

            $length = strlen($data['pelanggan']['alamat'])

        @endphp

        <div class="margin-body">
            <header>
                <div class="img-bg-header">
                    <table class="table-full">
                        <tr class="text-center">
                            <td colspan="2"><span class="text-bold font-h1">SERTIFIKAT KALIBRASI</span><br>
                                <span class="text-italic">Calibration Certificate</span>
                            </td>
                        </tr>
                        {{-- <tr class="text-center">
                            <td colspan="2"><span class="text-italic">Calibration Certificate</span><br></td>
                        </tr> --}}
                    </table>
                </div>
                <table class="table-full">
                    <tr class="text-italic header-top">
                        <td class="text-right" width="45%" style="padding-right: 65px">No Sertifikat</td>
                        <td><span class="text-italic">{{ $data['kode'] }}</span><br></td>
                    </tr>
                </table>
                <table class="table-full" style="margin-top: 5px">
                    <tr>
                        <td width="80%" class="text-right">
                            No Order
                        </td>
                        <td>
                            <i style="padding-left: 15px">{{ $data['order'] }}</i>
                        </td>
                    </tr>
                </table>
            </header>
            <main class="{{ $length > 100 ? 'main-not' : 'main' }}">
                <b>Identifikasi Alat</b><br>
                <i style="font-size: 13px">Equipment Identification</i>
                <table class="{{ $length > 100 ? 'table-0-not' : 'table-0' }}">
                    <tr>
                        <td width="2%">1</td>
                        <td width="43%">
                            <b>Nama Alat /</b>
                            <i class="font-italic-table">Name of Equipment</i>
                        </td>
                        <td width="1%">:</td>
                        <td>{{ $data['alat']['nama'] }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <b>Merk Pabrik /</b>
                            <i class="font-italic-table">Manufacture</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['alat']['merk'] }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <b>Tipe / Model /</b>
                            <i class="font-italic-table">Type / Model</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['alat']['model'] }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <b>No. Seri /</b>
                            <i class="font-italic-table">Serial Number</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['alat']['noseri'] }}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>
                            <b>Tanggal Penerimaan /</b>
                            <i class="font-italic-table">Receipt Date</i>
                        </td>
                        <td>:</td>
                        <td>{{ tgl_indo($data['alat']['tgl_penerimaan']) }}</td>
                    </tr>
                </table>

                <b>Identitas Pemilik</b><br>
                <i style="font-size: 13px">Owner's Identity</i>
                <table class="table-1">
                    <tr>
                        <td width="2%">1</td>
                        <td width="43%">
                            <b>Nama Pelanggan /</b>
                            <i class="font-italic-table">Customer's Name</i>
                        </td>
                        <td width="1%">:</td>
                        <td>{{ $data['pelanggan']['nama'] }}</td>
                    </tr>
                    <tr>
                        <td class="td-alamat">2</td>
                        <td class="td-alamat">
                            <b>Alamat /</b>
                            <i class="font-italic-table">Address</i>
                        </td>
                        <td class="td-alamat">:</td>
                        <td class="td-alamat">{{ $data['pelanggan']['alamat'] }}
                        </td>
                    </tr>
                </table>

                <b>Pelaksanaan Kalibrasi</b><br>
                <i style="font-size: 13px">Implementation of Calibration</i>
                <table class="{{ $length > 100 ? 'table-0-not' : 'table-0' }}">
                    <tr>
                        <td width="2%">1</td>
                        <td width="43%">
                            <b>Tempat /</b>
                            <i class="font-italic-table">Place</i>
                        </td>
                        <td width="1%">:</td>
                        <td>{{ $data['detail']['tempat'] }}</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <b>Ruangan /</b>
                            <i class="font-italic-table">Room</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['detail']['ruangan'] }}</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <b>Tanggal /</b>
                            <i class="font-italic-table">Cal Date</i>
                        </td>
                        <td>:</td>
                        <td>{{ tgl_indo($data['detail']['tgl_kalibrasi']) }}</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <b>Tanggal Berakhir /</b>
                            <i class="font-italic-table">Cal Due Date</i>
                        </td>
                        <td>:</td>
                        <td>{{ tgl_indo($data['detail']['tgl_kalibrasi_exp']) }}</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>
                            <b>Teknisi /</b>
                            <i class="font-italic-table">Name of Technician</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['detail']['teknisi'] }}</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>
                            <b>Metode /</b>
                            <i class="font-italic-table">Method</i>
                        </td>
                        <td>:</td>
                        <td>{{ $data['detail']['metode'] }}</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>
                            <b>Hasil Kalibrasi /</b>
                            <i class="font-italic-table">Calibration Result</i>
                        </td>
                        <td>:</td>
                        <td class="text-bold">{{ $data['detail']['hasil'] }}</td>
                    </tr>
                </table>
                <table class="{{ $length > 100 ? 'table-2-not text-right' : 'table-2 text-right' }}">
                    <tr>
                        <td>Surabaya, {{ tgl_indo($data['tgl_sekarang']) }}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 20px">Menyetujui,</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 30px; padding-bottom: 10px;"><small><i>Approved,</i></small></td>
                    </tr>
                    <tr>
                        <td>
                            @if ($ttd == 'true')
                                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('/assets/image/nida_ttd.png'))) }}"
                                    width="125">
                            @else
                                <div style="height: 50px"></div>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 10px;"><span class="text-underline">Nida Ariba, S.T</span></td>
                    </tr>
                    <tr>
                        <td>
                            <span style="padding-right: 2px">Manager Mutu</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="padding-right: 5px">
                                <small><i>Quality Manager</i></small>
                            </span>
                        </td>
                    </tr>
                </table>
                <hr class="first">
                <hr>
                <div class="text-center" style="margin-right: -30px">
                    <span class="footer-text">Sertifikat ini dilarang digandakan kecuali sepenuhnya secara tidak lengkap
                        tanpa persetujuan Laboratorium Kalibrasi PT. Sinko Prima Alloy</span> <br>
                    <span class="text-italic footer-text">This Certificate shall not be uncomplete reproduce, without
                        written approval of PT. Sinko Prima Alloy Calibration Laboratory</span>
                </div>
            </main>
        </div>

        <footer>
            <table>
                <tr>
                    <td width="55%" class="text-right footer-text">
                        Page 1 of {{ $hal }}
                    </td>
                    <td class="text-right footer-text">
                        <i>Nomor Dokumen : SPA-FR/LAB-03PBK</i>
                    </td>
                </tr>
            </table>
            <div class="text-right">

            </div>
        </footer>
    @endforeach

</body>

</html>
