<!-- resources/views/chat.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Admin</title>

    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl flex flex-col p-4">
        <h1 class="text-3xl font-extrabold mb-4 text-center text-green-700">Chat Admin</h1>

        <!-- Area chat -->
        <div id="reply" class="flex-1 overflow-y-auto mb-4 p-3 space-y-2 bg-green-50 rounded-2xl border border-green-200">
            <p class="text-gray-500 text-center mt-10">Belum ada pesan</p>
        </div>

        <!-- Quick question buttons -->
        <div class="mb-4 flex flex-wrap gap-2">
            <button class="quick-btn px-3 py-1 bg-green-200 text-green-800 rounded-full hover:bg-green-300 transition">SPP harganya berapa?</button>
            <button class="quick-btn px-3 py-1 bg-green-200 text-green-800 rounded-full hover:bg-green-300 transition">Ada pembayaran apa saja?</button>
            <button class="quick-btn px-3 py-1 bg-green-200 text-green-800 rounded-full hover:bg-green-300 transition">Bisa bayar lewat apa saja?</button>
        </div>

        <!-- Form input pesan -->
        <form id="chatForm" class="flex space-x-2">
            @csrf
            <input type="text" id="message" name="message" placeholder="Tulis pesan..." required
                class="flex-1 px-4 py-2 border border-green-300 rounded-full focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="submit"
                class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition">Kirim</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const form = document.getElementById('chatForm');
        const replyDiv = document.getElementById('reply');
        const quickBtns = document.querySelectorAll('.quick-btn');

        // Quick button click
        quickBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                document.getElementById('message').value = btn.textContent;
                form.dispatchEvent(new Event('submit', {cancelable: true}));
            });
        });

        function addMessage(sender, text) {
            const msgDiv = document.createElement('div');
            msgDiv.classList.add('flex', sender === 'user' ? 'justify-end' : 'justify-start');

            const bubble = document.createElement('div');
            bubble.classList.add('px-4', 'py-2', 'rounded-2xl', 'max-w-xs', 'break-words');

            if(sender === 'user') {
                bubble.classList.add('bg-green-500', 'text-white', 'rounded-br-none');
            } else {
                bubble.classList.add('bg-green-100', 'text-green-800', 'rounded-bl-none');
            }

            bubble.innerHTML = text;
            msgDiv.appendChild(bubble);
            replyDiv.appendChild(msgDiv);
            replyDiv.scrollTop = replyDiv.scrollHeight;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const message = document.getElementById('message').value.trim();
            if(!message) return;

            // Hapus pesan placeholder "Belum ada pesan"
            if(replyDiv.querySelector('p') && replyDiv.querySelector('p').textContent.includes('Belum ada pesan')) {
                replyDiv.innerHTML = '';
            }

            // Tampilkan pesan user
            addMessage('user', message);

            try {
                // Simulasi respon admin
                setTimeout(() => {
                    addMessage('admin', 'Terimakasih sudah menghubungi. Nanti admin akan merespon pertanyaan kamu mengenai: "<em>' + message + '</em>"');
                }, 800); // delay 0.8 detik supaya terlihat alami

                document.getElementById('message').value = '';
            } catch (error) {
                addMessage('admin', 'Error: ' + (error.response?.data?.error || error.message));
            }
        });
    </script>

</body>
</html>
