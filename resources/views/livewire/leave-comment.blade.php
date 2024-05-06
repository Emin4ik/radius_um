<div>
    {{-- <input wire:model="commentText" class="text-black" placeholder="Type your comment here..." /> --}}
    {{-- <div>
        <input  wire:model="commentText" type="text" id="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Leave a comment here..." required />
    </div>
    <button wire:click="addComment" class="text-white bg-black-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Add Comment</button> --}}
    <div class="flex items-center w-full max-w-md mt-4 mb-3 seva-fields formkit-fields">
        <div class="relative w-full mr-3 formkit-field">
            <input wire:model="commentText" class="formkit-input bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="comment" placeholder="Leave a comment here..." required="" type="text" >
        </div>
        @error('commentText') <span class="text-red-500">{{ $message }}</span> @enderror
        <button data-element="submit" class="formkit-submit" wire:click="addComment">
            <span class="px-5 py-3 text-sm font-medium text-center text-white bg-gray-700 rounded-lg cursor-pointer hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:bg-gray-600 dark:hover:bg-gray-900 dark:focus:ring-blue-800">Send</span>
        </button>
    </div>
</div>
