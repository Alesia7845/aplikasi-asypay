<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat AI</title>

    <!-- Tailwind CDN untuk styling cepat -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">
        <h1 class="text-2xl font-bold mb-4 text-center">Chat AI</h1>

        <!-- Area chat -->
        <div id="reply" class="h-64 overflow-y-auto mb-4 border rounded-lg p-3 bg-gray-50">
            <p class="text-gray-500 text-center">Belum ada pesan</p>
        </div>

        <!-- Form input pesan -->
        <form id="chatForm" class="flex space-x-2">
            @csrf
            <input type="text" id="message" name="message" placeholder="Tulis pesan..." required
                class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">Kirim</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const form = document.getElementById('chatForm');
        const replyDiv = document.getElementById('reply');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = document.getElementById('message').value.trim();
            if(!message) return;

            // Tampilkan pesan user
            const userMsg = document.createElement('p');
            userMsg.className = 'text-right text-gray-700 mb-1';
            userMsg.innerHTML = '<strong>Kamu:</strong> ' + message;
            replyDiv.appendChild(userMsg);
            replyDiv.scrollTop = replyDiv.scrollHeight;

            try {
                const response = await axios.post('/chat', { message }, {
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
                });

                const aiMsg = document.createElement('p');
                aiMsg.className = 'text-left text-gray-800 mb-1';
                aiMsg.innerHTML = '<strong>AI:</strong> ' + (response.data.reply ?? 'AI tidak merespon');
                replyDiv.appendChild(aiMsg);
                replyDiv.scrollTop = replyDiv.scrollHeight;

                document.getElementById('message').value = '';
            } catch (error) {
                const errMsg = document.createElement('p');
                errMsg.className = 'text-red-500 mb-1';
                errMsg.innerHTML = 'Error: ' + (error.response?.data?.error || error.message);
                replyDiv.appendChild(errMsg);
                replyDiv.scrollTop = replyDiv.scrollHeight;
            }
        });
    </script>

</body>
</html>
