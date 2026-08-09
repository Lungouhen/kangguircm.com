import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('builder-container');
    if (!container) return;

    // Initialize Drag and Drop
    new Sortable(container, {
        animation: 150,
        handle: '.drag-handle',
        ghostClass: 'bg-blue-100',
        onEnd: function (evt) {
            // Reorder blocks in Alpine state or hidden input
            const order = Array.from(container.children).map(el => el.dataset.id);
            const input = document.getElementById('blocks-order-input');
            if(input) {
                input.value = JSON.stringify(order);
            }
            
            // Auto-save via AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if(csrfToken) {
                fetch(window.location.href, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content
                    },
                    body: JSON.stringify({ blocks_order: order })
                }).then(res => {
                    if(res.ok) console.log('Order saved');
                });
            }
        }
    });

    // Handle Widget Addition
    window.addWidget = function(type) {
        fetch(`/admin/widgets/${type}/config`, {
            headers: {'Accept': 'application/json'}
        })
        .then(res => res.json())
        .then(config => {
            const html = `
                <div class="block p-4 mb-4 bg-white rounded shadow border" data-id="${Date.now()}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-bold">${config.label}</span>
                        <button class="drag-handle cursor-move text-gray-400">☰</button>
                    </div>
                    <!-- Render Config Fields Here -->
                    ${Object.keys(config.fields).map(key => `
                        <div class="mb-2">
                            <label class="block text-sm font-bold">${key}</label>
                            <input type="text" name="blocks[${Date.now()}][${key}]" class="w-full border rounded p-1">
                        </div>
                    `).join('')}
                    <button onclick="this.parentElement.remove()" class="text-red-500 text-sm mt-2">Remove Block</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        });
    };
});
