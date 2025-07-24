<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic PDF Viewer</title>
    <style>
        #pdf-container {
            width: 80%;
            margin: 20px auto;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #pdf-canvas {
            width: 100%;
            border: 1px solid #000;
        }
        #controls {
            text-align: center;
            margin: 10px 0;
        }
        .btn {
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }
        .btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div id="pdf-container">
        <div id="controls">
            <button id="prev-page" class="btn">Previous</button>
            <span>Page: <span id="current-page">1</span> / <span id="total-pages">0</span></span>
            <button id="next-page" class="btn">Next</button>
        </div>
        <canvas id="pdf-canvas"></canvas>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>

    <script>
        // Function to get the `invoice_id` from the URL query string
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
        }

        const invoiceId = getQueryParam('invoice_id'); // Extract invoice_id from URL

        // Fetch the dynamic PDF path from the backend
        fetch(`fetch_invoice_path.php?invoice_id=${invoiceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const url = data.file_url; // Fetch the file URL from the response

                    const pdfjsLib = window['pdfjs-dist/build/pdf'];
                    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

                    let pdfDoc = null,
                        pageNum = 1,
                        pageRendering = false,
                        pageNumPending = null;

                    const scale = 1.5,
                        canvas = document.getElementById('pdf-canvas'),
                        ctx = canvas.getContext('2d');

                    // Render the page
                    const renderPage = (num) => {
                        pageRendering = true;
                        pdfDoc.getPage(num).then((page) => {
                            const viewport = page.getViewport({ scale });
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            const renderContext = {
                                canvasContext: ctx,
                                viewport: viewport,
                            };
                            const renderTask = page.render(renderContext);

                            renderTask.promise.then(() => {
                                pageRendering = false;

                                if (pageNumPending !== null) {
                                    renderPage(pageNumPending);
                                    pageNumPending = null;
                                }
                            });
                        });

                        document.getElementById('current-page').textContent = num;
                    };

                    const queueRenderPage = (num) => {
                        if (pageRendering) {
                            pageNumPending = num;
                        } else {
                            renderPage(num);
                        }
                    };

                    const onPrevPage = () => {
                        if (pageNum <= 1) {
                            return;
                        }
                        pageNum--;
                        queueRenderPage(pageNum);
                    };

                    const onNextPage = () => {
                        if (pageNum >= pdfDoc.numPages) {
                            return;
                        }
                        pageNum++;
                        queueRenderPage(pageNum);
                    };

                    document.getElementById('prev-page').addEventListener('click', onPrevPage);
                    document.getElementById('next-page').addEventListener('click', onNextPage);

                    // Load the PDF dynamically
                    pdfjsLib.getDocument(url).promise.then((pdfDoc_) => {
                        pdfDoc = pdfDoc_;
                        document.getElementById('total-pages').textContent = pdfDoc.numPages;
                        renderPage(pageNum);
                    });
                } else {
                    alert('Failed to fetch invoice file: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching invoice file:', error);
                alert('Unable to fetch PDF file.');
            });
    </script>
</body>
</html>
