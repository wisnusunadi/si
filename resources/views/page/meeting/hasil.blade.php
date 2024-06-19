<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - {{ $data->judul }}</title>
    <style>
        .table-header {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        .judul-header {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            background-color: #D9D9D9;
            border: 1px solid black;
        }

        .judul {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            border: 1px solid black;
        }

        .full-border {
            border: 1px solid black;
        }

        .full-border td {
            border: 1px solid black;
        }

        .company {
            font-weight: bold;
            font-size: 16px;
            text-transform: uppercase;
            border: 1px solid black;
            text-align: center;
        }

        .table-body {
            margin-top: 20px;
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        .table-body th {
            border: 1px solid black;
            text-align: center;
            text-transform: uppercase;
        }

        .table-body td {
            border: 1px solid black;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .table-footer {
            width: 100%;
            padding-left: 20px;
        }

        .text-capitalize {
            text-transform: capitalize;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    @php
        function changeUnderscoreToSpace($string)
        {
            return str_replace('_', ' ', $string);
        }
    @endphp
    <table class="table-header">
        <tr>
            <td rowspan="6" class="company">PT SINKO PRIMA ALLOY</td>
            <td>Hari, Tanggal</td>
            <td>:</td>
            <td>{{ $data->tgl_meeting }}</td>
            <td class="judul-header">Judul Rapat</td>
        </tr>
        <tr>
            <td>Waktu</td>
            <td>:</td>
            <td>{{ $data->mulai }} WIB - {{ $data->selesai }} WIB</td>
            <td rowspan="5" class="judul">
                {{ $data->judul }} <br>
                @if ($data->status != 'terlaksana')
                    <img src="{{ public_path('images/draft.png') }}" alt="not-approved" style="width: 50px">
                @endif
            </td>
        </tr>
        <tr>
            <td>Tempat</td>
            <td>:</td>
            <td>{{ $data->lokasi }}</td>
        </tr>
        <tr>
            <td>Pimpinan Rapat</td>
            <td>:</td>
            <td>{{ $data->pimpinan }}</td>
        </tr>
        <tr>
            <td>Moderator</td>
            <td>:</td>
            <td>{{ $data->moderator }}</td>
        </tr>
        <tr>
            <td>Peserta</td>
            <td>:</td>
            <td>Terlampir Daftar Hadir</td>
        </tr>
        <tr class="full-border">
            <td>No Dokumen:</td>
            <td colspan="3">Tanggal Terbit:</td>
            <td>Revisi:</td>
        </tr>
    </table>

    <table class="table-body">
        <thead>
            <tr>
                <td colspan="4" class="judul-header">detail agenda rapat</td>
            </tr>
            <tr>
                <th style="width: 5%">no</th>
                <th style="width: 30%">penanggung jawab</th>
                <th>uraian</th>
                <th>kesesuaian</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->hasil_notulen as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td class="text-center">{{ $item['pic'] }}</td>
                    <td>{{ $item['isi'] }}</td>
                    <td class="text-center">
                        <span class="text-capitalize">
                            {{ changeUnderscoreToSpace($item['hasil']) }}
                        </span>, Oleh
                        {{ $item['dicek'] }}
                        {{ $item['checked_at'] }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="table-body">
        <thead>
            <tr>
                <td colspan="2" class="judul-header">hasil rapat</td>
            </tr>
            <tr>
                <th style="width: 5%">no</th>
                <th>uraian hasil rapat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->hasil_meet as $key => $item)
                <tr>
                    <td class="text-center">{{ $key + 1 }}</td>
                    <td>{{ $item['isi'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer class="footer" id="footer">
        <table class="table-footer">
            <tr>
                <td>Surabaya, {{ $data->tgl_simpan }}</td>
            </tr>
            <tr>
                <td class="text-center">Notulis,</td>
                <td style="width: 45%"></td>
                <td class="text-center">Pimpinan Rapat,</td>
            </tr>
            <tr>
                @if ($data->status != 'terlaksana')
                    <td colspan="3">
                        <br>
                        <br>
                        <br>
                    </td>
                @endif
            </tr>
            <tr>
                <td class="text-center">
                    @if ($data->status == 'terlaksana')
                        <img src="/images/approved.png" alt="approved" style="width: 100px">
                    @endif
                    <hr>
                </td>
                <td style="width: 45%"></td>
                <td class="text-center">
                    @if ($data->status == 'terlaksana')
                        <img src="/images/approved.png" alt="approved" style="width: 100px">
                    @endif
                    <hr>
                </td>
            </tr>
            <tr>
                <td class="text-center">{{ $data->notulen }}</td>
                <td style="width: 45%"></td>
                <td class="text-center">{{ $data->pimpinan }}</td>
            </tr>
            <tr>
            </tr>
        </table>
        <p><u>Tembusan:</u></p>
        <ol>
            <li>Peserta Rapat</li>
            <li>Direksi</li>
            <li>HRD</li>
            <li>Arsip Kontrol Dokumen</li>
        </ol>
    </footer>
    <div class="page-break"></div>
    <p>Lampiran</p>
    @php
        // group documents by type
        function groupDocumentsByExtension($documents)
        {
            // Inisialisasi array untuk mengelompokkan dokumen
            $groupedDocuments = [
                'foto' => [],
                'video' => [],
                'rekaman' => [],
                'dokumen' => [],
            ];

            // Iterasi melalui setiap dokumen dalam array
            foreach ($documents as $document) {
                // Ekstraksi ekstensi file
                $ext = strtolower(pathinfo($document['nama'], PATHINFO_EXTENSION));

                // Pengelompokan berdasarkan ekstensi
                switch ($ext) {
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                        $groupedDocuments['foto'][] = $document;
                        break;
                    case 'mp4':
                    case 'avi':
                    case 'mkv':
                        $groupedDocuments['video'][] = $document;
                        break;
                    case 'mp3':
                    case 'wav':
                    case 'mpeg':
                        $groupedDocuments['rekaman'][] = $document;
                        break;
                    default:
                        $groupedDocuments['dokumen'][] = $document;
                        break;
                }
            }

            return $groupedDocuments;
        }

        // Group documents by extension
        $data->dokumen_meet = groupDocumentsByExtension($data->dokumen_meet);

        function showImageFromExternalUrl($url)
        {
            // stream image from external url
            $server = $_SERVER['HTTP_HOST'];
            $image = 'http://' . $server . '/api/hr/meet/show_dokumen_ftp?name=' . $url;
            $imageData = base64_encode(@file_get_contents($image));
            return 'data:image/jpeg;base64,' . $imageData;
        }

        function showLinkOnExternalUrl($url)
        {
            $linkUrlNow = $_SERVER['HTTP_HOST'];
            return 'http://' . $linkUrlNow . '/api/hr/meet/show_dokumen_ftp?name=' . $url;
        }

    @endphp

    {{-- buat perulangan --}}
    @foreach ($data->dokumen_meet as $type => $documents)
        @if (count($documents) > 0)
            <h3>{{ $type }}</h3>
            <table class="table-body">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Lampiran
                            @php
                                // jumlah dokumen
                                $total = count($documents);
                            @endphp
                            ({{ $total }})
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $index => $doc)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <a href="{{ showLinkOnExternalUrl($doc['nama']) }}" target="_blank">
                                    {{ $doc['original'] }}
                                </a>
                                {{-- @if ($type == 'foto')
                                    <img src="{{ showImageFromExternalUrl($doc['nama']) }}" alt="foto"
                                        style="width: 100%">
                                @else
                                    <a href="{{ showLinkOnExternalUrl($doc['nama']) }}" target="_blank">
                                        {{ $doc['original'] }}
                                    </a>
                                @endif --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    @endforeach

</body>

</html>
<script>
    window.onload = function() {
        // wait for 1 second before printing
        setTimeout(function() {
            window.print();
        }, 1000);
    }

    function detectPrintPages() {
        // Create a temporary iframe to hold the printable content
        const printFrame = document.createElement('iframe');
        printFrame.style.position = 'absolute';
        printFrame.style.width = '100%';
        printFrame.style.height = '100%';
        printFrame.style.top = '-9999px'; // Move the iframe off-screen
        document.body.appendChild(printFrame);

        // Clone the body content into the iframe
        const frameDoc = printFrame.contentDocument || printFrame.contentWindow.document;
        frameDoc.open();
        frameDoc.write('<html><head></head><body>' + document.body.innerHTML + '</body></html>');
        frameDoc.close();

        // Ensure the iframe content is fully loaded
        printFrame.onload = () => {
            const printableHeight = 1122; // Standard A4 size at 96 DPI
            const contentHeight = frameDoc.body.scrollHeight;

            // Check if content exceeds 80% of A4 paper height
            const eightyPercentHeight = printableHeight * 0.8;
            if (contentHeight >= eightyPercentHeight) {
                console.log('Content exceeds 80% of A4 paper height');

                // make new page with page break
                const newPage = frameDoc.createElement('div');
                newPage.style.pageBreakBefore = 'always';
                frameDoc.body.appendChild(newPage);
            }

            // Copy the style rules from the iframe to the print window and footer
            const styleElements = frameDoc.querySelectorAll('style, link[rel="stylesheet"]');
            styleElements.forEach((styleElement) => {
                document.head.appendChild(styleElement.cloneNode(true));
            });

            // Print the iframe content
            printFrame.contentWindow.print();
        };
    }

    // Event listener for the window load event
    window.addEventListener('load', () => {
        // Set timeout to ensure all content is loaded
        setTimeout(() => {
            // detectPrintPages();
        }, 500); // Adjust timeout duration if necessary
    });

    window.onafterprint = function() {
        window.close();
    }
</script>
