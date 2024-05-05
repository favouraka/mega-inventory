<div class="sm:p-4 p-8 h-lvh flex">
    <form wire:submit.prevent='login' class="m-auto flex-1 p-4 md:p-6 shadow-lg border space-y-4 lg:max-w-xs">
        <p class="text-3xl font-extralight">User Login</p>
        @if ($errors->first())
            <div class="p-4 border border-red-500 bg-red-100 text-red-500 rounded-md">
                <p class="font-light">The credentials do not match our records.</p>
            </div>
        @endif
        <div id="field" class="w-full">
            <label for="username" class="font-bold block">Username</label>
            <input type="text" wire:model='username' @class(['w-full p-2 rounded border ','border-gray-400' => !$errors->first() , ' border-red-500' => $errors->any()])>            
        </div>
        <div id="field" class="w-full">
            <label for="username" class="font-bold block">Password</label>
            <input type="password" wire:model='password' @class(['w-full p-2 rounded border ','border-gray-400' => !$errors->first() , ' border-red-500' => $errors->any()])>            
        </div>
        <button wire:loading.attr='disabled' class="px-3 p-2 disabled:bg-indigo-400 text-white bg-indigo-600 rounded">Log in</button>
    </form>
</div>
