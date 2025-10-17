<div id="eventModal" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm flex justify-center items-center" 
     @click.self="closeModal()">
  <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 w-full max-w-lg border-2 border-[#c4dafa] dark:border-gray-700 
              transform transition-all duration-300 hover:scale-[1.01]">
    
    {{-- Contenido del modal (generado dinámicamente) --}}
    <div id="event-modal-body" class="prose dark:prose-invert max-w-none"></div>
    
    {{-- Botones con mejor estilo --}}
    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
      <button id="edit-btn" 
              class="hidden inline-flex items-center gap-2 px-4 py-2 bg-[#84b6f4] hover:bg-[#4d82bc] text-white font-medium rounded-lg shadow-md 
                     transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-[#4d82bc] focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Editar
      </button>
      <button id="delete-btn" 
              class="hidden inline-flex items-center gap-2 px-4 py-2 bg-[#e57373] hover:bg-[#d32f2f] text-white font-medium rounded-lg shadow-md 
                     transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        Eliminar
      </button>
      <button onclick="closeModal()" 
              class="inline-flex items-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg shadow-md 
                     transition-all duration-200 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
        Cerrar
      </button>
    </div>
  </div>
</div>



