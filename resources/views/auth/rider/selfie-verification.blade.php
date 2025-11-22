<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selfie Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .upload-area {
            border: 2px dashed #d1d5db;
            transition: border-color 0.2s ease, background-color 0.2s ease;
        }
        .upload-area.dragover {
            border-color: #0d9488;
            background-color: #ecfdf5;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden">
            <div class="bg-emerald-600 px-6 py-8 text-white text-center">
                <h1 class="text-3xl font-bold mb-2">Final Step: Selfie Verification</h1>
                <p class="text-emerald-100 text-sm sm:text-base">
                    Help us keep the marketplace safe by verifying your identity. Upload a recent selfie where your face is clearly visible.
                </p>
            </div>

            <div class="px-6 py-8 space-y-6">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                        <p class="text-red-700 font-semibold">There was a problem with your upload:</p>
                        <ul class="mt-2 list-disc list-inside text-red-600 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-md text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="bg-gray-50 rounded-xl p-5">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">Guidelines</h2>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-600">•</span>
                            Take the photo in a well-lit area so your face is clearly visible.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-600">•</span>
                            Do not wear hats, helmets, or sunglasses that cover your face.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-emerald-600">•</span>
                            Accepted formats: JPG or PNG, up to 4MB.
                        </li>
                    </ul>
                </div>

                <form id="selfie-form" action="{{ route('rider.selfie-verification.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="upload-area rounded-2xl p-8 text-center" id="drop-area">
                        <input type="file" name="selfie" id="selfie-input" accept="image/jpeg,image/png" class="hidden" required>
                        <div class="space-y-4">
                            <div class="flex justify-center">
                                <span class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-emerald-50 text-emerald-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 10.5L12 6.75 8.25 10.5m3.75-3.75V17.25" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19.5 8.25H16.5a2.25 2.25 0 01-2.25-2.25V3.75M4.5 15.75h3A2.25 2.25 0 009.75 13.5V10.5" />
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-800">Drag & Drop your selfie here</p>
                                <p class="text-sm text-gray-500">or</p>
                                <button type="button" id="browse-btn" class="mt-2 px-5 py-2 rounded-full bg-emerald-600 text-white font-semibold hover:bg-emerald-500 transition">
                                    Browse Files
                                </button>
                            </div>
                            <p class="text-xs text-gray-500" id="file-name">No file selected</p>
                        </div>
                    </div>

                    <div id="preview-wrapper" class="hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview</p>
                        <img id="selfie-preview" src="#" alt="Selfie preview" class="w-full max-w-xs rounded-xl shadow-md border">
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <a href="{{ route('rider.logout') }}" class="text-sm text-gray-500 hover:text-gray-700">Logout</a>
                        <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-emerald-600 text-white font-semibold rounded-xl shadow hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-200">
                            Submit Selfie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('selfie-input');
        const browseBtn = document.getElementById('browse-btn');
        const fileNameEl = document.getElementById('file-name');
        const previewWrapper = document.getElementById('preview-wrapper');
        const previewImg = document.getElementById('selfie-preview');

        function updatePreview(file) {
            if (!file) {
                previewWrapper.classList.add('hidden');
                previewImg.src = '#';
                fileNameEl.textContent = 'No file selected';
                return;
            }

            fileNameEl.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function (e) {
                previewImg.src = e.target.result;
                previewWrapper.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }

        browseBtn.addEventListener('click', () => fileInput.click());

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            updatePreview(file);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.add('dragover');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropArea.classList.remove('dragover');
            });
        });

        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length) {
                fileInput.files = files;
                updatePreview(files[0]);
            }
        });
    </script>
</body>
</html>
