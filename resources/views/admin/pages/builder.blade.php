@extends('admin.layout')

@section('title', 'Visual Page Builder - ' . $page->title)

@section('content')
<div class="flex h-screen overflow-hidden bg-gray-100" x-data="pageBuilder()">
    
    <!-- Left Sidebar: Widget Library -->
    <div class="w-80 bg-white border-r border-gray-200 flex flex-col shadow-lg z-20">
        <div class="p-5 border-b border-gray-200 bg-indigo-900">
            <h2 class="text-white font-bold text-lg flex items-center">
                <span class="mr-2">🧩</span> Widget Library
            </h2>
            <p class="text-indigo-200 text-xs mt-1">Drag & drop to build</p>
        </div>
        
        <div class="flex-1 overflow-y-auto p-4 space-y-3">
            @foreach($widgets as $widget)
                <div class="bg-white border border-gray-200 rounded-lg p-4 cursor-move hover:shadow-md hover:border-indigo-500 transition-all group"
                     draggable="true"
                     data-widget-id="{{ $widget->getId() }}"
                     @dragstart="onDragStart($event, '{{ $widget->getId() }}')">
                    
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center">
                            <span class="text-2xl mr-3">{{ $widget->getIcon() }}</span>
                            <span class="font-semibold text-gray-800">{{ $widget->getName() }}</span>
                        </div>
                        <span class="text-gray-400 group-hover:text-indigo-600">⋮⋮</span>
                    </div>
                    
                    <p class="text-xs text-gray-500 pl-9">Click to add or drag to canvas</p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Center: Visual Canvas -->
    <div class="flex-1 flex flex-col h-full relative">
        <!-- Top Toolbar -->
        <div class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.pages.index') }}" class="text-gray-500 hover:text-gray-700">
                    ← Back
                </a>
                <h1 class="text-xl font-bold text-gray-800">{{ $page->title }}</h1>
                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full" x-show="saved">Saved</span>
            </div>
            
            <div class="flex items-center space-x-3">
                <button @click="preview()" class="px-4 py-2 text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 font-medium">
                    👁 Preview
                </button>
                <button @click="save()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-md transition-transform active:scale-95">
                    💾 Save Changes
                </button>
            </div>
        </div>

        <!-- Drop Zone / Canvas -->
        <div class="flex-1 overflow-y-auto bg-gray-100 p-8" 
             @dragover.prevent="isDragging = true" 
             @dragleave="isDragging = false"
             @drop.prevent="onDrop($event)">
            
            <div class="max-w-5xl mx-auto bg-white min-h-[800px] shadow-xl rounded-xl overflow-hidden relative">
                
                <!-- Empty State -->
                <div x-show="blocks.length === 0" 
                     class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 border-4 border-dashed border-gray-200 m-4 rounded-lg"
                     :class="{ 'border-indigo-400 bg-indigo-50': isDragging }">
                    <span class="text-6xl mb-4">🏗️</span>
                    <p class="text-xl font-medium">Drag widgets here to start building</p>
                    <p class="text-sm mt-2">or click on a widget in the sidebar</p>
                </div>

                <!-- Rendered Blocks -->
                <template x-for="(block, index) in blocks" :key="block.id">
                    <div class="relative group border-b border-gray-100 last:border-0">
                        <!-- Block Actions -->
                        <div class="absolute right-4 top-4 z-20 opacity-0 group-hover:opacity-100 transition-opacity flex space-x-2">
                            <button @click="editBlock(index)" class="p-2 bg-blue-500 text-white rounded shadow hover:bg-blue-600">✏️</button>
                            <button @click="moveUp(index)" class="p-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600">↑</button>
                            <button @click="moveDown(index)" class="p-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600">↓</button>
                            <button @click="removeBlock(index)" class="p-2 bg-red-500 text-white rounded shadow hover:bg-red-600">🗑️</button>
                        </div>
                        
                        <!-- Block Content (Live Preview) -->
                        <div x-html="renderBlock(block)"></div>
                    </div>
                </template>
            </div>
            
            <div class="h-20"></div> <!-- Spacer -->
        </div>
    </div>

    <!-- Right Sidebar: Properties Panel (Modal/Drawer) -->
    <div x-show="editingBlock !== null" 
         class="fixed inset-y-0 right-0 w-96 bg-white shadow-2xl transform transition-transform duration-300 z-30 overflow-y-auto"
         x-transition:enter="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="translate-x-0"
         x-transition:leave-end="translate-x-full">
        
        <div class="p-6 border-b border-gray-200 bg-gray-50 flex justify-between items-center sticky top-0">
            <h3 class="font-bold text-lg text-gray-800">Configure Widget</h3>
            <button @click="editingBlock = null" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
        </div>
        
        <div class="p-6 space-y-6" x-if="editingBlock !== null">
            <template x-if="blocks[editingBlock]">
                <div>
                    <p class="text-sm text-gray-500 mb-4">Editing: <span x-text="blocks[editingBlock].type"></span></p>
                    
                    <!-- Dynamic Fields based on Widget Type -->
                    <template x-for="field in getFieldDefinition(blocks[editingBlock].type)" :key="field.name">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1" x-text="field.label"></label>
                            
                            <input x-if="field.type === 'text' || field.type === 'url'" 
                                   type="text" 
                                   x-model="blocks[editingBlock].data[field.name]"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            
                            <textarea x-if="field.type === 'textarea'" 
                                      x-model="blocks[editingBlock].data[field.name]"
                                      rows="3"
                                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                            
                            <input x-if="field.type === 'color'" 
                                   type="color" 
                                   x-model="blocks[editingBlock].data[field.name]"
                                   class="h-10 w-full cursor-pointer">
                            
                            <input x-if="field.type === 'image'" 
                                   type="text" 
                                   x-model="blocks[editingBlock].data[field.name]"
                                   placeholder="Image URL"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </template>
                    
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <button @click="saveBlockConfig()" class="w-full py-3 bg-indigo-600 text-white rounded-lg font-medium hover:bg-indigo-700">
                            Apply Changes
                        </button>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
function pageBuilder() {
    return {
        blocks: {{ $page->content ?? '[]' }},
        isDragging: false,
        saved: false,
        editingBlock: null,
        widgetDefinitions: @json($widgets->map(fn($w) => [
            'id' => $w->getId(), 
            'name' => $w->getName(), 
            'fields' => $w->getFields()
        ])),
        
        onDragStart(event, widgetId) {
            event.dataTransfer.setData('widgetId', widgetId);
        },
        
        onDrop(event) {
            const widgetId = event.dataTransfer.getData('widgetId');
            if (widgetId) {
                this.addWidget(widgetId);
            }
            this.isDragging = false;
        },
        
        addWidget(widgetId) {
            const def = this.widgetDefinitions.find(w => w.id === widgetId);
            const newBlock = {
                id: 'block_' + Date.now(),
                type: widgetId,
                data: {}
            };
            
            // Set defaults
            def.fields.forEach(f => {
                if(f.default) newBlock.data[f.name] = f.default;
            });
            
            this.blocks.push(newBlock);
            this.editingBlock = this.blocks.length - 1;
        },
        
        renderBlock(block) {
            // In real app, this would fetch rendered HTML from server or use client-side templates
            return `<div class="p-10 bg-gray-50 text-center text-gray-500">Preview of ${block.type}</div>`;
        },
        
        removeBlock(index) {
            if(confirm('Remove this section?')) {
                this.blocks.splice(index, 1);
                this.editingBlock = null;
            }
        },
        
        moveUp(index) {
            if (index > 0) {
                [this.blocks[index], this.blocks[index - 1]] = [this.blocks[index - 1], this.blocks[index]];
            }
        },
        
        moveDown(index) {
            if (index < this.blocks.length - 1) {
                [this.blocks[index], this.blocks[index + 1]] = [this.blocks[index + 1], this.blocks[index]];
            }
        },
        
        editBlock(index) {
            this.editingBlock = index;
        },
        
        saveBlockConfig() {
            this.editingBlock = null;
        },
        
        getFieldDefinition(type) {
            const def = this.widgetDefinitions.find(w => w.id === type);
            return def ? def.fields : [];
        },
        
        async save() {
            try {
                const response = await fetch('{{ route('admin.pages.builder.update', $page->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ blocks: this.blocks })
                });
                
                if (response.ok) {
                    this.saved = true;
                    setTimeout(() => this.saved = false, 2000);
                }
            } catch (e) {
                alert('Error saving page');
            }
        },
        
        preview() {
            // Open preview in new window
            const win = window.open('', '_blank');
            win.document.write('<html><head><title>Preview</title></head><body>' + JSON.stringify(this.blocks) + '</body></html>');
        }
    }
}
</script>
@endsection
