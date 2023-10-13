<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Cetak Seri</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <style>
        @media print {

            /* ukuran 10cm x 2 cm landscape */
            @page {
                size: 10cm 5cm;
                margin: 0;
            }

        }

        .flex-center {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .wrapper {
            font-family: monospace;
            font-size: 20px;
        }

        .company {
            margin-bottom: -5px;
        }
    </style>
</head>

<body>
    <div class="flex-center">
        <div class="wrapper">
            <div class="company">
                Elitech
            </div>
            <svg id="barcode"></svg>
        </div>
    </div>

    <script>
        // generate the barcode
        JsBarcode("#barcode", " {{ $seri }}", {
            lineColor: "#000",
            width: 1,
            height: 30,
            displayValue: true
        });
    </script>
</body>

</html>
<script>
    $(document).ready(function() {
        setTimeout(() => {
            window.print();
        }, 100);
    });
    // click cancel close window
    window.onafterprint = function() {
        window.close();
    }
</script>
